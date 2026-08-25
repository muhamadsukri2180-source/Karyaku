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
        $roleName = strtolower(Auth::user()->role->role_name ?? '');
        return in_array($roleName, ['admin', 'verifikator']);
    }

    /**
     * 1. DASHBOARD VERIFIKATOR (CS & Admin)
     */
    public function dashboard()
    {
        $pending = IdentityVerification::with(['user', 'membership'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        $pendingKtp = IdentityVerification::where('status', 'pending')->count();
        $pendingProduk = Product::where('status', 'pending')->count();
        
        $pendingPembayaran = IdentityVerification::where('status', 'pending')
            ->whereNotNull('payment_method')
            ->count();

        $laporanMasuk = Report::where('status', 'pending')->count();

        $approvedCount = IdentityVerification::where('status', 'approved')->count() + Product::where('status', 'approved')->count();
        $rejectedCount = IdentityVerification::where('status', 'rejected')->count() + Product::where('status', 'rejected')->count();

        return view('verifikator.dashboard', compact(
            'pending',
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
        $registration = IdentityVerification::with(['user', 'membership', 'verifier'])->findOrFail($id);
        $verification = $registration;
        return view('verifikator.detail-pendaftaran', compact('registration', 'verification'));
    }

    /**
     * Approve Pendaftaran / Identitas (Khusus Admin / Verifikator)
     */
    /**
     * Approve Pendaftaran / Identitas (Khusus Admin / Verifikator)
     */
    public function approve($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        DB::beginTransaction();
        try {
            $verification = IdentityVerification::findOrFail($id);
            $verification->status = 'approved';
            $verification->verifier_id = Auth::id();
            $verification->verified_at = now();
            $verification->save();

            // Ubah role user dari Pembeli menjadi Penjual
            $sellerRole = Role::where('role_name', 'penjual')->first();
            $user = User::where('id_user', $verification->user_id)->first();
            
            if ($sellerRole && $user) {
                $user->id_role = $sellerRole->id_role;
                if ($verification->membership_id) {
                    $user->id_membership = $verification->membership_id;
                    $membership = \App\Models\Membership::find($verification->membership_id);
                    if ($membership) {
                        $user->membership_expires_at = now()->addDays($membership->duration_days ?? 30);
                    }
                }
                $user->status = 'active';
                $user->save();
            }

            Notification::create([
                'user_id'     => $verification->user_id,
                'name'        => '🎉 Verifikasi Disetujui',
                'description' => 'Selamat! Pendaftaran akun penjual Anda telah disetujui. Sekarang Anda dapat mulai berjualan.',
                'is_read'     => false,
            ]);

            DB::commit();
            return redirect()->route('verifikator.identitas')->with('success', 'Pendaftaran berhasil disetujui. Pengguna kini resmi menjadi Penjual.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Reject Pendaftaran / Identitas (Khusus Admin / Verifikator)
     */
    public function reject(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $note = $request->input('notes') ?? $request->input('rejection_note');
        if (!$note) {
            return redirect()->back()->with('error', 'Catatan / alasan penolakan wajib diisi.');
        }

        DB::beginTransaction();
        try {
            $verification = IdentityVerification::findOrFail($id);
            $verification->status = 'rejected';
            $verification->notes = $note;
            $verification->verifier_id = Auth::id();
            $verification->verified_at = now();
            $verification->save();

            Notification::create([
                'user_id'     => $verification->user_id,
                'name'        => '❌ Verifikasi Ditolak',
                'description' => 'Pendaftaran penjual Anda ditolak. Catatan: ' . $note,
                'is_read'     => false,
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
            $products = Product::with(['seller', 'category'])
                ->whereIn('status', ['approved', 'rejected', 'active', 'inactive'])
                ->latest()
                ->paginate(10);
        } else {
            $products = Product::with(['seller', 'category'])
                ->where('status', 'pending')
                ->latest()
                ->paginate(10);
        }

        return view('verifikator.produk', compact('products', 'tab'));
    }

    public function showProduk($id)
    {
        $product = Product::with(['seller', 'category'])->findOrFail($id);
        return view('verifikator.detail-produk', compact('product'));
    }

    public function approveProduk($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Admin/Verifikator yang berhak menyetujui produk.');
        }

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->status = 'active';
            $product->rejection_note = null;
            $product->save();

            Notification::create([
                'user_id'     => $product->seller_id ?? $product->user_id,
                'name'        => '✅ Produk Disetujui',
                'description' => 'Produk "' . ($product->title ?? $product->name) . '" Anda telah diverifikasi dan diterbitkan.',
                'is_read'     => false,
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
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Admin/Verifikator yang berhak menolak produk.');
        }

        $request->validate([
            'rejection_note' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->status = 'rejected';
            $product->rejection_note = $request->rejection_note;
            $product->save();

            Notification::create([
                'user_id'     => $product->seller_id ?? $product->user_id,
                'name'        => '❌ Produk Ditolak',
                'description' => 'Produk "' . ($product->title ?? $product->name) . '" ditolak. Catatan: ' . $request->rejection_note,
                'is_read'     => false,
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
            return redirect()->back()->with('error', 'Akses ditolak! Konfirmasi pembayaran hanya dapat dilakukan oleh Admin/Verifikator.');
        }

        DB::beginTransaction();
        try {
            $verification = IdentityVerification::findOrFail($id);
            $verification->status = 'approved';
            $verification->verifier_id = Auth::id();
            $verification->verified_at = now();
            $verification->save();

            // Gunakan kolom role_name sesuai struktur tabel roles
            $sellerRole = Role::where('role_name', 'penjual')->first();
            $user = User::where('id_user', $verification->user_id)->first();

            if ($sellerRole && $user) {
                $user->id_role = $sellerRole->id_role;
                if ($verification->membership_id) {
                    $user->id_membership = $verification->membership_id;
                    $membership = \App\Models\Membership::find($verification->membership_id);
                    if ($membership) {
                        $user->membership_expires_at = now()->addDays($membership->duration_days ?? 30);
                    }
                }
                $user->status = 'active';
                $user->save();
            }

            Notification::create([
                'user_id'     => $verification->user_id,
                'name'        => '💳 Pembayaran Dikonfirmasi',
                'description' => 'Pembayaran paket membership Anda berhasil dikonfirmasi lunas. Akun Anda telah aktif sebagai Penjual.',
                'is_read'     => false,
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
            return redirect()->back()->with('error', 'Akses ditolak! Penolakan pembayaran hanya dapat dilakukan oleh Admin/Verifikator.');
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
                'user_id'     => $verification->user_id,
                'name'        => '❌ Pembayaran Ditolak',
                'description' => 'Pembayaran paket membership Anda ditolak. Catatan: ' . $request->rejection_note,
                'is_read'     => false,
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
            return redirect()->back()->with('error', 'Akses ditolak! Penanganan tindakan disiplin laporan hanya dapat dilakukan oleh Admin/Verifikator.');
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
                        'user_id'     => $report->reported_user_id,
                        'name'        => '⚠️ Peringatan Pelanggaran',
                        'description' => 'Akun Anda mendapatkan teguran terkait laporan pengaduan: ' . ($request->note ?? 'Pelanggaran ketentuan platform.'),
                        'is_read'     => false,
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
                        'user_id'     => $report->reported_user_id,
                        'name'        => '⛔ Tindakan Disiplin (Takedown)',
                        'description' => 'Produk/Konten Anda telah diturunkan karena terbukti melanggar aturan.',
                        'is_read'     => false,
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

    /**
     * 6. PROFIL VERIFIKATOR
     */
    public function profile()
    {
        $user = Auth::user();
        return view('verifikator.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id_user . ',id_user',
            'phone'  => ['nullable', 'string', 'max:20', 'regex:/^(\+62|08)[0-9]{8,13}$/'],
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'phone.regex' => 'No. telepon harus diawali 08 atau +62 dan minimal 10 digit.',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('verifikator.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
