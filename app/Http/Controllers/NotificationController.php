<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $notifications = Notification::where(function ($q) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', Auth::id());
            })
            ->when($search, fn ($query) => 
                $query->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('description', 'like', "%{$search}%");
                })
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalNotifications = Notification::where(function ($q) {
            $q->whereNull('user_id')
              ->orWhere('user_id', Auth::id());
        })->count();

        $newThisMonth = Notification::where(function ($q) {
            $q->whereNull('user_id')
              ->orWhere('user_id', Auth::id());
        })
        ->whereMonth('created_at', now()->month)
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
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'user_id'     => 'nullable|exists:users,id_user',
        ]);

        Notification::create($validated);
        return redirect()->back()->with('success', 'Notifikasi baru berhasil ditambahkan.');
    }

    public function update(Request $request, int|string $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'user_id'     => 'nullable|exists:users,id_user',
        ]);

        Notification::findOrFail($id)->update($validated);
        return redirect()->back()->with('success', 'Notifikasi berhasil diperbarui.');
    }

    public function destroy(int|string $id)
    {
        Notification::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}