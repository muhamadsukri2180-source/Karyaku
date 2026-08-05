<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Membership;
use App\Models\IdentityVerification;
use App\Models\Withdrawal;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;
use Carbon\Carbon;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. DASHBOARD  ->  GET /admin/dashboard  (admin.dashboard)
    | View: resources/views/admin/dashboard.blade.php
    |--------------------------------------------------------------------------
    */
    public function dashboard(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        $totalProducts        = Product::count();
        $pendingProductsCount = Product::where('status', 'pending')->count();

        $totalOrders  = Order::count();
        $monthlySales = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalRevenue       = (float) Order::where('payment_status', 'paid')->sum('total_price');
        $platformCommission = $totalRevenue * 0.05;

        $totalUsers = User::whereHas('role', fn ($q) => $q->whereIn('role_name', ['pembeli', 'penjual']))->count();

        $pendingIdentityCount = IdentityVerification::where('status', 'pending')->count();
        $pendingReportsCount  = Report::where('status', 'pending')->count();

        // Grafik: jumlah order per bulan pada tahun yang dipilih
        $chartRaw = Order::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartData[] = (int) ($chartRaw[$m] ?? 0);
        }

        // Top kategori berdasarkan jumlah item yang benar-benar terjual (order_items)
        $topCategories = Category::all()->map(function ($cat) {
            $cat->order_count = OrderItem::whereHas('product', fn ($q) => $q->where('category_id', $cat->id_category))->count();
            return $cat;
        })->sortByDesc('order_count')->take(4)->values();

        $totalCategoryOrders = max(1, $topCategories->sum('order_count'));
        $topCategories = $topCategories->map(function ($cat) use ($totalCategoryOrders) {
            $cat->percentage = round(($cat->order_count / $totalCategoryOrders) * 100);
            return $cat;
        });

        // Aktivitas terkini gabungan
        $recentOrders = Order::with('buyer')->latest()->take(3)->get()->map(fn ($o) => [
            'title' => 'Order Baru #' . $o->kode_order,
            'desc'  => 'Pembeli "' . ($o->buyer->name ?? '-') . '" membuat pesanan baru.',
            'time'  => $o->created_at,
            'color' => 'emerald',
        ]);

        $recentProducts = Product::where('status', 'active')->latest('updated_at')->take(3)->get()->map(fn ($p) => [
            'title' => 'Produk Diverifikasi',
            'desc'  => 'Produk "' . $p->title . '" telah disetujui.',
            'time'  => $p->updated_at,
            'color' => 'sky',
        ]);

        $recentIdentities = IdentityVerification::with('user')->latest()->take(3)->get()->map(fn ($iv) => [
            'title' => 'Pengajuan Identitas',
            'desc'  => 'Kreator "' . ($iv->user->name ?? '-') . '" mengunggah identitas.',
            'time'  => $iv->created_at,
            'color' => 'amber',
        ]);

        $recentActivities = $recentOrders->concat($recentProducts)->concat($recentIdentities)
            ->sortByDesc('time')->take(5)->values();

        $isMaintenance = app()->isDownForMaintenance();

        return view('admin.dashboard', compact(
            'totalProducts',
            'pendingProductsCount',
            'totalOrders',
            'monthlySales',
            'totalRevenue',
            'platformCommission',
            'totalUsers',
            'pendingIdentityCount',
            'pendingReportsCount',
            'chartData',
            'topCategories',
            'recentActivities',
            'isMaintenance',
            'year'
        ));
    }

    // GET /admin/dashboard/chart-data  (admin.dashboard.chartData) - AJAX ganti tahun
    public function dashboardChartData(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        $chartRaw = Order::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartData[] = (int) ($chartRaw[$m] ?? 0);
        }

        return response()->json(['year' => $year, 'data' => $chartData]);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. MAINTENANCE MODE + BACKUP
    | GET  /admin/maintenance                (admin.maintenance)
    | POST /admin/toggle-maintenance         (admin.toggleMaintenance)
    | View: resources/views/admin/sistem/maintenance.blade.php
    |--------------------------------------------------------------------------
    */
    public function maintenance()
    {
        $isMaintenance = app()->isDownForMaintenance();

        Storage::disk('local')->makeDirectory('backups');

        $backups = collect(Storage::disk('local')->files('backups'))
            ->filter(fn ($f) => str_ends_with($f, '.sql') || str_ends_with($f, '.zip'))
            ->map(fn ($file) => [
                'name'       => basename($file),
                'size'       => round(Storage::disk('local')->size($file) / 1048576, 1) . ' MB',
                'created_at' => Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file)),
            ])
            ->sortByDesc('created_at')
            ->values();

        return view('admin.sistem.maintenance', compact('isMaintenance', 'backups'));
    }

    public function toggleMaintenance(Request $request)
    {
        if (app()->isDownForMaintenance()) {
            Artisan::call('up');
            return redirect()->back()->with('success', 'Sistem kembali Online (Maintenance Mode Nonaktif).');
        }

        $secretKey = $request->input('secret_key', 'admin-access-' . str()->random(6));
        Artisan::call('down', ['--secret' => $secretKey, '--refresh' => 15]);

        return redirect()->back()->with('warning', 'Sistem masuk ke Maintenance Mode. Akses rahasia: /' . $secretKey);
    }

    public function createBackup()
    {
        $filename = 'backup-' . now()->format('Y-m-d_His') . '.sql';
        $path     = storage_path('app/backups/' . $filename);

        Storage::disk('local')->makeDirectory('backups');

        $db  = config('database.connections.mysql');
        $cmd = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($db['username']),
            escapeshellarg($db['password']),
            escapeshellarg($db['host']),
            escapeshellarg($db['database']),
            escapeshellarg($path)
        );

        try {
            Process::run($cmd);
            return redirect()->back()->with('success', 'Backup database berhasil dibuat: ' . $filename);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    public function downloadBackup(string $filename)
    {
        $path = 'backups/' . basename($filename);

        if (! Storage::disk('local')->exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        return Storage::disk('local')->download($path);
    }

    public function deleteBackup(string $filename)
    {
        Storage::disk('local')->delete('backups/' . basename($filename));
        return redirect()->back()->with('success', 'File backup berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | 3. MANAJEMEN PENGGUNA (Akun Pengguna)
    | GET /admin/users  (admin.users)
    | View: resources/views/admin/manajemen/akun_pengguna.blade.php
    |--------------------------------------------------------------------------
    */
    public function users(Request $request)
    {
        $search = $request->query('search');

        $users = User::with(['role', 'membership'])
            ->whereHas('role', fn ($q) => $q->whereIn('role_name', ['pembeli', 'penjual']))
            ->when($search, fn ($q) => $q->where(fn ($qq) => $qq->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalUsers     = User::whereHas('role', fn ($q) => $q->whereIn('role_name', ['pembeli', 'penjual']))->count();
        $activeCreators = User::whereHas('role', fn ($q) => $q->where('role_name', 'penjual'))->whereHas('products')->count();
        $newThisMonth   = User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $blockedUsers   = User::where('status', 'blocked')->count();

        $roles = Role::whereIn('role_name', ['pembeli', 'penjual'])->get();

        return view('admin.manajemen.akun_pengguna', compact(
            'users', 'totalUsers', 'activeCreators', 'newThisMonth', 'blockedUsers', 'roles'
        ));
    }

    // POST /admin/users  (admin.users.store)
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone'    => 'nullable|string|max:20',
            'id_role'  => 'required|exists:roles,id_role',
            'status'   => 'nullable|in:active,inactive,blocked',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone'    => $validated['phone'] ?? null,
            'id_role'  => $validated['id_role'],
            'status'   => $validated['status'] ?? 'active',
        ]);

        return redirect()->back()->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    // PUT /admin/users/{id}  (admin.users.update)
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'phone'    => 'nullable|string|max:20',
            'id_role'  => 'required|exists:roles,id_role',
            'status'   => 'required|in:active,inactive,blocked',
            'password' => 'nullable|min:8',
        ]);

        $data = collect($validated)->except('password')->toArray();
        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // DELETE /admin/users/{id}  (admin.users.delete) — sudah ada di route lamamu
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role?->role_name === 'admin') {
            return redirect()->back()->with('error', 'Akun Admin tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | 4. AKUN VERIFIKATOR & ANTREAN VERIFIKASI IDENTITAS
    | GET /admin/users/verifikator  (admin.users.verifikator)
    | View: resources/views/admin/manajemen/akun_verifikator.blade.php
    |--------------------------------------------------------------------------
    */
    public function verifikator()
    {
        $verifikatorRole = Role::where('role_name', 'verifikator')->first();

        $verifikators = User::where('id_role', $verifikatorRole->id_role ?? 0)
            ->latest()
            ->get()
            ->map(function ($v) {
                $v->total_checked = $v->identityVerificationsAsVerifier()
                    ->whereIn('status', ['approved', 'rejected'])
                    ->count();
                return $v;
            });

        $pendingQueue = IdentityVerification::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        $totalVerifikator = $verifikators->count();
        $antreanMasuk     = IdentityVerification::where('status', 'pending')->count();
        $selesaiHariIni   = IdentityVerification::whereDate('verified_at', today())->count();

        $totalDiperiksa = max(1, IdentityVerification::whereIn('status', ['approved', 'rejected'])->count());
        $totalDisetujui = IdentityVerification::where('status', 'approved')->count();
        $akurasiSistem  = round(($totalDisetujui / $totalDiperiksa) * 100, 1);

        return view('admin.manajemen.akun_verifikator', compact(
            'verifikators', 'pendingQueue', 'totalVerifikator', 'antreanMasuk', 'selesaiHariIni', 'akurasiSistem'
        ));
    }

    // POST /admin/users/add-verifier  (admin.users.addVerifier) — sudah ada di route lamamu
    public function addVerifier(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $verifikatorRole = Role::firstOrCreate(['role_name' => 'verifikator']);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'id_role'  => $verifikatorRole->id_role,
            'status'   => 'active',
        ]);

        return redirect()->back()->with('success', 'Verifikator berhasil ditambahkan.');
    }

    // PUT /admin/users/verifier/{id}  (admin.users.updateVerifier)
    public function updateVerifier(Request $request, $id)
    {
        $verifier = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $verifier->id_user . ',id_user',
            'password' => 'nullable|min:8',
        ]);

        $data = collect($validated)->except('password')->toArray();
        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $verifier->update($data);

        return redirect()->back()->with('success', 'Data verifikator berhasil diperbarui.');
    }

    // DELETE /admin/users/verifier/{id}  (admin.users.deleteVerifier)
    public function deleteVerifier($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Verifikator berhasil dihapus.');
    }

    // POST /admin/users/approve-seller/{id}  (admin.users.approveSeller) — sudah ada di route lamamu
    public function approveSeller(Request $request, $id)
    {
        $verification = IdentityVerification::findOrFail($id);
        $verification->update([
            'status'      => 'approved',
            'verifier_id' => auth()->id(),
            'verified_at' => now(),
        ]);

        User::where('id_user', $verification->user_id)->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Pengajuan identitas disetujui.');
    }

    // POST /admin/users/reject-seller/{id}  (admin.users.rejectSeller)
    public function rejectSeller(Request $request, $id)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        $verification = IdentityVerification::findOrFail($id);
        $verification->update([
            'status'      => 'rejected',
            'verifier_id' => auth()->id(),
            'notes'       => $request->notes,
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pengajuan identitas ditolak.');
    }

    /*
    |--------------------------------------------------------------------------
    | 5. KATALOG: DAFTAR JASA (Product)
    | GET /admin/products  (admin.products) — sudah ada di route lamamu
    | View: resources/views/admin/katalog/daftar_jasa.blade.php
    |--------------------------------------------------------------------------
    */
    public function products(Request $request)
    {
        $search = $request->query('search');

        $products = Product::with(['category', 'seller'])
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pendingCount = Product::where('status', 'pending')->count();
        $activeCount  = Product::where('status', 'active')->count();

        return view('admin.katalog.daftar_jasa', compact('products', 'pendingCount', 'activeCount'));
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

    // DELETE /admin/products/{id}  (admin.products.delete)
    public function deleteProduct($id)
    {
        Product::where('id_product', $id)->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus permanen.');
    }

    /*
    |--------------------------------------------------------------------------
    | 6. KATALOG: KATEGORI JASA
    | GET /admin/categories  (admin.categories.index)
    | View: resources/views/admin/katalog/kategori_jasa.blade.php
    |--------------------------------------------------------------------------
    */
    public function categories()
    {
        $categories = Category::withCount('products')->latest()->get();

        $totalKategori    = $categories->count();
        $kategoriPopuler  = $categories->sortByDesc('products_count')->first();
        $kategoriNonaktif = $categories->where('status', 'nonaktif')->count();

        return view('admin.katalog.kategori_jasa', compact(
            'categories', 'totalKategori', 'kategoriPopuler', 'kategoriNonaktif'
        ));
    }

    // POST /admin/categories  (admin.categories.store) — sudah ada di route lamamu
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:aktif,nonaktif',
            'icon'        => 'nullable|string|max:100',
        ]);

        Category::create($validated);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    // PUT /admin/categories/{id}  (admin.categories.update)
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id_category . ',id_category',
            'description' => 'nullable|string',
            'status'      => 'required|in:aktif,nonaktif',
            'icon'        => 'nullable|string|max:100',
        ]);

        $category->update($validated);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    // DELETE /admin/categories/{id}  (admin.categories.delete) — sudah ada di route lamamu
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->exists()) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | 7. TRANSAKSI & KEUANGAN: RIWAYAT PESANAN
    | GET /admin/transactions  (admin.transactions) — sudah ada di route lamamu
    | View: resources/views/admin/keuangan/riwayat_pesanan.blade.php
    |--------------------------------------------------------------------------
    */
    public function transactions(Request $request)
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

        $totalCommission = Order::where('payment_status', 'paid')->sum('total_price') * 0.05;

        $totalTransaksi = Order::count();
        $sedangDiproses = Order::whereIn('status', ['pending', 'diproses'])->count();
        $orderSelesai   = Order::where('status', 'selesai')->count();
        $dibatalkan     = Order::where('status', 'dibatalkan')->count();

        return view('admin.keuangan.riwayat_pesanan', compact(
            'orders', 'totalCommission', 'totalTransaksi', 'sedangDiproses', 'orderSelesai', 'dibatalkan'
        ));
    }

    // GET /admin/transactions/export  (admin.transactions.export)
    public function exportTransactions()
    {
        $orders   = Order::with(['buyer', 'items'])->latest()->get();
        $filename = 'riwayat-pesanan-' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID Order', 'Pembeli', 'Total', 'Status Pembayaran', 'Status Order', 'Tanggal']);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->kode_order,
                    $order->buyer->name ?? '-',
                    $order->total_price,
                    $order->payment_status,
                    $order->status,
                    $order->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // GET /admin/transactions/{id}  (admin.transactions.detail)
    public function transactionDetail($id)
    {
        $order = Order::with(['buyer', 'items.product.seller'])->findOrFail($id);
        return response()->json($order);
    }

    /*
    |--------------------------------------------------------------------------
    | 8. KEUANGAN: PENARIKAN SALDO
    | GET /admin/withdrawals  (admin.withdrawals)
    | View: resources/views/admin/keuangan/penarikan_saldo.blade.php
    |--------------------------------------------------------------------------
    */
    public function withdrawals(Request $request)
    {
        $search = $request->query('search');

        $withdrawalList = Withdrawal::with('user')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))
                  ->orWhere('id_withdrawal', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $menungguDiproses = Withdrawal::where('status', 'pending')->count();
        $selesaiBulanIni  = Withdrawal::where('status', 'processed')
            ->whereMonth('processed_at', now()->month)
            ->whereYear('processed_at', now()->year)
            ->sum('amount');
        $gagalDitolak = Withdrawal::where('status', 'rejected')->count();

        return view('admin.keuangan.penarikan_saldo', [
            'withdrawals'      => $withdrawalList,
            'menungguDiproses' => $menungguDiproses,
            'selesaiBulanIni'  => $selesaiBulanIni,
            'gagalDitolak'     => $gagalDitolak,
        ]);
    }

    // POST /admin/withdrawals/{id}/process  (admin.withdrawals.process)
    public function processWithdrawal($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->update([
            'status'       => 'processed',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Penarikan saldo berhasil diproses.');
    }

    // POST /admin/withdrawals/{id}/reject  (admin.withdrawals.reject)
    public function rejectWithdrawal(Request $request, $id)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->update([
            'status'       => 'rejected',
            'notes'        => $request->notes,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Penarikan saldo ditolak.');
    }

    /*
    |--------------------------------------------------------------------------
    | 9. MEMBERSHIP CARD MANAGEMENT
    | GET /admin/memberships  (admin.memberships) — sudah ada di route lamamu
    | View: resources/views/admin/membership/paket_membership.blade.php
    |--------------------------------------------------------------------------
    */
    public function memberships()
    {
        $memberships = Membership::withCount('users')->get();
        $totalPelangganAktif = User::whereNotNull('id_membership')->count();

        return view('admin.membership.paket_membership', compact('memberships', 'totalPelangganAktif'));
    }

    // POST /admin/memberships  (admin.memberships.store)
    public function storeMembership(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_upload'    => 'required|integer|min:0',
            'benefit'       => 'required|string',
        ]);

        Membership::create($validated);

        return redirect()->back()->with('success', 'Paket membership baru berhasil ditambahkan.');
    }

    // PUT /admin/memberships/{id}  (admin.memberships.update) — sudah ada di route lamamu
    public function updateMembership(Request $request, $id)
    {
        $validated = $request->validate([
            'name'          => 'required|string',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_upload'    => 'required|integer|min:0',
            'benefit'       => 'required|string',
        ]);

        $membership = Membership::findOrFail($id);
        $membership->update($validated);

        return redirect()->back()->with('success', 'Kartu membership berhasil diperbarui.');
    }

    // DELETE /admin/memberships/{id}  (admin.memberships.delete)
    public function deleteMembership($id)
    {
        $membership = Membership::findOrFail($id);

        if ($membership->users()->exists()) {
            return redirect()->back()->with('error', 'Paket tidak dapat dihapus karena masih memiliki pelanggan aktif.');
        }

        $membership->delete();

        return redirect()->back()->with('success', 'Paket membership berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | 10. PROFILE ADMIN — sudah ada di route lamamu
    |--------------------------------------------------------------------------
    */
    public function profile()
    {
        $admin = auth()->user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = User::findOrFail(auth()->id());

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $admin->id_user . ',id_user',
            'phone'    => 'nullable|string',
            'password' => 'nullable|min:8',
        ]);

        $data = $request->only('name', 'email', 'phone');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->back()->with('success', 'Profil Admin berhasil diperbarui.');
    }
}