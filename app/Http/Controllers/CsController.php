<?php

namespace App\Http\Controllers;

use App\Models\AccountAppeal;
use App\Models\Notification;
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

        $recentReports = Report::select(['id_report', 'user_id', 'reported_user_id', 'product_id', 'reason', 'status', 'created_at'])
            ->with([
                'reporter:id_user,name',
                'reportedUser:id_user,name',
                'product:id_product,title',
            ])
            ->latest('created_at')->limit(5)->get();

        return view('cs.dashboard', compact(
            'totalLaporanMasuk',
            'laporanSelesai',
            'recentReports'
        ));
    }


    public function laporan(Request $request)
    {
        $search = $request->query('search');

        $reportsUser = Report::select('id_report', 'id_report as id', 'user_id', 'reported_user_id', 'reason', 'description', 'status', 'created_at')
            ->with(['reporter:id_user,name', 'reportedUser:id_user,name'])
            ->whereNull('product_id')
            ->whereIn('status', ['pending', 'escalated'])
            ->when($search, fn ($q) => $q->where(fn ($query) => $query
                ->whereHas('reporter', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))
                ->orWhereHas('reportedUser', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))
                ->orWhere('reason', 'like', "%{$search}%")
            ))
            ->latest('id_report')->paginate(10, ['*'], 'page_user')->withQueryString();

        $reportsProduk = Report::select('id_report', 'id_report as id', 'user_id', 'product_id', 'reason', 'description', 'status', 'created_at')
            ->with(['reporter:id_user,name', 'product:id_product,seller_id,title', 'product.seller:id_user,name'])
            ->whereNotNull('product_id')
            ->whereIn('status', ['pending', 'escalated'])
            ->when($search, fn ($q) => $q->where(fn ($query) => $query
                ->whereHas('reporter', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))
                ->orWhereHas('product', fn ($qq) => $qq->where('title', 'like', "%{$search}%"))
                ->orWhere('reason', 'like', "%{$search}%")
            ))
            ->latest('id_report')->paginate(10, ['*'], 'page_produk')->withQueryString();

        $reportsAppeal = AccountAppeal::select('id_appeal', 'id_appeal as id', 'user_id', 'reason', 'proof_image', 'status', 'created_at', 'reviewed_by', 'admin_note')
            ->with(['user:id_user,name,email,id_role,suspend_reason', 'user.role:id_role,role_name', 'reviewer:id_user,name'])
            ->latest('id_appeal')->paginate(10, ['*'], 'page_banding')->withQueryString();

        $pendingAppealCount = AccountAppeal::where('status', 'pending')->count();

        $riwayat = Report::select('id_report', 'id_report as id', 'user_id', 'reported_user_id', 'product_id', 'reason', 'status', 'admin_note', 'reviewed_at', 'updated_at')
            ->with(['reporter:id_user,name', 'reportedUser:id_user,name', 'product:id_product,title'])
            ->whereIn('status', ['reviewed', 'dismissed'])
            ->latest('id_report')->paginate(10, ['*'], 'page_riwayat')->withQueryString();

        return view('cs.laporan', compact('reportsUser', 'reportsProduk', 'reportsAppeal', 'pendingAppealCount', 'riwayat'));
    }

    public function tindakLaporan(Request $request, string|int $id)
    {
        $request->validate([
            'action'      => 'required|string|in:abaikan,peringatan,teguran,suspend,sembunyikan,eskalasi',
            'admin_notes' => 'required|string|max:500',
        ]);

        $message = DB::transaction(function () use ($request, $id) {
            $report = Report::with('product')->findOrFail($id);
            $targetUserId = $report->reported_user_id ?? ($report->product->seller_id ?? null);

            $status = match ($request->action) {
                'abaikan'  => 'dismissed',
                'eskalasi' => 'escalated',
                default    => 'reviewed',
            };

            $report->update([
                'reported_user_id' => $targetUserId,
                'status'           => $status,
                'admin_note'       => $request->admin_notes,
                'reviewed_by'      => Auth::id(),
                'reviewed_at'      => now(),
            ]);

            if ($request->action === 'suspend' && $targetUserId) {
                User::where('id_user', $targetUserId)->update(['status' => 'blocked']);
            }

            if (in_array($request->action, ['sembunyikan', 'suspend']) && $report->product_id) {
                Product::where('id_product', $report->product_id)->update(['status' => 'inactive']);
            }

            if (in_array($request->action, ['peringatan', 'teguran']) && $targetUserId) {
                Notification::create([
                    'user_id'     => $targetUserId,
                    'name'        => 'Peringatan Laporan Pelanggaran',
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
                    'name'        => $isApproved ? 'Banding Disetujui & Akun Aktif' : 'Pengajuan Banding Ditolak',
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

    public function notifikasi()
    {
        $userId = Auth::id();

        $notifications = Notification::where(function ($q) use ($userId) {
                $q->whereNull('user_id')
                ->orWhere('user_id', $userId);
            })
            ->latest()
            ->paginate(10);

        $unreadCount = Notification::where(function ($q) use ($userId) {
                $q->whereNull('user_id')
                ->orWhere('user_id', $userId);
            })
            ->where('is_read', false)
            ->count();

        return view('cs.notifikasi', compact('notifications', 'unreadCount'));
    }

}
