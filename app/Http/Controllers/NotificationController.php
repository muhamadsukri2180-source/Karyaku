<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $notifications = Notification::when($search, fn ($query) => 
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalNotifications = Notification::count();
        $newThisMonth = Notification::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        return view('admin.sistem.notifikasi', compact(
            'notifications',
            'totalNotifications',
            'newThisMonth'
        ));
    }

    public function store(Request $request)
    {
        Notification::create($request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
        ]));
        return redirect()->back()->with('success', 'Notifikasi baru berhasil ditambahkan.');
    }

    public function update(Request $request, int|string $id)
    {
        Notification::findOrFail($id)->update($request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
        ]));
        return redirect()->back()->with('success', 'Notifikasi berhasil diperbarui.');
    }

    public function destroy(int|string $id)
    {
        Notification::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}