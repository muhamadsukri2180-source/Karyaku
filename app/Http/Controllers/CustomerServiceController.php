<?php

namespace App\Http\Controllers;

use App\Models\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerServiceController extends Controller
{
    public function userIndex()
    {
        $userId = Auth::id();

        $rawStats = CustomerService::where('user_id', $userId)
            ->selectRaw("
                COUNT(CASE WHEN status = 'selesai' THEN 1 END) as selesai,
                COUNT(CASE WHEN status = 'proses' THEN 1 END) as proses,
                COUNT(CASE WHEN status = 'belum' THEN 1 END) as belum
            ")
            ->first();
        $stats = [
            'selesai' => $rawStats->selesai ?? 0,
            'proses'  => $rawStats->proses ?? 0,
            'belum'   => $rawStats->belum ?? 0,
        ];
        $tickets = CustomerService::select('id', 'user_id', 'subject', 'message', 'status', 'admin_note', 'created_at')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('admin.service.dashboard', compact('tickets', 'stats'));
    }
    public function userStore(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        DB::transaction(function () use ($request) {
            CustomerService::create([
                'user_id' => Auth::id(),
                'subject' => $request->subject,
                'message' => $request->message,
                'status'  => 'belum',
            ]);
        });

        return redirect()
            ->route('pembeli.service.index')
            ->with('success', 'Keluhan atau masukan berhasil dikirim ke Customer Service!');
    }
}
