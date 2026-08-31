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
        $totalLaporanMasuk = Report::where('status', 'pending')->count();
        $laporanSelesai = Report::whereIn('status', ['reviewed', 'dismissed'])->count();
        $totalTiketPending = CustomerService::where('status', 'pending')->count();

        $recentReports = Report::select(['user_id', 'reported_user_id', 'product_id', 'reason', 'status', 'created_at'])
            ->with([
                'reporter:id_user,name',
                'reportedUser:id_user,name',
                'product:id_product,title',
            ])
            ->latest('created_at')->limit(5)->get();

        $recentTickets = CustomerService::select(['id', 'user_id', 'subject', 'status', 'created_at'])
            ->with(['user:id_user,name,email'])
            ->latest('created_at')->limit(5)->get();

        return view('cs.dashboard', compact(
            'totalLaporanMasuk',
            'laporanSelesai',
            'totalTiketPending',
            'recentReports',
            'recentTickets'
        ));
    }

    public function tiket(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $tickets = CustomerService::select('id', 'user_id', 'subject', 'status', 'created_at')
            ->with('user:id_user,name,email')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where(fn ($query) => $query->where('subject', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($qq) => $qq->where('name', 'like', "%{$search}%")));
            })
            ->latest()->paginate(10)->withQueryString();

        return view('cs.tiket', compact('tickets'));
    }

    public function tiketDetail(string|int $id)
    {
        return response()->json(CustomerService::with('user:id_user,name,email,avatar')->findOrFail($id));
    }

    public function balasTiket(Request $request, string|int $id)
    {
        $request->validate([
            'status'     => 'required|string|in:pending,in_progress,resolved,closed',
            'admin_note' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $id) {
            $ticket = CustomerService::findOrFail($id);
            $ticket->update($request->only(['status', 'admin_note']));

            if ($targetUserId = $ticket->user_id ?? $ticket->id_user ?? null) {
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

    public function laporan(Request $request)
    {
        $search = $request->query('search');

        $reportsUser = Report::select('id', 'user_id', 'reported_user_id', 'reason', 'status', 'created_at')
            ->with(['reporter:id_user,name', 'reportedUser:id_user,name'])
            ->whereNull('product_id')->whereIn('status', ['pending', 'escalated'])
            ->when($search, fn ($q) => $q->where(fn ($query) => $query->whereHas('reporter', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))
                ->orWhereHas('reportedUser', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))))
            ->latest()->paginate(10, ['*'], 'page_user')->withQueryString();

        $reportsProduk = Report::select('id', 'user_id', 'product_id', 'reason', 'status', 'created_at')
            ->with(['reporter:id_user,name', 'product:id_product,seller_id,title', 'product.seller:id_user,name'])
            ->whereNotNull('product_id')->whereIn('status', ['pending', 'escalated'])
            ->when($search, fn ($q) => $q->where(fn ($query) => $query->whereHas('reporter', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))
                ->orWhereHas('product', fn ($qq) => $qq->where('title', 'like', "%{$search}%"))))
            ->latest()->paginate(10, ['*'], 'page_produk')->withQueryString();

        $reportsAppeal = AccountAppeal::select('id', 'user_id', 'reason', 'status', 'created_at', 'reviewed_by')
            ->with(['user:id_user,name,id_role', 'user.role:id_role,name', 'reviewer:id_user,name'])
            ->latest()->paginate(10, ['*'], 'page_banding')->withQueryString();

        $pendingAppealCount = AccountAppeal::where('status', 'pending')->count();

        $riwayat = Report::select('id', 'user_id', 'reported_user_id', 'product_id', 'status', 'admin_note', 'updated_at')
            ->with(['reporter:id_user,name', 'reportedUser:id_user,name', 'product:id_product,title'])
            ->whereIn('status', ['reviewed', 'dismissed'])
            ->latest()->paginate(10, ['*'], 'page_riwayat')->withQueryString();

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

            if ($request->action === 'suspend' && $report->reported_user_id) {
                User::where('id_user', $report->reported_user_id)->update(['status' => 'blocked']);
            }

            if (in_array($request->action, ['sembunyikan', 'suspend']) && $report->product_id) {
                Product::where('id_product', $report->product_id)->update(['status' => 'inactive']);
            }

            if (in_array($request->action, ['peringatan', 'teguran']) && ($targetUserId = $report->reported_user_id ?? ($report->product->seller_id ?? null))) {
                Notification::create([
                    'user_id'     => $targetUserId,
                    'name'        => '⚠️ Peringatan Laporan Pelanggaran',
                    'description' => 'Akun/Jasa Anda menerima peringatan dari CS: ' . $request->admin_notes,
                    'is_read'     => false,
                ]);
            }

            if ($reporterId = $report->user_id ?? $report->id_user ?? null) {
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
            $isApproved = $request->action === 'setujui';

            $appeal->update([
                'status'      => $isApproved ? 'approved' : 'rejected',
                'admin_note'  => $request->admin_notes ?: ($isApproved ? 'Banding disetujui. Akun telah diaktifkan kembali.' : 'Banding ditolak oleh CS.'),
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ]);

            if ($user) {
                if ($isApproved) {
                    $user->update([
                        'status'          => 'active',
                        'suspended_until' => null,
                        'suspend_reason'  => null,
                    ]);
                }

                Notification::create([
                    'user_id'     => $user->id_user,
                    'name'        => $isApproved ? '🎉 Banding Disetujui & Akun Aktif' : '❌ Pengajuan Banding Ditolak',
                    'description' => $isApproved 
                        ? 'Pengajuan banding Anda telah disetujui oleh CS. Akun Anda telah diaktifkan kembali. ' . ($request->admin_notes ? 'Catatan CS: ' . $request->admin_notes : '')
                        : 'Pengajuan banding akun Anda ditolak oleh CS. Catatan CS: ' . ($request->admin_notes ?: 'Alasan pembelaan atau bukti tidak mencukupi.'),
                    'is_read'     => false,
                ]);
            }

            return $isApproved 
                ? 'Banding disetujui dan akun pengguna "' . ($user->name ?? 'User') . '" berhasil diaktifkan kembali.' 
                : 'Pengajuan banding telah ditolak.';
        });

        return redirect()->back()->with('success', $result);
    }

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
            ->when($search, fn ($q) => $q->where(fn ($query) => $query->where('id_order', 'like', "%{$search}%")
                ->orWhereHas('buyer', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))))
            ->latest()->paginate(15)->withQueryString();

        return view('cs.transaksi', compact('orders'));
    }

    public function transaksiDetail(string|int $id)
    {
        return response()->json(Order::with([
            'buyer:id_user,name,email,phone',
            'items:id,order_id,product_id,quantity,price',
            'items.product:id_product,seller_id,title,price',
            'items.product.seller:id_user,name'
        ])->findOrFail($id));
    }

    public function notifikasi()
    {
        $notifications = Notification::select('id', 'user_id', 'name', 'description', 'is_read', 'created_at')
            ->where(fn ($q) => $q->where('user_id', Auth::id())->orWhereNull('user_id'))
            ->latest()->paginate(10);

        return view('cs.notifikasi', compact('notifications'));
    }
}