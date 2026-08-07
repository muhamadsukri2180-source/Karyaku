<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tampilkan daftar notifikasi.
     */
    public function index()
    {
        $notifications = Notification::latest()->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
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

        return redirect()->back()->with('success', 'Notifikasi berhasil ditambahkan.');
    }

    /**
     * Perbarui data notifikasi.
     */
    public function update(Request $request, $id)
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
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
