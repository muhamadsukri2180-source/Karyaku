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

class VerifikatorController extends Controller
{
    /**
     * ============================================================
     * HELPER CEK AKSES
     * ============================================================
     *
     * Hanya Admin dan Verifikator yang boleh melakukan tindakan
     * verifikasi / persetujuan / penolakan.
     */
    private function isAdmin(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        $roleName = strtolower($user->role->role_name ?? '');

        return in_array($roleName, ['admin', 'verifikator'], true);
    }

    /**
     * ============================================================
     * 1. DASHBOARD VERIFIKATOR
     * ============================================================
     */
    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | Data antrean identitas
        |--------------------------------------------------------------------------
        */

        $pending = IdentityVerification::select([
                'id_identity_verification',
                'user_id',
                'membership_id',
                'status',
                'payment_method',
                'submitted_at',
            ])
            ->with([
                'user:id_user,name,email',
                'membership:id_membership,name',
            ])
            ->where('status', 'pending')
            ->latest('id_identity_verification')
            ->paginate(10);

        /*
        |--------------------------------------------------------------------------
        | Statistik identitas
        |--------------------------------------------------------------------------
        */

        $identityStats = IdentityVerification::selectRaw("
                COUNT(CASE WHEN status = 'pending' THEN 1 END) AS pending_ktp,
                COUNT(CASE WHEN status = 'approved' THEN 1 END) AS approved,
                COUNT(CASE WHEN status = 'rejected' THEN 1 END) AS rejected
            ")
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Statistik produk
        |--------------------------------------------------------------------------
        */

        $productStats = Product::selectRaw("
                COUNT(CASE WHEN status = 'pending' THEN 1 END) AS pending_produk,
                COUNT(CASE WHEN status = 'approved' THEN 1 END) AS approved,
                COUNT(CASE WHEN status = 'rejected' THEN 1 END) AS rejected
            ")
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Pembayaran pending
        |--------------------------------------------------------------------------
        */

        $pendingPembayaran = IdentityVerification::where('status', 'pending')
            ->whereNotNull('payment_method')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Laporan masuk
        |--------------------------------------------------------------------------
        */

        $laporanMasuk = Report::where('status', 'pending')->count();

        /*
        |--------------------------------------------------------------------------
        | Total approved & rejected
        |--------------------------------------------------------------------------
        */

        $approvedCount =
            ($identityStats->approved ?? 0) +
            ($productStats->approved ?? 0);

        $rejectedCount =
            ($identityStats->rejected ?? 0) +
            ($productStats->rejected ?? 0);

        $pendingKtp = $identityStats->pending_ktp ?? 0;
        $pendingProduk = $productStats->pending_produk ?? 0;

        return view(
            'verifikator.dashboard',
            compact(
                'pending',
                'pendingKtp',
                'pendingProduk',
                'pendingPembayaran',
                'laporanMasuk',
                'approvedCount',
                'rejectedCount'
            )
        );
    }

    /**
     * ============================================================
     * 2. VERIFIKASI IDENTITAS
     * ============================================================
     */
    public function identitas(Request $request)
    {
        $tab = $request->get('tab', 'pending');

        /*
        |--------------------------------------------------------------------------
        | HISTORY
        |--------------------------------------------------------------------------
        */

        if ($tab === 'history') {

            $verifications = IdentityVerification::select([
                    'id_identity_verification',
                    'user_id',
                    'membership_id',
                    'verifier_id',
                    'status',
                    'notes',
                    'payment_method',
                    'submitted_at',
                    'verified_at',
                ])
                ->with([
                    'user:id_user,name,email',
                    'membership:id_membership,name',
                    'verifier:id_user,name',
                ])
                ->whereIn('status', ['approved', 'rejected'])
                ->latest('id_identity_verification')
                ->paginate(10)
                ->withQueryString();

        } else {

            /*
            |--------------------------------------------------------------------------
            | PENDING
            |--------------------------------------------------------------------------
            */

            $verifications = IdentityVerification::select([
                    'id_identity_verification',
                    'user_id',
                    'membership_id',
                    'status',
                    'payment_method',
                    'submitted_at',
                ])
                ->with([
                    'user:id_user,name,email',
                    'membership:id_membership,name',
                ])
                ->where('status', 'pending')
                ->latest('id_identity_verification')
                ->paginate(10)
                ->withQueryString();
        }

        return view(
            'verifikator.identitas',
            compact('verifications', 'tab')
        );
    }

    /**
     * ============================================================
     * DETAIL PENDAFTARAN
     * ============================================================
     */
    public function show($id)
    {
        $registration = IdentityVerification::with([
            'user',
            'membership',
            'verifier',
        ])->findOrFail($id);

        $verification = $registration;

        return view(
            'verifikator.detail-pendaftaran',
            compact('registration', 'verification')
        );
    }

    /**
     * ============================================================
     * APPROVE IDENTITAS / PENDAFTARAN PENJUAL
     * ============================================================
     */
    public function approve($id)
    {
        if (!$this->isAdmin()) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Akses ditolak!'
                );
        }

        try {

            DB::transaction(function () use ($id) {

                /*
                |--------------------------------------------------------------------------
                | Ambil verifikasi
                |--------------------------------------------------------------------------
                */

                $verification = IdentityVerification::lockForUpdate()
                    ->findOrFail($id);

                /*
                |--------------------------------------------------------------------------
                | Pastikan masih pending
                |--------------------------------------------------------------------------
                */

                if ($verification->status !== 'pending') {
                    throw new \RuntimeException(
                        'Pengajuan ini sudah diproses sebelumnya.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Role Penjual
                |--------------------------------------------------------------------------
                */

                $sellerRole = Role::where(
                    'role_name',
                    'penjual'
                )->first();

                if (!$sellerRole) {
                    throw new \RuntimeException(
                        'Role penjual tidak ditemukan.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | User pemohon
                |--------------------------------------------------------------------------
                */

                $user = User::where(
                    'id_user',
                    $verification->user_id
                )
                    ->lockForUpdate()
                    ->first();

                if (!$user) {
                    throw new \RuntimeException(
                        'User pemohon tidak ditemukan.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Data update user
                |--------------------------------------------------------------------------
                */

                $userData = [
                    'id_role' => $sellerRole->id_role,
                    'status'  => 'active',
                ];

                /*
                |--------------------------------------------------------------------------
                | Membership
                |--------------------------------------------------------------------------
                */

                if ($verification->membership_id) {

                    $membership = Membership::select([
                        'id_membership',
                        'duration_days',
                    ])->find(
                        $verification->membership_id
                    );

                    if ($membership) {

                        $userData['id_membership'] =
                            $membership->id_membership;

                        $userData['membership_expires_at'] =
                            now()->addDays(
                                $membership->duration_days ?? 30
                            );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Update User
                |--------------------------------------------------------------------------
                */

                $user->update($userData);

                /*
                |--------------------------------------------------------------------------
                | Update verifikasi
                |--------------------------------------------------------------------------
                */

                $verification->update([
                    'status'      => 'approved',
                    'verifier_id' => Auth::id(),
                    'verified_at' => now(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Notification
                |--------------------------------------------------------------------------
                */

                Notification::create([
                    'user_id'     => $verification->user_id,
                    'name'        => '🎉 Verifikasi Disetujui',
                    'description' =>
                        'Selamat! Pendaftaran akun penjual Anda telah disetujui. Sekarang Anda dapat mulai berjualan.',
                    'is_read'     => false,
                ]);
            });

            return redirect()
                ->route('verifikator.identitas')
                ->with(
                    'success',
                    'Pendaftaran berhasil disetujui. Pengguna kini resmi menjadi Penjual.'
                );

        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Gagal memproses verifikasi: ' . $e->getMessage()
                );
        }
    }

    /**
     * ============================================================
     * REJECT IDENTITAS / PENDAFTARAN
     * ============================================================
     */
    public function reject(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Akses ditolak!'
                );
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
            'rejection_note' => 'nullable|string|max:500',
        ]);

        $note =
            $request->input('notes')
            ?: $request->input('rejection_note');

        if (!$note) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Catatan / alasan penolakan wajib diisi.'
                );
        }

        try {

            DB::transaction(function () use ($id, $note) {

                $verification = IdentityVerification::lockForUpdate()
                    ->findOrFail($id);

                if ($verification->status !== 'pending') {
                    throw new \RuntimeException(
                        'Pengajuan ini sudah diproses sebelumnya.'
                    );
                }

                $verification->update([
                    'status'      => 'rejected',
                    'notes'       => $note,
                    'verifier_id' => Auth::id(),
                    'verified_at' => now(),
                ]);

                Notification::create([
                    'user_id'     => $verification->user_id,
                    'name'        => '❌ Verifikasi Ditolak',
                    'description' =>
                        'Pendaftaran penjual Anda ditolak. Catatan: ' . $note,
                    'is_read'     => false,
                ]);
            });

            return redirect()
                ->route('verifikator.identitas')
                ->with(
                    'success',
                    'Pendaftaran berhasil ditolak.'
                );

        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Gagal menolak verifikasi: ' . $e->getMessage()
                );
        }
    }

    /**
     * ============================================================
     * 3. VERIFIKASI PRODUK & JASA
     * ============================================================
     */
    public function produk(Request $request)
    {
        $tab = $request->get('tab', 'pending');

        /*
        |--------------------------------------------------------------------------
        | History produk
        |--------------------------------------------------------------------------
        */

        $query = Product::select([
            'id_product',
            'seller_id',
            'user_id',
            'category_id',
            'title',
            'name',
            'status',
            'rejection_note',
            'created_at',
        ])
            ->with([
                'seller:id_user,name',
                'category:id_category,name',
            ]);

        if ($tab === 'history') {

            $query->whereIn(
                'status',
                [
                    'approved',
                    'rejected',
                    'active',
                    'inactive',
                ]
            );

        } else {

            $query->where(
                'status',
                'pending'
            );
        }

        $products = $query
            ->latest('id_product')
            ->paginate(10)
            ->withQueryString();

        return view(
            'verifikator.produk',
            compact('products', 'tab')
        );
    }

    /**
     * ============================================================
     * DETAIL PRODUK
     * ============================================================
     */
    public function showProduk($id)
    {
        $product = Product::with([
            'seller',
            'category',
        ])->findOrFail($id);

        return view(
            'verifikator.detail-produk',
            compact('product')
        );
    }

    /**
     * ============================================================
     * APPROVE PRODUK
     * ============================================================
     */
    public function approveProduk($id)
    {
        if (!$this->isAdmin()) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Akses ditolak! Hanya Admin/Verifikator yang berhak menyetujui produk.'
                );
        }

        try {

            DB::transaction(function () use ($id) {

                $product = Product::lockForUpdate()
                    ->findOrFail($id);

                if ($product->status !== 'pending') {
                    throw new \RuntimeException(
                        'Produk ini sudah diproses sebelumnya.'
                    );
                }

                $product->update([
                    'status' => 'active',
                    'rejection_note' => null,
                ]);

                Notification::create([
                    'user_id' =>
                        $product->seller_id
                        ?? $product->user_id,

                    'name' =>
                        '✅ Produk Disetujui',

                    'description' =>
                        'Produk "' .
                        ($product->title ?? $product->name) .
                        '" Anda telah diverifikasi dan diterbitkan.',

                    'is_read' => false,
                ]);
            });

            return redirect()
                ->route('verifikator.produk')
                ->with(
                    'success',
                    'Produk berhasil disetujui.'
                );

        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Gagal menyetujui produk: ' . $e->getMessage()
                );
        }
    }

    /**
     * ============================================================
     * REJECT PRODUK
     * ============================================================
     */
    public function rejectProduk(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Akses ditolak! Hanya Admin/Verifikator yang berhak menolak produk.'
                );
        }

        $validated = $request->validate([
            'rejection_note' => 'required|string|max:500',
        ]);

        try {

            DB::transaction(function () use ($id, $validated) {

                $product = Product::lockForUpdate()
                    ->findOrFail($id);

                if ($product->status !== 'pending') {
                    throw new \RuntimeException(
                        'Produk ini sudah diproses sebelumnya.'
                    );
                }

                $product->update([
                    'status' =>
                        'rejected',

                    'rejection_note' =>
                        $validated['rejection_note'],
                ]);

                Notification::create([
                    'user_id' =>
                        $product->seller_id
                        ?? $product->user_id,

                    'name' =>
                        '❌ Produk Ditolak',

                    'description' =>
                        'Produk "' .
                        ($product->title ?? $product->name) .
                        '" ditolak. Catatan: ' .
                        $validated['rejection_note'],

                    'is_read' => false,
                ]);
            });

            return redirect()
                ->route('verifikator.produk')
                ->with(
                    'success',
                    'Produk berhasil ditolak.'
                );

        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Gagal menolak produk: ' . $e->getMessage()
                );
        }
    }

    /**
     * ============================================================
     * 4. VERIFIKASI PEMBAYARAN & MEMBERSHIP
     * ============================================================
     */
    public function pembayaran(Request $request)
    {
        $tab = $request->get(
            'tab',
            'pending'
        );

        $query = IdentityVerification::select([
            'id_identity_verification',
            'user_id',
            'membership_id',
            'verifier_id',
            'status',
            'payment_method',
            'notes',
            'submitted_at',
            'verified_at',
        ])
            ->with([
                'user:id_user,name,email',
                'membership:id_membership,name,price,duration_days',
                'verifier:id_user,name',
            ])
            ->whereNotNull(
                'payment_method'
            );

        if ($tab === 'history') {

            $payments = $query
                ->whereIn(
                    'status',
                    [
                        'approved',
                        'rejected',
                    ]
                )
                ->latest('id_identity_verification')
                ->paginate(10)
                ->withQueryString();

        } else {

            $payments = $query
                ->where(
                    'status',
                    'pending'
                )
                ->latest('id_identity_verification')
                ->paginate(10)
                ->withQueryString();
        }

        return view(
            'verifikator.pembayaran',
            compact(
                'payments',
                'tab'
            )
        );
    }

    /**
     * ============================================================
     * DETAIL PEMBAYARAN
     * ============================================================
     */
    public function showPembayaran($id)
    {
        $payment = IdentityVerification::with([
            'user',
            'membership',
            'verifier',
        ])->findOrFail($id);

        return view(
            'verifikator.detail-pembayaran',
            compact('payment')
        );
    }

    /**
     * ============================================================
     * APPROVE PEMBAYARAN
     * ============================================================
     */
    public function approvePembayaran($id)
    {
        if (!$this->isAdmin()) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Akses ditolak! Konfirmasi pembayaran hanya dapat dilakukan oleh Admin/Verifikator.'
                );
        }

        try {

            DB::transaction(function () use ($id) {

                $verification = IdentityVerification::lockForUpdate()
                    ->findOrFail($id);

                if ($verification->status !== 'pending') {
                    throw new \RuntimeException(
                        'Pembayaran ini sudah diproses sebelumnya.'
                    );
                }

                $sellerRole = Role::where(
                    'role_name',
                    'penjual'
                )->first();

                if (!$sellerRole) {
                    throw new \RuntimeException(
                        'Role penjual tidak ditemukan.'
                    );
                }

                $user = User::where(
                    'id_user',
                    $verification->user_id
                )
                    ->lockForUpdate()
                    ->first();

                if (!$user) {
                    throw new \RuntimeException(
                        'User pemohon tidak ditemukan.'
                    );
                }

                $userData = [
                    'id_role' => $sellerRole->id_role,
                    'status'  => 'active',
                ];

                if ($verification->membership_id) {

                    $membership = Membership::select([
                        'id_membership',
                        'duration_days',
                    ])->find(
                        $verification->membership_id
                    );

                    if ($membership) {

                        $userData['id_membership'] =
                            $membership->id_membership;

                        $userData['membership_expires_at'] =
                            now()->addDays(
                                $membership->duration_days ?? 30
                            );
                    }
                }

                $user->update($userData);

                $verification->update([
                    'status'      => 'approved',
                    'verifier_id' => Auth::id(),
                    'verified_at' => now(),
                ]);

                Notification::create([
                    'user_id' =>
                        $verification->user_id,

                    'name' =>
                        '💳 Pembayaran Dikonfirmasi',

                    'description' =>
                        'Pembayaran paket membership Anda berhasil dikonfirmasi lunas. Akun Anda telah aktif sebagai Penjual.',

                    'is_read' => false,
                ]);
            });

            return redirect()
                ->route('verifikator.pembayaran')
                ->with(
                    'success',
                    'Pembayaran berhasil dikonfirmasi lunas.'
                );

        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Gagal memproses pembayaran: ' . $e->getMessage()
                );
        }
    }

    /**
     * ============================================================
     * REJECT PEMBAYARAN
     * ============================================================
     */
    public function rejectPembayaran(
        Request $request,
        $id
    ) {
        if (!$this->isAdmin()) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Akses ditolak! Penolakan pembayaran hanya dapat dilakukan oleh Admin/Verifikator.'
                );
        }

        $validated = $request->validate([
            'rejection_note' =>
                'required|string|max:500',
        ]);

        try {

            DB::transaction(function () use (
                $id,
                $validated
            ) {

                $verification = IdentityVerification::lockForUpdate()
                    ->findOrFail($id);

                if ($verification->status !== 'pending') {
                    throw new \RuntimeException(
                        'Pembayaran ini sudah diproses sebelumnya.'
                    );
                }

                $verification->update([
                    'status' =>
                        'rejected',

                    'notes' =>
                        $validated['rejection_note'],

                    'verifier_id' =>
                        Auth::id(),

                    'verified_at' =>
                        now(),
                ]);

                Notification::create([
                    'user_id' =>
                        $verification->user_id,

                    'name' =>
                        '❌ Pembayaran Ditolak',

                    'description' =>
                        'Pembayaran paket membership Anda ditolak. Catatan: ' .
                        $validated['rejection_note'],

                    'is_read' => false,
                ]);
            });

            return redirect()
                ->route('verifikator.pembayaran')
                ->with(
                    'success',
                    'Pembayaran berhasil ditolak.'
                );

        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Gagal menolak pembayaran: ' . $e->getMessage()
                );
        }
    }

    /**
     * ============================================================
     * 5. LAPORAN PELANGGARAN
     * ============================================================
     */
    public function laporan(Request $request)
    {
        $tab = $request->get(
            'tab',
            'pending'
        );

        $query = Report::with([
            'reporter',
            'reportedUser',
            'product',
        ]);

        if ($tab === 'history') {

            $query->whereIn(
                'status',
                [
                    'resolved',
                    'dismissed',
                    'action_taken',
                ]
            );

        } else {

            $query->where(
                'status',
                'pending'
            );
        }

        $reports = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'verifikator.laporan',
            compact(
                'reports',
                'tab'
            )
        );
    }

    /**
     * ============================================================
     * DETAIL LAPORAN
     * ============================================================
     */
    public function showLaporan($id)
    {
        $report = Report::with([
            'reporter',
            'reportedUser',
            'product',
        ])->findOrFail($id);

        return view(
            'verifikator.detail-laporan',
            compact('report')
        );
    }

    /**
     * ============================================================
     * TINDAKAN LAPORAN
     * ============================================================
     */
    public function actionLaporan(
        Request $request,
        $id
    ) {
        if (!$this->isAdmin()) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Akses ditolak! Penanganan tindakan disiplin laporan hanya dapat dilakukan oleh Admin/Verifikator.'
                );
        }

        $validated = $request->validate([
            'action' =>
                'required|in:warning,takedown,dismiss',

            'note' =>
                'nullable|string|max:500',
        ]);

        try {

            DB::transaction(function () use (
                $id,
                $validated
            ) {

                $report = Report::lockForUpdate()
                    ->findOrFail($id);

                if ($report->status !== 'pending') {
                    throw new \RuntimeException(
                        'Laporan ini sudah diproses sebelumnya.'
                    );
                }

                $action = $validated['action'];
                $note   = $validated['note'] ?? null;

                /*
                |--------------------------------------------------------------------------
                | WARNING
                |--------------------------------------------------------------------------
                */

                if ($action === 'warning') {

                    $report->status = 'resolved';

                    if ($report->reported_user_id) {

                        Notification::create([
                            'user_id' =>
                                $report->reported_user_id,

                            'name' =>
                                '⚠️ Peringatan Pelanggaran',

                            'description' =>
                                'Akun Anda mendapatkan teguran terkait laporan pengaduan: ' .
                                ($note ?? 'Pelanggaran ketentuan platform.'),

                            'is_read' => false,
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | TAKEDOWN
                |--------------------------------------------------------------------------
                */

                elseif ($action === 'takedown') {

                    $report->status = 'resolved';

                    if ($report->product_id) {

                        Product::where(
                            'id_product',
                            $report->product_id
                        )->update([
                            'status' => 'inactive',
                        ]);
                    }

                    if ($report->reported_user_id) {

                        Notification::create([
                            'user_id' =>
                                $report->reported_user_id,

                            'name' =>
                                '⛔ Tindakan Disiplin (Takedown)',

                            'description' =>
                                'Produk/Konten Anda telah diturunkan karena terbukti melanggar aturan.',

                            'is_read' => false,
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | DISMISS
                |--------------------------------------------------------------------------
                */

                else {

                    $report->status = 'dismissed';
                }

                /*
                |--------------------------------------------------------------------------
                | Simpan tindakan
                |--------------------------------------------------------------------------
                */

                $report->action_taken = $action;
                $report->admin_note = $note;
                $report->save();
            });

            return redirect()
                ->route('verifikator.laporan')
                ->with(
                    'success',
                    'Tindakan laporan pelanggaran berhasil diproses.'
                );

        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Gagal memproses tindakan laporan: ' .
                    $e->getMessage()
                );
        }
    }

    /**
     * ============================================================
     * 6. PROFIL VERIFIKATOR
     * ============================================================
     */
    public function profile()
    {
        $user = Auth::user();

        return view(
            'verifikator.profile',
            compact('user')
        );
    }

    /**
     * ============================================================
     * UPDATE PROFIL VERIFIKATOR
     * ============================================================
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' =>
                'required|string|max:255',

            'email' =>
                'required|email|max:255|unique:users,email,' .
                $user->id_user .
                ',id_user',

            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(\+62|08)[0-9]{8,13}$/',
            ],

            'avatar' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'password' =>
                'nullable|string|min:6|confirmed',
        ], [
            'phone.regex' =>
                'No. telepon harus diawali 08 atau +62 dan minimal 10 digit.',
        ]);

        $data = [
            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'phone' =>
                $validated['phone'] ?? null,
        ];

        /*
        |--------------------------------------------------------------------------
        | Avatar
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('avatar')) {

            $data['avatar'] =
                $request
                    ->file('avatar')
                    ->store(
                        'avatars',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['password'])) {

            $data['password'] =
                Hash::make(
                    $validated['password']
                );
        }

        $user->update($data);

        return redirect()
            ->route('verifikator.profile')
            ->with(
                'success',
                'Profil berhasil diperbarui.'
            );
    }
}
