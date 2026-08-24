<?php

namespace App\Http\Controllers;

use App\Models\CustomerService;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CsController extends Controller
{
    /* ===================== 1. DASHBOARD CS ===================== */
    public function dashboard()
    {
        $totalLaporanMasuk = Report::where('status', 'pending')->count();
        $laporanSelesai    = Report::whereIn('status', ['reviewed', 'dismissed'])->count();
        $totalTiketPending = CustomerService::where('status', 'pending')->count();

        $recentReports = Report::with(['reporter', 'reportedUser', 'product'])
            ->latest()
            ->take(5)
            ->get();

        $recentTickets = CustomerService::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('cs.dashboard', compact(
            'totalLaporanMasuk', 
            'laporanSelesai', 
            'totalTiketPending',
            'recentReports',
            'recentTickets'
        ));
    }

    /* ===================== 2. TIKET PENGADUAN / BANTUAN USER ===================== */
    public function tiket(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $tickets = CustomerService::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($qq) => $qq->where('name', 'like', "%{$search}%"));
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('cs.tiket', compact('tickets'));
    }

    public function tiketDetail(string|int $id)
    {
        $ticket = CustomerService::with('user')->findOrFail($id);
        return response()->json($ticket);
    }

    public function balasTiket(Request $request, string|int $id)
    {
        $request->validate([
            'status'     => 'required|string|in:pending,in_progress,resolved,closed',
            'admin_note' => 'required|string|max:1000',
        ]);

        $ticket = CustomerService::findOrFail($id);
        $ticket->update([
            'status'     => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        // Kirim Notifikasi Balasan ke User
        $targetUserId = $ticket->user_id ?? $ticket->id_user ?? null;
        if ($targetUserId) {
            Notification::create([
                'user_id'     => $targetUserId,
                'name'        => 'Balasan Tiket Bantuan: ' . $ticket->subject,
                'description' => 'CS memberikan respon: ' . $request->admin_note,
                'is_read'     => false,
            ]);
        }

        return redirect()->back()->with('success', 'Tiket bantuan berhasil diperbarui dan notifikasi dikirim ke pengguna.');
    }

    /* ===================== 3. LAPORAN & MODERASI ===================== */
    public function laporan(Request $request)
    {
        $search = $request->query('search');

        $reportsUser = Report::with(['reporter', 'reportedUser'])
            ->whereNull('product_id')
            ->whereIn('status', ['pending', 'escalated'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('reporter', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('reportedUser', fn ($qq) => $qq->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10, ['*'], 'page_user')
            ->withQueryString();

        $reportsProduk = Report::with(['reporter', 'product.seller'])
            ->whereNotNull('product_id')
            ->whereIn('status', ['pending', 'escalated'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('reporter', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('product', fn ($qq) => $qq->where('title', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10, ['*'], 'page_produk')
            ->withQueryString();

        $riwayat = Report::with(['reporter', 'reportedUser', 'product'])
            ->whereIn('status', ['reviewed', 'dismissed'])
            ->latest()
            ->paginate(10, ['*'], 'page_riwayat')
            ->withQueryString();

        return view('cs.laporan', compact('reportsUser', 'reportsProduk', 'riwayat'));
    }

    public function tindakLaporan(Request $request, string|int $id)
    {
        $request->validate([
            'action'      => 'required|string|in:abaikan,teguran,sembunyikan,eskalasi',
            'admin_notes' => 'required|string|max:500',
        ]);

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

        if ($request->action === 'sembunyikan' && $report->product_id) {
            Product::where('id_product', $report->product_id)->update(['status' => 'inactive']);
        }

        // Send Notification to Reported User if Warning
        if ($request->action === 'teguran') {
            $targetUserId = $report->reported_user_id ?? ($report->product->user_id ?? $report->product->id_user ?? null);
            if ($targetUserId) {
                Notification::create([
                    'user_id'     => $targetUserId,
                    'name'        => '⚠️ Peringatan Laporan Pelanggaran',
                    'description' => 'Akun/Jasa Anda menerima peringatan dari CS: ' . $request->admin_notes,
                    'is_read'     => false,
                ]);
            }
        }

        // Send Notification back to Reporter
        $reporterId = $report->user_id ?? $report->id_user ?? null;
        if ($reporterId) {
            Notification::create([
                'user_id'     => $reporterId,
                'name'        => 'Status Laporan Anda',
                'description' => 'Laporan Anda telah ditindaklanjuti CS. Catatan: ' . $request->admin_notes,
                'is_read'     => false,
            ]);
        }

        $message = match ($request->action) {
            'abaikan'     => 'Laporan telah diabaikan.',
            'teguran'     => 'Peringatan berhasil dikirim ke pengguna.',
            'sembunyikan' => 'Jasa berhasil disembunyikan dari katalog.',
            'eskalasi'    => 'Laporan berhasil dieskalasi ke Admin.',
        };

        return redirect()->back()->with('success', $message);
    }

    /* ===================== 4. PANTAU TRANSAKSI (READ-ONLY) ===================== */
    public function transaksi(Request $request)
    {
        $search = $request->query('search');

        $orders = Order::with(['buyer', 'items.product.seller'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('buyer', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))
                  ->orWhere('id_order', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('cs.transaksi', compact('orders'));
    }

    public function transaksiDetail(string|int $id)
    {
        $order = Order::with(['buyer', 'items.product.seller'])->findOrFail($id);

        return response()->json($order);
    }

    /* ===================== 5. NOTIFIKASI CS ===================== */
    public function notifikasi()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orWhereNull('user_id')
            ->latest()
            ->paginate(10);

        return view('cs.notifikasi', compact('notifications'));
    }
}