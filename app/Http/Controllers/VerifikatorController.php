<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\IdentityVerification;
use App\Models\Report;
use App\Models\Notification;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class VerifikatorController extends Controller
{
    private function isAdmin(): bool
    {
        $user = Auth::user();
        return $user && in_array(strtolower($user->role->role_name ?? ''), ['admin', 'verifikator'], true);
    }

    // ================= 1. DASHBOARD =================
    public function dashboard()
    {
        $pending = IdentityVerification::select(['id_identity_verification', 'user_id', 'membership_id', 'status', 'payment_method', 'submitted_at'])
            ->with(['user:id_user,name,email', 'membership:id_membership,name'])
            ->where('status', 'pending')
            ->latest('id_identity_verification')
            ->paginate(10);

        $identityStats = IdentityVerification::selectRaw("
            COUNT(CASE WHEN status = 'pending' THEN 1 END) AS pending_ktp,
            COUNT(CASE WHEN status = 'approved' THEN 1 END) AS approved,
            COUNT(CASE WHEN status = 'rejected' THEN 1 END) AS rejected
        ")->first();

        $productStats = Product::selectRaw("
            COUNT(CASE WHEN status = 'pending' THEN 1 END) AS pending_produk,
            COUNT(CASE WHEN status = 'approved' THEN 1 END) AS approved,
            COUNT(CASE WHEN status = 'rejected' THEN 1 END) AS rejected
        ")->first();

        $pendingPembayaran = IdentityVerification::where('status', 'pending')->whereNotNull('payment_method')->count();
        $laporanMasuk = Report::where('status', 'pending')->count();

        $approvedCount = ($identityStats->approved ?? 0) + ($productStats->approved ?? 0);
        $rejectedCount = ($identityStats->rejected ?? 0) + ($productStats->rejected ?? 0);
        $pendingKtp = $identityStats->pending_ktp ?? 0;
        $pendingProduk = $productStats->pending_produk ?? 0;

        return view('verifikator.dashboard', compact(
            'pending', 'pendingKtp', 'pendingProduk', 'pendingPembayaran', 'laporanMasuk', 'approvedCount', 'rejectedCount'
        ));
    }

    // ================= 2. VERIFIKASI IDENTITAS =================
    public function identitas(Request $request)
    {
        $tab = $request->get('tab', 'pending');
        $query = IdentityVerification::select(['id_identity_verification', 'user_id', 'membership_id', 'verifier_id', 'status', 'notes', 'payment_method', 'submitted_at', 'verified_at'])
            ->with(['user:id_user,name,email', 'membership:id_membership,name', 'verifier:id_user,name']);

        if ($tab === 'history') {
            $verifications = $query->whereIn('status', ['approved', 'rejected'])->latest('id_identity_verification')->paginate(10)->withQueryString();
        } else {
            $verifications = $query->where('status', 'pending')->latest('id_identity_verification')->paginate(10)->withQueryString();
        }

        return view('verifikator.identitas', compact('verifications', 'tab'));
    }

    public function show($id)
    {
        $registration = IdentityVerification::with(['user', 'membership', 'verifier'])->findOrFail($id);
        $verification = $registration;
        return view('verifikator.detail-pendaftaran', compact('registration', 'verification'));
    }

    public function approve($id)
    {
        if (!$this->isAdmin()) return redirect()->back()->with('error', 'Akses ditolak!');

        try {
            DB::transaction(function () use ($id) {
                $verification = IdentityVerification::lockForUpdate()->findOrFail($id);
                if ($verification->status !== 'pending') throw new \RuntimeException('Pengajuan ini sudah diproses sebelumnya.');

                $sellerRole = Role::where('role_name', 'penjual')->firstOrFail();
                $user = User::where('id_user', $verification->user_id)->lockForUpdate()->firstOrFail();

                $userData = ['id_role' => $sellerRole->id_role, 'status' => 'active'];

                if ($verification->membership_id && $membership = Membership::find($verification->membership_id)) {
                    $userData['id_membership'] = $membership->id_membership;
                    $userData['membership_expires_at'] = now()->addDays($membership->duration_days ?? 30);
                }

                $user->update($userData);
                $verification->update(['status' => 'approved', 'verifier_id' => Auth::id(), 'verified_at' => now()]);

                Notification::create([
                    'user_id'     => $verification->user_id,
                    'name'        => '🎉 Verifikasi Disetujui',
                    'description' => 'Selamat! Pendaftaran akun penjual Anda telah disetujui. Sekarang Anda dapat mulai berjualan.',
                    'is_read'     => false,
                ]);
            });

            return redirect()->route('verifikator.identitas')->with('success', 'Pendaftaran berhasil disetujui.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Gagal memproses verifikasi: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        if (!$this->isAdmin()) return redirect()->back()->with('error', 'Akses ditolak!');

        $note = $request->input('notes') ?: $request->input('rejection_note');
        if (!$note) return redirect()->back()->with('error', 'Catatan / alasan penolakan wajib diisi.');

        try {
            DB::transaction(function () use ($id, $note) {
                $verification = IdentityVerification::lockForUpdate()->findOrFail($id);
                if ($verification->status !== 'pending') throw new \RuntimeException('Pengajuan ini sudah diproses sebelumnya.');

                $verification->update(['status' => 'rejected', 'notes' => $note, 'verifier_id' => Auth::id(), 'verified_at' => now()]);

                Notification::create([
                    'user_id'     => $verification->user_id,
                    'name'        => '❌ Verifikasi Ditolak',
                    'description' => 'Pendaftaran penjual Anda ditolak. Catatan: ' . $note,
                    'is_read'     => false,
                ]);
            });

            return redirect()->route('verifikator.identitas')->with('success', 'Pendaftaran berhasil ditolak.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Gagal menolak verifikasi: ' . $e->getMessage());
        }
    }

    // ================= 3. VERIFIKASI PRODUK =================
    public function produk(Request $request)
    {
        $tab = $request->get('tab', 'pending');
        $query = Product::select(['id_product', 'seller_id', 'user_id', 'category_id', 'title', 'name', 'status', 'rejection_note', 'created_at'])
            ->with(['seller:id_user,name', 'category:id_category,name']);

        if ($tab === 'history') {
            $query->whereIn('status', ['approved', 'rejected', 'active', 'inactive']);
        } else {
            $query->where('status', 'pending');
        }

        $products = $query->latest('id_product')->paginate(10)->withQueryString();
        return view('verifikator.produk', compact('products', 'tab'));
    }

    public function showProduk($id)
    {
        $product = Product::with(['seller', 'category'])->findOrFail($id);
        return view('verifikator.detail-produk', compact('product'));
    }

    public function approveProduk($id)
    {
        if (!$this->isAdmin()) return redirect()->back()->with('error', 'Akses ditolak!');

        try {
            DB::transaction(function () use ($id) {
                $product = Product::lockForUpdate()->findOrFail($id);
                if ($product->status !== 'pending') throw new \RuntimeException('Produk ini sudah diproses sebelumnya.');

                $product->update(['status' => 'active', 'rejection_note' => null]);

                Notification::create([
                    'user_id'     => $product->seller_id ?? $product->user_id,
                    'name'        => '✅ Produk Disetujui',
                    'description' => 'Produk "' . ($product->title ?? $product->name) . '" Anda telah diverifikasi dan diterbitkan.',
                    'is_read'     => false,
                ]);
            });

            return redirect()->route('verifikator.produk')->with('success', 'Produk berhasil disetujui.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Gagal menyetujui produk: ' . $e->getMessage());
        }
    }

    public function rejectProduk(Request $request, $id)
    {
        if (!$this->isAdmin()) return redirect()->back()->with('error', 'Akses ditolak!');

        $validated = $request->validate(['rejection_note' => 'required|string|max:500']);

        try {
            DB::transaction(function () use ($id, $validated) {
                $product = Product::lockForUpdate()->findOrFail($id);
                if ($product->status !== 'pending') throw new \RuntimeException('Produk ini sudah diproses sebelumnya.');

                $product->update(['status' => 'rejected', 'rejection_note' => $validated['rejection_note']]);

                Notification::create([
                    'user_id'     => $product->seller_id ?? $product->user_id,
                    'name'        => '❌ Produk Ditolak',
                    'description' => 'Produk "' . ($product->title ?? $product->name) . '" ditolak. Catatan: ' . $validated['rejection_note'],
                    'is_read'     => false,
                ]);
            });

            return redirect()->route('verifikator.produk')->with('success', 'Produk berhasil ditolak.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Gagal menolak produk: ' . $e->getMessage());
        }
    }

    // ================= 4. VERIFIKASI PEMBAYARAN =================
    public function pembayaran(Request $request)
    {
        $tab = $request->get('tab', 'pending');
        $query = IdentityVerification::select(['id_identity_verification', 'user_id', 'membership_id', 'verifier_id', 'status', 'payment_method', 'notes', 'submitted_at', 'verified_at'])
            ->with(['user:id_user,name,email', 'membership:id_membership,name,price,duration_days', 'verifier:id_user,name'])
            ->whereNotNull('payment_method');

        if ($tab === 'history') {
            $payments = $query->whereIn('status', ['approved', 'rejected'])->latest('id_identity_verification')->paginate(10)->withQueryString();
        } else {
            $payments = $query->where('status', 'pending')->latest('id_identity_verification')->paginate(10)->withQueryString();
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
        return $this->approve($id);
    }

    public function rejectPembayaran(Request $request, $id)
    {
        return $this->reject($request, $id);
    }

    // ================= 5. LAPORAN PELANGGARAN =================
    public function laporan(Request $request)
    {
        $tab = $request->get('tab', 'pending');
        $query = Report::with(['reporter', 'reportedUser', 'product']);

        if ($tab === 'history') {
            $query->whereIn('status', ['resolved', 'dismissed', 'action_taken']);
        } else {
            $query->where('status', 'pending');
        }

        $reports = $query->latest()->paginate(10)->withQueryString();
        return view('verifikator.laporan', compact('reports', 'tab'));
    }

    public function showLaporan($id)
    {
        $report = Report::with(['reporter', 'reportedUser', 'product'])->findOrFail($id);
        return view('verifikator.detail-laporan', compact('report'));
    }

    public function actionLaporan(Request $request, $id)
    {
        if (!$this->isAdmin()) return redirect()->back()->with('error', 'Akses ditolak!');

        $validated = $request->validate([
            'action' => 'required|in:warning,takedown,dismiss',
            'note'   => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($id, $validated) {
                $report = Report::lockForUpdate()->findOrFail($id);
                if ($report->status !== 'pending') throw new \RuntimeException('Laporan ini sudah diproses sebelumnya.');

                $action = $validated['action'];
                $note = $validated['note'] ?? null;

                if ($action === 'warning') {
                    $report->status = 'resolved';
                    if ($report->reported_user_id) {
                        Notification::create([
                            'user_id'     => $report->reported_user_id,
                            'name'        => '⚠️ Peringatan Pelanggaran',
                            'description' => 'Akun Anda mendapatkan teguran terkait laporan: ' . ($note ?? 'Pelanggaran ketentuan platform.'),
                            'is_read'     => false,
                        ]);
                    }
                } elseif ($action === 'takedown') {
                    $report->status = 'resolved';
                    if ($report->product_id) Product::where('id_product', $report->product_id)->update(['status' => 'inactive']);
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

                $report->update([
                    'action_taken' => $action,
                    'admin_note'   => $note,
                ]);
            });

            return redirect()->route('verifikator.laporan')->with('success', 'Tindakan laporan pelanggaran berhasil diproses.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Gagal memproses tindakan laporan: ' . $e->getMessage());
        }
    }

    // ================= 6. PROFIL VERIFIKATOR =================
    public function profile()
    {
        $user = Auth::user();
        return view('verifikator.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id_user . ',id_user',
            'phone'    => ['nullable', 'string', 'max:20', 'regex:/^(\+62|08)[0-9]{8,13}$/'],
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'phone.regex' => 'No. telepon harus diawali 08 atau +62 dan minimal 10 digit.',
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('verifikator.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}