<?php

namespace App\Http\Controllers;

use App\Models\AccountAppeal;
use App\Models\CustomerService;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CsController extends Controller
{
    public function dashboard()
    {
    /*
    |--------------------------------------------------------------------------
    | ID USER YANG LOGIN
    |--------------------------------------------------------------------------
    */

    $userId = Auth::id();


    /*
    |--------------------------------------------------------------------------
    | STATISTIK LAPORAN
    |--------------------------------------------------------------------------
    */

    $totalLaporanMasuk = Report::where(
        'status',
        'pending'
    )->count();


    $laporanSelesai = Report::whereIn(
        'status',
        [
            'reviewed',
            'dismissed',
        ]
    )->count();


    /*
    |--------------------------------------------------------------------------
    | STATISTIK CUSTOMER SERVICE
    |--------------------------------------------------------------------------
    */

    $totalTiketPending = CustomerService::where(
        'status',
        'pending'
    )->count();


    /*
    |--------------------------------------------------------------------------
    | LAPORAN TERBARU
    |--------------------------------------------------------------------------
    |
    | PENTING:
    | Jangan menggunakan kolom "id" karena tabel reports
    | tidak memiliki kolom tersebut.
    |
    */

    $recentReports = Report::select([
            'user_id',
            'reported_user_id',
            'product_id',
            'reason',
            'status',
            'created_at',
        ])
        ->with([
            'reporter:id_user,name',
            'reportedUser:id_user,name',
            'product:id_product,title',
        ])
        ->latest('created_at')
        ->limit(5)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | TIKET CUSTOMER SERVICE TERBARU
    |--------------------------------------------------------------------------
    */

    $recentTickets = CustomerService::select([
            'id',
            'user_id',
            'subject',
            'status',
            'created_at',
        ])
        ->with([
            'user:id_user,name,email',
        ])
        ->latest('created_at')
        ->limit(5)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN DASHBOARD
    |--------------------------------------------------------------------------
    */

    return view(
        'cs.dashboard',
        compact(
            'totalLaporanMasuk',
            'laporanSelesai',
            'totalTiketPending',
            'recentReports',
            'recentTickets'
        )
    );
    }

    /* ===================== 2. TIKET PENGADUAN / BANTUAN USER ===================== */
    public function tiket(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $tickets = CustomerService::select('id', 'user_id', 'subject', 'status', 'created_at')
            ->with('user:id_user,name,email')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('subject', 'like', "%{$search}%")
                          ->orWhereHas('user', fn ($qq) => $qq->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('cs.tiket', compact('tickets'));
    }

    public function tiketDetail(string|int $id)
    {
        $ticket = CustomerService::with('user:id_user,name,email,avatar')->findOrFail($id);
        return response()->json($ticket);
    }

    public function balasTiket(Request $request, string|int $id)
    {
        $request->validate([
            'status'     => 'required|string|in:pending,in_progress,resolved,closed',
            'admin_note' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $id) {
            $ticket = CustomerService::findOrFail($id);
            $ticket->update([
                'status'     => $request->status,
                'admin_note' => $request->admin_note,
            ]);

            $targetUserId = $ticket->user_id ?? $ticket->id_user ?? null;
            if ($targetUserId) {
                Notification::create([
                    'user_id'     => $targetUserId,
                    'name'        => 'Balasan Tiket Bantuan: ' . $ticket->subject,
                    'description' => 'CS memberikan respon: ' . $request->admin_note,
                    'is_read'     => false,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Tiket bantuan berhasil diperbarui dan notifikasi dikirim ke pengguna.');
    }

    /* ===================== 3. LAPORAN & MODERASI ===================== */
    public function laporan(Request $request)
    {
        $search = $request->query('search');

        // 1. Laporan Pengguna
        $reportsUser = Report::select('id', 'user_id', 'reported_user_id', 'reason', 'status', 'created_at')
            ->with([
                'reporter:id_user,name',
                'reportedUser:id_user,name'
            ])
            ->whereNull('product_id')
            ->whereIn('status', ['pending', 'escalated'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->whereHas('reporter', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))
                          ->orWhereHas('reportedUser', fn ($qq) => $qq->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'page_user')
            ->withQueryString();

        // 2. Laporan Produk
        $reportsProduk = Report::select('id', 'user_id', 'product_id', 'reason', 'status', 'created_at')
            ->with([
                'reporter:id_user,name',
                'product:id_product,seller_id,title',
                'product.seller:id_user,name'
            ])
            ->whereNotNull('product_id')
            ->whereIn('status', ['pending', 'escalated'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->whereHas('reporter', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))
                          ->orWhereHas('product', fn ($qq) => $qq->where('title', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'page_produk')
            ->withQueryString();

        // 3. Permohonan Banding
        $reportsAppeal = AccountAppeal::select('id', 'user_id', 'reason', 'status', 'created_at', 'reviewed_by')
            ->with([
                'user:id_user,name,id_role',
                'user.role:id_role,name',
                'reviewer:id_user,name'
            ])
            ->latest()
            ->paginate(10, ['*'], 'page_banding')
            ->withQueryString();

        $pendingAppealCount = AccountAppeal::where('status', 'pending')->count();

        // 4. Riwayat Moderasi
        $riwayat = Report::select('id', 'user_id', 'reported_user_id', 'product_id', 'status', 'admin_note', 'updated_at')
            ->with([
                'reporter:id_user,name',
                'reportedUser:id_user,name',
                'product:id_product,title'
            ])
            ->whereIn('status', ['reviewed', 'dismissed'])
            ->latest()
            ->paginate(10, ['*'], 'page_riwayat')
            ->withQueryString();

        return view('cs.laporan', compact('reportsUser', 'reportsProduk', 'reportsAppeal', 'pendingAppealCount', 'riwayat'));
    }

    public function tindakLaporan(Request $request, string|int $id)
    {
        $request->validate([
            'action'      => 'required|string|in:abaikan,peringatan,teguran,suspend,sembunyikan,eskalasi',
            'admin_notes' => 'required|string|max:500',
        ]);

        $message = DB::transaction(function () use ($request, $id) {
            $report = Report::findOrFail($id);

            $status = match ($request->action) {
                'abaikan'  => 'dismissed',
                'eskalasi' => 'escalated',
                default    => 'reviewed',
            };

            $report->update([
                'status'      => $status,
                'admin_note'  => $request->admin_notes,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            // Suspend Akun jika diperlukan
            if ($request->action === 'suspend' && $report->reported_user_id) {
                User::where('id_user', $report->reported_user_id)->update(['status' => 'blocked']);
            }

            // Sembunyikan Jasa / Produk
            if (in_array($request->action, ['sembunyikan', 'suspend']) && $report->product_id) {
                Product::where('id_product', $report->product_id)->update(['status' => 'inactive']);
            }

            // Notifikasi Peringatan ke Pengguna yang dilaporkan
            if (in_array($request->action, ['peringatan', 'teguran'])) {
                $targetUserId = $report->reported_user_id ?? ($report->product->seller_id ?? null);
                if ($targetUserId) {
                    Notification::create([
                        'user_id'     => $targetUserId,
                        'name'        => '⚠️ Peringatan Laporan Pelanggaran',
                        'description' => 'Akun/Jasa Anda menerima peringatan dari CS: ' . $request->admin_notes,
                        'is_read'     => false,
                    ]);
                }
            }

            // Notifikasi ke Pelapor
            $reporterId = $report->user_id ?? $report->id_user ?? null;
            if ($reporterId) {
                Notification::create([
                    'user_id'     => $reporterId,
                    'name'        => 'Status Laporan Anda',
                    'description' => 'Laporan Anda telah ditindaklanjuti CS. Catatan: ' . $request->admin_notes,
                    'is_read'     => false,
                ]);
            }

            return match ($request->action) {
                'abaikan'               => 'Laporan telah diabaikan.',
                'peringatan', 'teguran' => 'Peringatan berhasil dikirim ke pengguna.',
                'sembunyikan', 'suspend'=> 'Tindakan penangguhan / penyembunyian berhasil diproses.',
                'eskalasi'              => 'Laporan berhasil dieskalasi ke Admin.',
            };
        });

        return redirect()->back()->with('success', $message);
    }

    public function tindakUserLaporan(Request $request, string|int $id)
    {
        return $this->tindakLaporan($request, $id);
    }

    public function tindakProdukLaporan(Request $request, string|int $id)
    {
        return $this->tindakLaporan($request, $id);
    }

    public function tindakAppeal(Request $request, string|int $id)
    {
        $request->validate([
            'action'      => 'required|string|in:setujui,tolak',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $result = DB::transaction(function () use ($request, $id) {
            $appeal = AccountAppeal::with('user:id_user,name')->findOrFail($id);
            $user = $appeal->user;

            if ($request->action === 'setujui') {
                $appeal->update([
                    'status'      => 'approved',
                    'admin_note'  => $request->admin_notes ?: 'Banding disetujui. Akun telah diaktifkan kembali.',
                    'reviewed_at' => now(),
                    'reviewed_by' => Auth::id(),
                ]);

                if ($user) {
                    $user->update([
                        'status'          => 'active',
                        'suspended_until' => null,
                        'suspend_reason'  => null,
                    ]);

                    Notification::create([
                        'user_id'     => $user->id_user,
                        'name'        => '🎉 Banding Disetujui & Akun Aktif',
                        'description' => 'Pengajuan banding Anda telah disetujui oleh CS. Akun Anda telah diaktifkan kembali. ' . ($request->admin_notes ? 'Catatan CS: ' . $request->admin_notes : ''),
                        'is_read'     => false,
                    ]);
                }

                return 'Banding disetujui dan akun pengguna "' . ($user->name ?? 'User') . '" berhasil diaktifkan kembali.';
            } else {
                $appeal->update([
                    'status'      => 'rejected',
                    'admin_note'  => $request->admin_notes ?: 'Banding ditolak oleh CS.',
                    'reviewed_at' => now(),
                    'reviewed_by' => Auth::id(),
                ]);

                if ($user) {
                    Notification::create([
                        'user_id'     => $user->id_user,
                        'name'        => '❌ Pengajuan Banding Ditolak',
                        'description' => 'Pengajuan banding akun Anda ditolak oleh CS. Catatan CS: ' . ($request->admin_notes ?: 'Alasan pembelaan atau bukti tidak mencukupi.'),
                        'is_read'     => false,
                    ]);
                }

                return 'Pengajuan banding telah ditolak.';
            }
        });

        return redirect()->back()->with('success', $result);
    }

    /* ===================== 4. PANTAU TRANSAKSI (READ-ONLY) ===================== */
    public function transaksi(Request $request)
    {
        $search = $request->query('search');

        $orders = Order::select('id_order', 'buyer_id', 'total_price', 'status', 'created_at')
            ->with([
                'buyer:id_user,name',
                'items:id,order_id,product_id',
                'items.product:id_product,seller_id,title',
                'items.product.seller:id_user,name'
            ])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('id_order', 'like', "%{$search}%")
                          ->orWhereHas('buyer', fn ($qq) => $qq->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('cs.transaksi', compact('orders'));
    }

    public function transaksiDetail(string|int $id)
    {
        $order = Order::with([
            'buyer:id_user,name,email,phone',
            'items:id,order_id,product_id,quantity,price',
            'items.product:id_product,seller_id,title,price',
            'items.product.seller:id_user,name'
        ])->findOrFail($id);

        return response()->json($order);
    }

    /* ===================== 5. NOTIFIKASI CS ===================== */
    public function notifikasi()
    {
        $notifications = Notification::select('id', 'user_id', 'name', 'description', 'is_read', 'created_at')
            ->where(function ($q) {
                $q->where('user_id', Auth::id())
                  ->orWhereNull('user_id');
            })
            ->latest()
            ->paginate(10);

        return view('cs.notifikasi', compact('notifications'));
    }
}
