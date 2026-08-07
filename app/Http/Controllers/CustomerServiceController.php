<?php

namespace App\Http\Controllers;

use App\Models\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerServiceController extends Controller
{
    // Tampilkan halaman Customer Service dan riwayat tiket di sisi User/Pembeli
    public function userIndex()
    {
        $tickets = CustomerService::where('user_id', Auth::id())->latest()->get();
        
        $stats = [
            'selesai' => $tickets->where('status', 'selesai')->count(),
            'proses'  => $tickets->where('status', 'proses')->count(),
            'belum'   => $tickets->where('status', 'belum')->count(),
        ];

        return view('admin.service.dashboard', compact('tickets', 'stats'));
    }

    // Proses simpan keluhan / masukan baru dari User/Pembeli
    public function userStore(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        CustomerService::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status'  => 'belum',
        ]);

        return redirect()->route('pembeli.service.index')->with('success', 'Keluhan atau masukan berhasil dikirim ke Customer Service!');
    }
}