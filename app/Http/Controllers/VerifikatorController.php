<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\IdentityVerification;
use App\Models\Report;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VerifikatorController extends Controller
{
    /**
     * Helper privat untuk mengecek apakah pengguna yang login adalah Admin.
     */
    private function isAdmin()
    {
        $roleName = strtolower(Auth::user()->role->name ?? '');
        return $roleName === 'admin';
    }

    /**
     * 1. DASHBOARD VERIFIKATOR (CS & Admin)
     */
    public function dashboard()
    {
        $pendingKtp = IdentityVerification::where('status', 'pending')->count();
        $pendingProduk = Product::where('status', 'pending')->count();
        
        $pendingPembayaran = IdentityVerification::where('status', 'pending')
            ->whereNotNull('payment_method')
            ->count();

        $laporanMasuk = Report::where('status', 'pending')->count();

        $approvedCount = IdentityVerification::where('status', 'approved')->count() + Product::where('status', 'approved')->count();
        $rejectedCount = IdentityVerification::where('status', 'rejected')->count() + Product::where('status', 'rejected')->count();

        return view('verifikator.dashboard', compact(
            'pendingKtp',
            'pendingProduk',
            'pendingPembayaran',
            'laporanMasuk',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * 2. VERIFIKASI IDENTITAS / KTP & PENDAFTARAN
     */
    public function identitas(Request $request)
    {
        $tab = $request->get('tab', 'pending');

        if ($tab === 'history') {
            $verifications = IdentityVerification::with(['user', 'membership', 'verifier'])
                ->whereIn('status', ['approved', 'rejected'])
                ->latest()
                ->paginate(10);
        } else {
            $verifications = IdentityVerification::with(['user', 'membership'])
                ->where('status', 'pending')
                ->latest()
                ->paginate(10);
        }

        return view('verifikator.identitas', compact('verifications', 'tab'));
    }

    /**
     * Alias method 'show' sesuai route /pendaftaran/{id}
     */
    public function show($id)
    {
        $verification = IdentityVerification::with(['user', 'membership', 'verifier'])->findOrFail($id);
        return view('verifikator.detail-pendaftaran', compact('verification'));
    }

    /**
     * Approve Pendaftaran / Identitas (Khusus Admin)
     */
    public function approve($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak! Customer Service (CS) hanya dapat melihat data. Persetujuan hanya dapat dilakukan oleh Admin.');
        }

        DB::beginTransaction();
        try {
            $verification = IdentityVerification::findOrFail($id);
            $verification->status = 'approved';
            $verification->verifier_id = Auth::id();
            $verification->verified_at = now();
            $verification->save();

            // Ubah role user dari Pembeli menjadi Penjual
            $sellerRole = Role::where('name', 'penjual')->orWhere('name', 'seller')->first();
            $user = User::where('id_user', $verification->user_id)->first();
            
            if ($sellerRole && $user) {
                $user->role_id = $sellerRole->id_role ?? $sellerRole->id;
                $user->save();
            }

            Notification::create([
                'user_id' => $verification->user_id,
                'title' => 'Verifikasi Disetujui',
                'message' => 'Selamat! Pendaftaran akun penjual Anda telah disetujui. Sekarang Anda dapat mulai berjualan.',
                'type' => 'info',
                'is_read' => false,
            ]);

            DB::commit();
            return redirect()->route('verifikator.identitas')->with('success', 'Pendaftaran berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Reject Pendaftaran / Identitas (Khusus Admin)
     */
    public function reject(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak! Customer Service (CS) hanya dapat melihat data. Penolakan hanya dapat dilakukan oleh Admin.');
        }

        $request->validate([
            'rejection_note' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $verification = IdentityVerification::findOrFail($id);
            $verification->status = 'rejected';
            $verification->notes = $request->rejection_note;
            $verification->verifier_id = Auth::id();
            $verification->verified_at = now();
            $verification->save();

            Notification::create([
                'user_id' => $verification->user_id,
                'title' => 'Verifikasi Ditolak',
                'message' => 'Pendaftaran penjual Anda ditolak. Catatan: ' . $request->rejection_note,
                'type' => 'warning',
                'is_read' => false,
            ]);

            DB::commit();
            return redirect()->route('verifikator.identitas')->with('success', 'Pendaftaran berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menolak verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * 3. VERIFIKASI PRODUK & JASA
     */
    public function produk(Request $request)
    {
        $tab = $request->get('tab', 'pending');

        if ($tab === 'history') {
            $products = Product::with(['user', 'category'])
                ->whereIn('status', ['approved', 'rejected', 'active', 'inactive'])
                ->latest()
                ->paginate(10);
        } else {
            $products = Product::with(['user', 'category'])
                ->where('status', 'pending')
                ->latest()
                ->paginate(10);
        }

        return view('verifikator.produk', compact('products', 'tab'));
    }

    public function showProduk($id)
    {
        $product = Product::with(['user', 'category'])->findOrFail($id);
        return view('verifikator.detail-produk', compact('product'));
    }

    public function approveProduk($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Admin yang berhak menyetujui produk.');
        }

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->status = 'approved';
            $product->save();

            Notification::create([
                'user_id' => $product->user_id,
                'title' => 'Produk Disetujui',
                'message' => 'Produk "' . $product->name . '" Anda telah diverifikasi dan diterbitkan.',
                'type' => 'info',
                'is_read' => false,
            ]);

            DB::commit();
            return redirect()->route('verifikator.produk')->with('success', 'Produk berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyetujui produk: ' . $e->getMessage());
        }
    }

    public function rejectProduk(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Admin yang berhak menolak produk.');
        }

        $request->validate([
            'rejection_note' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->status = 'rejected';
            $product->save();

            Notification::create([
                'user_id' => $product->user_id,
                'title' => 'Produk Ditolak',
                'message' => 'Produk "' . $product->name . '" ditolak. Catatan: ' . $request->rejection_note,
                'type' => 'warning',
                'is_read' => false,
            ]);

            DB::commit();
            return redirect()->route('verifikator.produk')->with('success', 'Produk berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menolak produk: ' . $e->getMessage());
        }
    }

    /**
     * 4. VERIFIKASI PEMBAYARAN & MEMBERSHIP
     */
    public function pembayaran(Request $request)
    {
        $tab = $request->get('tab', 'pending');

        $query = IdentityVerification::with(['user', 'membership', 'verifier'])
            ->whereNotNull('payment_method');

        if ($tab === 'history') {
            $payments = $query->whereIn('status', ['approved', 'rejected'])->latest()->paginate(10);
        } else {
            $payments = $query->where('status', 'pending')->latest()->paginate(10);
        }

        return view('verifikator.pembayaran', compact('payments', 'tab'));
    }

    public function showPembayaran($id)
    {
        $payment = IdentityVerification::with(['user', 'membership', 'verifier'])->findOrFail($id);
        return view('verifikator.detail-pembayaran', compact('payment'));
    }

    public function approvePembayaran($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak! CS hanya dapat memeriksa transaksi. Konfirmasi pembayaran hanya dapat dilakukan oleh Admin.');
        }

        DB::beginTransaction();
        try {
            $verification = IdentityVerification::findOrFail($id);
            $verification->status = 'approved';
            $verification->verifier_id = Auth::id();
            $verification->verified_at = now();
            $verification->save();

            $sellerRole = Role::where('name', 'penjual')->orWhere('name', 'seller')->first();
            $user = User::where('id_user', $verification->user_id)->first();
            
            if ($sellerRole && $user) {
                $user->role_id = $sellerRole->id_role ?? $sellerRole->id;
                $user->save();
            }

            Notification::create([
                'user_id' => $verification->user_id,
                'title' => 'Pembayaran Dikonfirmasi',
                'message' => 'Pembayaran paket membership Anda berhasil dikonfirmasi lunas.',
                'type' => 'info',
                'is_read' => false,
            ]);

            DB::commit();
            return redirect()->route('verifikator.pembayaran')->with('success', 'Pembayaran berhasil dikonfirmasi lunas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function rejectPembayaran(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak! CS hanya dapat memeriksa transaksi. Penolakan pembayaran hanya dapat dilakukan oleh Admin.');
        }

        $request->validate([
            'rejection_note' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $verification = IdentityVerification::findOrFail($id);
            $verification->status = 'rejected';
            $verification->notes = $request->rejection_note;
            $verification->verifier_id = Auth::id();
            $verification->verified_at = now();
            $verification->save();

            Notification::create([
                'user_id' => $verification->user_id,
                'title' => 'Pembayaran Ditolak',
                'message' => 'Pembayaran paket membership Anda ditolak. Catatan: ' . $request->rejection_note,
                'type' => 'danger',
                'is_read' => false,
            ]);

            DB::commit();
            return redirect()->route('verifikator.pembayaran')->with('success', 'Pembayaran berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * 5. LAPORAN PELANGGARAN
     */
    public function laporan(Request $request)
    {
        $tab = $request->get('tab', 'pending');

        if ($tab === 'history') {
            $reports = Report::with(['reporter', 'reportedUser', 'product'])
                ->whereIn('status', ['resolved', 'dismissed', 'action_taken'])
                ->latest()
                ->paginate(10);
        } else {
            $reports = Report::with(['reporter', 'reportedUser', 'product'])
                ->where('status', 'pending')
                ->latest()
                ->paginate(10);
        }

        return view('verifikator.laporan', compact('reports', 'tab'));
    }

    public function showLaporan($id)
    {
        $report = Report::with(['reporter', 'reportedUser', 'product'])->findOrFail($id);
        return view('verifikator.detail-laporan', compact('report'));
    }

    public function actionLaporan(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak! Penanganan tindakan disiplin laporan hanya dapat dilakukan oleh Admin.');
        }

        $request->validate([
            'action' => 'required|in:warning,takedown,dismiss',
            'note'   => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $report = Report::findOrFail($id);

            if ($request->action === 'warning') {
                $report->status = 'resolved';
                
                if ($report->reported_user_id) {
                    Notification::create([
                        'user_id' => $report->reported_user_id,
                        'title' => 'Peringatan Pelanggaran',
                        'message' => 'Akun Anda mendapatkan teguran terkait laporan pengaduan: ' . ($request->note ?? 'Pelanggaran ketentuan platform.'),
                        'type' => 'warning',
                        'is_read' => false,
                    ]);
                }
            } elseif ($request->action === 'takedown') {
                $report->status = 'resolved';
                
                if ($report->product_id) {
                    $product = Product::find($report->product_id);
                    if ($product) {
                        $product->status = 'inactive';
                        $product->save();
                    }
                }

                if ($report->reported_user_id) {
                    Notification::create([
                        'user_id' => $report->reported_user_id,
                        'title' => 'Tindakan Disiplin (Takedown)',
                        'message' => 'Produk/Konten Anda telah diturunkan karena terbukti melanggar aturan.',
                        'type' => 'danger',
                        'is_read' => false,
                    ]);
                }
            } else {
                $report->status = 'dismissed';
            }

            $report->action_taken = $request->action;
            $report->admin_note = $request->note;
            $report->save();

            DB::commit();
            return redirect()->route('verifikator.laporan')->with('success', 'Tindakan laporan pelanggaran berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses tindakan laporan: ' . $e->getMessage());
        }
    }
}
