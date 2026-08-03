<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Membership;
use App\Models\IdentityVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // 1. Dashboard
    public function dashboard()
    {
        $totalProducts = Product::count();
        $monthlySales = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_price');
        $pendingProductsCount = Product::where('status', 'pending')->count();

        $chartData = Order::select(
                DB::raw('MONTHNAME(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('payment_status', 'paid')
            ->groupBy('month')
            ->take(6)
            ->get();

        $isMaintenance = app()->isDownForMaintenance();

        return view('admin.dashboard', compact(
            'totalProducts', 'monthlySales', 'totalRevenue', 
            'pendingProductsCount', 'chartData', 'isMaintenance'
        ));
    }

    // 2. Fitur Maintenance Mode (ON / OFF)
    public function toggleMaintenance(Request $request)
    {
        if (app()->isDownForMaintenance()) {
            // Matikan Maintenance Mode
            Artisan::call('up');
            return redirect()->back()->with('success', 'Sistem kembali Online (Maintenance Mode Nonaktif).');
        } else {
            // Aktifkan Maintenance Mode dengan Secret Key agar Admin tetap bisa akses
            $secretKey = $request->input('secret_key', 'admin-access');
            Artisan::call('down', [
                '--secret' => $secretKey,
                '--refresh' => 15,
            ]);
            return redirect()->back()->with('warning', 'Sistem masuk ke Maintenance Mode. Akses rahasia: /' . $secretKey);
        }
    }

    // 3. Manajemen Pengguna
    public function users()
    {
        $users = User::with('role')->get();
        $sellersToApprove = IdentityVerification::with('user')
            ->where('status', 'pending')
            ->get();

        return view('admin.users.index', compact('users', 'sellersToApprove'));
    }

    public function addVerifier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => 3, // ID Role untuk Verifikator
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Verifikator berhasil ditambahkan.');
    }

    public function approveSeller(Request $request, $id)
    {
        $verification = IdentityVerification::findOrFail($id);
        $verification->update([
            'status' => 'approved', 
            'verifier_id' => auth()->id(),
            'verified_at' => now()
        ]);

        $user = User::findOrFail($verification->user_id);
        $user->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Pengajuan penjual disetujui.');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }

    // 4. Katalog & Kategori
    public function products()
    {
        $products = Product::with(['category', 'seller'])->get();
        $categories = Category::all();
        return view('admin.catalog.index', compact('products', 'categories'));
    }

    public function approveProduct($id)
    {
        Product::where('id_product', $id)->update(['status' => 'active']);
        return redirect()->back()->with('success', 'Produk berhasil disetujui.');
    }

    public function takedownProduct($id)
    {
        Product::where('id_product', $id)->update(['status' => 'inactive']);
        return redirect()->back()->with('success', 'Produk berhasil di-takedown.');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', 
            'description' => 'nullable|string'
        ]);
        Category::create($request->only('name', 'description'));

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function deleteCategory($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }

    // 5. Transaksi & Keuangan
    public function transactions()
    {
        $orders = Order::with('buyer')->latest()->get();
        $totalCommission = $orders->where('payment_status', 'paid')->sum('total_price') * 0.05; // Komisi 5%

        return view('admin.transactions.index', compact('orders', 'totalCommission'));
    }

    // 6. Membership
    public function memberships()
    {
        $memberships = Membership::all();
        return view('admin.memberships.index', compact('memberships'));
    }

    public function updateMembership(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'max_upload' => 'required|integer',
            'benefit' => 'required|string',
        ]);

        $membership = Membership::findOrFail($id);
        $membership->update($request->all());

        return redirect()->back()->with('success', 'Kartu membership berhasil diperbarui.');
    }

    // 7. Profile Admin
    public function profile()
    {
        $admin = auth()->user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = User::findOrFail(auth()->id());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id_user . ',id_user',
            'phone' => 'nullable|string',
            'password' => 'nullable|min:8'
        ]);

        $data = $request->only('name', 'email', 'phone');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);
        return redirect()->back()->with('success', 'Profil Admin berhasil diperbarui.');
    }
}