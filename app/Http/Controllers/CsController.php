<?php

namespace App\Http\Controllers;

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

        $recentReports = Report::with(['reporter', 'reportedUser', 'product'])
            ->latest()
            ->take(5)
            ->get();

        return view('cs.dashboard', compact(
            'totalLaporanMasuk', 
            'laporanSelesai', 
            'recentReports'
        ));
    }

    /* ===================== 2. LAPORAN & MODERASI ===================== */
    public function laporan()
    {
        $reportsUser = Report::with(['reporter', 'reportedUser'])
            ->whereNull('product_id')
            ->whereIn('status', ['pending', 'escalated'])
            ->latest()
            ->paginate(10, ['*'], 'page_user');

        $reportsProduk = Report::with(['reporter', 'product.seller'])
            ->whereNotNull('product_id')
            ->whereIn('status', ['pending', 'escalated'])
            ->latest()
            ->paginate(10, ['*'], 'page_produk');

        $riwayat = Report::with(['reporter', 'reportedUser', 'product'])
            ->whereIn('status', ['reviewed', 'dismissed'])
            ->latest()
            ->paginate(10, ['*'], 'page_riwayat');

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

        $message = match ($request->action) {
            'abaikan'     => 'Laporan telah diabaikan.',
            'teguran'     => 'Peringatan berhasil dikirim ke pengguna.',
            'sembunyikan' => 'Jasa berhasil disembunyikan dari katalog.',
            'eskalasi'    => 'Laporan berhasil dieskalasi ke Admin.',
        };

        return redirect()->back()->with('success', $message);
    }

    /* ===================== 3. PANTAU TRANSAKSI (READ-ONLY) ===================== */
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

    /* ===================== 4. NOTIFIKASI CS ===================== */
    public function notifikasi()
    {
        $notifications = Notification::latest()->paginate(10);

        return view('cs.notifikasi', compact('notifications'));
    }
}
