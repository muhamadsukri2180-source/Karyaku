<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tampilkan daftar notifikasi.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $notifications = Notification::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('description', 'like', "%{$search}%");
        })->latest()->paginate(10);

        $notifications->withQueryString();

        // Data Ringkasan Statistik
        $totalNotifications = Notification::count();
        $newThisMonth       = Notification::whereMonth('created_at', now()->month)
                                          ->whereYear('created_at', now()->year)
                                          ->count();

        return view('admin.sistem.notifikasi', compact(
            'notifications',
            'totalNotifications',
            'newThisMonth'
        ));
    }

    /**
     * Simpan notifikasi baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Notification::create($validated);

        return redirect()->back()->with('success', 'Notifikasi baru berhasil ditambahkan.');
    }

    /**
     * Perbarui data notifikasi.
     */
    public function update(Request $request, int|string $id)
    {
        $notification = Notification::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $notification->update($validated);

        return redirect()->back()->with('success', 'Notifikasi berhasil diperbarui.');
    }

    /**
     * Hapus data notifikasi.
     */
    public function destroy(int|string $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}