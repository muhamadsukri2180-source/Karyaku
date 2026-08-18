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
use App\Models\CustomerService;
use App\Models\IpLog;
use App\Models\AllowedIp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use ZipArchive;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. DASHBOARD -> GET /admin/dashboard
    |--------------------------------------------------------------------------
    */
    public function dashboard(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        $totalProducts         = Product::count();
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

        $chartRaw = Order::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartData[] = (int) ($chartRaw[$m] ?? 0);
        }

        $topCategories = Category::all()->map(function ($cat) {
            $cat->order_count = OrderItem::whereHas('product', fn ($q) => $q->where('category_id', $cat->id_category))->count();
            return $cat;
        })->sortByDesc('order_count')->take(4)->values();

        $totalCategoryOrders = max(1, $topCategories->sum('order_count'));
        $topCategories = $topCategories->map(function ($cat) use ($totalCategoryOrders) {
            $cat->percentage = round(($cat->order_count / $totalCategoryOrders) * 100);
            return $cat;
        });

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
    | 2. MAINTENANCE MODE + BACKUP DATABASE (GOOGLE DRIVE)
    |--------------------------------------------------------------------------
    */
    public function maintenance()
    {
        $statusFile = storage_path('framework/maintenance_mode.json');
        $currentMode = 'none';
        $currentEndAt = null;

        if (file_exists($statusFile)) {
            $data       = json_decode(file_get_contents($statusFile), true);
            $targetRole = $data['target_role'] ?? 'none';
            $endAt      = $data['end_at'] ?? null;

            if ($targetRole !== 'none' && $endAt) {
                try {
                    $targetTimestamp = isset($data['timestamp']) 
                        ? $data['timestamp'] 
                        : Carbon::parse($endAt, 'Asia/Jakarta')->timestamp;

                    if (now('Asia/Jakarta')->timestamp >= $targetTimestamp) {
                        @unlink($statusFile);
                        $currentMode  = 'none';
                        $currentEndAt = null;
                    } else {
                        $currentMode  = $targetRole;
                        $currentEndAt = $endAt;
                    }
                } catch (\Exception $e) {
                    @unlink($statusFile);
                    $currentMode  = 'none';
                    $currentEndAt = null;
                }
            } else {
                $currentMode  = $targetRole;
                $currentEndAt = $endAt;
            }
        }

        $isMaintenance = app()->isDownForMaintenance();

        Storage::disk('local')->makeDirectory('backups');

        $backups = collect(Storage::disk('local')->files('backups'))
            ->filter(fn ($f) => str_ends_with($f, '.sql') || str_ends_with($f, '.zip'))
            ->map(fn ($file) => [
                'name'       => basename($file),
                'size'       => $this->formatBytes(Storage::disk('local')->size($file)),
                'created_at' => Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file)),
            ])
            ->sortByDesc('created_at')
            ->values();

        return view('admin.sistem.maintenance', compact('isMaintenance', 'currentMode', 'currentEndAt', 'backups'));
    }

    private function formatBytes(int $bytes, int $decimals = 2): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $power = (int) floor(log($bytes, 1024));
        $power = max(0, min($power, count($units) - 1));

        $value = $bytes / (1024 ** $power);

        return round($value, $decimals) . ' ' . $units[$power];
    }

    public function toggleMaintenance(Request $request)
    {
        $targetRole = $request->input('target_role', 'none');
        $statusFile = storage_path('framework/maintenance_mode.json');

        if ($targetRole === 'none') {
            if (file_exists($statusFile)) {
                @unlink($statusFile);
            }
            if (app()->isDownForMaintenance()) {
                Artisan::call('up');
            }
            return redirect()->back()->with('success', 'Sistem kembali Online dan Berjalan Normal.');
        }

        $validated = $request->validate([
            'end_at' => 'required|date',
        ]);

        $endAtCarbon = Carbon::parse($validated['end_at'], 'Asia/Jakarta');

        $data = [
            'target_role' => $targetRole,
            'time'        => now('Asia/Jakarta')->toIso8601String(),
            'end_at'      => $endAtCarbon->toIso8601String(),
            'timestamp'   => $endAtCarbon->timestamp,
        ];

        file_put_contents($statusFile, json_encode($data, JSON_PRETTY_PRINT));

        if (app()->isDownForMaintenance()) {
            Artisan::call('up');
        }

        return redirect()->back()->with('warning', 'Mode Maintenance berhasil diterapkan untuk target: ' . strtoupper($targetRole));
    }

    public function createBackup()
    {
        $timestamp    = now()->format('Y-m-d_His');
        $sqlFilename  = 'backup-' . $timestamp . '.sql';
        $zipFilename  = 'backup-' . $timestamp . '.zip';

        $backupDir     = storage_path('app/backups');
        $localSqlPath  = $backupDir . DIRECTORY_SEPARATOR . $sqlFilename;
        $localZipPath  = $backupDir . DIRECTORY_SEPARATOR . $zipFilename;

        Storage::disk('local')->makeDirectory('backups');

        try {
            set_time_limit(300);
            ini_set('memory_limit', '512M');

            $dbName   = config('database.connections.mysql.database');
            $tables   = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $dbName;

            $sqlDump  = "-- Backup Database Karyaku\n";
            $sqlDump .= "-- Tanggal: " . now()->format('d M Y - H:i:s') . " WIB\n\n";
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $tableObj) {
                $table = $tableObj->$tableKey ?? current((array) $tableObj);

                $createTableResult = DB::select("SHOW CREATE TABLE `{$table}`");
                $createSql = $createTableResult[0]->{'Create Table'} ?? null;

                if ($createSql) {
                    $sqlDump .= "DROP TABLE IF EXISTS `{$table}`;\n";
                    $sqlDump .= $createSql . ";\n\n";

                    $rows = DB::table($table)->get();
                    if ($rows->count() > 0) {
                        foreach ($rows as $row) {
                            $rowArray = (array) $row;
                            $values = array_map(function ($val) {
                                if (is_null($val)) return 'NULL';
                                return DB::getPdo()->quote($val);
                            }, $rowArray);

                            $sqlDump .= "INSERT INTO `{$table}` (`" . implode('`, `', array_keys($rowArray)) . "`) VALUES (" . implode(', ', $values) . ");\n";
                        }
                        $sqlDump .= "\n";
                    }
                }
            }

            $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

            file_put_contents($localSqlPath, $sqlDump);

            $zip = new ZipArchive();
            if ($zip->open($localZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \Exception('Gagal membuat file ZIP backup di server.');
            }
            $zip->addFile($localSqlPath, $sqlFilename);
            $zip->close();

            if (file_exists($localSqlPath)) {
                @unlink($localSqlPath);
            }

            if (!file_exists($localZipPath)) {
                throw new \Exception('File ZIP backup tidak ditemukan setelah proses kompresi.');
            }

            if (filesize($localZipPath) === 0) {
                @unlink($localZipPath);
                throw new \Exception('Proses kompresi ZIP menghasilkan file kosong (0 byte). Backup dibatalkan.');
            }

            $driveUploaded     = false;
            $driveErrorMessage = null;

            try {
                $stream = fopen($localZipPath, 'r');
                $driveUploaded = Storage::disk('google')->put($zipFilename, $stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
            } catch (\Throwable $driveError) {
                $driveErrorMessage = $driveError->getMessage();
            }

            if ($driveUploaded) {
                return redirect()->back()->with('success', 'Backup database (ZIP) BERHASIL dibuat di lokal & dikirim ke Google Drive!');
            }

            $msg = 'Backup database (ZIP) BERHASIL dibuat di lokal, namun GAGAL terkirim ke Google Drive.';
            if ($driveErrorMessage) {
                $msg .= ' Detail: ' . $driveErrorMessage;
            }

            return redirect()->back()->with('warning', $msg);

        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal membuat backup database: ' . $e->getMessage());
        }
    }

    public function downloadBackup(string $filename)
    {
        $path = storage_path('app/backups/' . basename($filename));

        if (!file_exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        return response()->download($path);
    }

    public function deleteBackup(string $filename)
    {
        Storage::disk('local')->delete('backups/' . basename($filename));
        return redirect()->back()->with('success', 'File backup berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | 3. MANAJEMEN PENGGUNA (Akun Pengguna)
    |--------------------------------------------------------------------------
    */
    public function users(Request $request)
    {
        $search = $request->query('search');

        /** @var \Illuminate\Pagination\LengthAwarePaginator $users */
        $users = User::with(['role', 'membership'])
            ->whereHas('role', fn ($q) => $q->whereIn('role_name', ['pembeli', 'penjual']))
            ->when($search, fn ($q) => $q->where(fn ($qq) => $qq->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")))
            ->latest()
            ->paginate(15);

        $users->withQueryString();

        $totalUsers   = User::whereHas('role', fn ($q) => $q->whereIn('role_name', ['pembeli', 'penjual']))->count();
        $activeCreators = User::whereHas('role', fn ($q) => $q->where('role_name', 'penjual'))->whereHas('products')->count();
        $newThisMonth   = User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $blockedUsers   = User::where('status', 'blocked')->count();

        $roles = Role::whereIn('role_name', ['pembeli', 'penjual'])->get();

        return view('admin.manajemen.akun_pengguna', compact(
            'users', 'totalUsers', 'activeCreators', 'newThisMonth', 'blockedUsers', 'roles'
        ));
    }

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

    public function updateUser(Request $request, string|int $id)
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

    public function deleteUser(string|int $id)
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
    |--------------------------------------------------------------------------
    */
    public function verifikator()
    {
        $verifikatorRole = Role::where('role_name', 'verifikator')->first();

        $verifikators = collect();
        if ($verifikatorRole) {
            $verifikators = User::where('id_role', $verifikatorRole->id_role)
                ->latest('id_user')
                ->get()
                ->map(function ($v) {
                    $v->total_checked = IdentityVerification::where('verifier_id', $v->id_user)
                        ->whereIn('status', ['approved', 'rejected'])
                        ->count();
                    return $v;
                });
        }

        $pendingQueue = IdentityVerification::with(['user'])
            ->where('status', 'pending')
            ->latest('id_identity_verification')
            ->paginate(10)
            ->withQueryString();

        $totalVerifikator = $verifikators->count();
        $antreanMasuk     = IdentityVerification::where('status', 'pending')->count();
        $selesaiHariIni   = IdentityVerification::whereDate('verified_at', today())
            ->whereIn('status', ['approved', 'rejected'])
            ->count();

        $totalDiperiksa = IdentityVerification::whereIn('status', ['approved', 'rejected'])->count();
        $totalDisetujui = IdentityVerification::where('status', 'approved')->count();
        $akurasiSistem  = $totalDiperiksa > 0 ? round(($totalDisetujui / $totalDiperiksa) * 100, 1) : 100;

        return view('admin.manajemen.akun_verifikator', compact(
            'verifikators', 'pendingQueue', 'totalVerifikator', 'antreanMasuk', 'selesaiHariIni', 'akurasiSistem'
        ));
    }

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

    public function updateVerifier(Request $request, string|int $id)
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

    public function deleteVerifier(string|int $id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Verifikator berhasil dihapus.');
    }

    public function approveSeller(Request $request, string|int $id)
    {
        if (!auth()->check()) {
            return redirect()->route('auth.login')->with('error', 'Sesi login telah berakhir. Silakan login kembali.');
        }

        $verification = IdentityVerification::find($id);

        if (!$verification) {
            return redirect()->back()->with('error', 'Data pengajuan verifikasi tidak ditemukan.');
        }

        if ($verification->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $user = User::where('id_user', $verification->user_id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User pemohon tidak ditemukan.');
        }

        $penjualRole = Role::where('role_name', 'penjual')->first();

        if (!$penjualRole) {
            return redirect()->back()->with('error', 'Role penjual tidak ditemukan di database.');
        }

        DB::beginTransaction();

        try {
            $verification->update([
                'status'      => 'approved',
                'verifier_id' => auth()->id(),
                'verified_at' => now(),
            ]);

            $user->update([
                'id_role' => $penjualRole->id_role,
                'status'  => 'active',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pengajuan identitas berhasil disetujui. Akun user sekarang menjadi penjual.');

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Pengajuan gagal disetujui. Silakan coba lagi.');
        }
    }

    public function rejectSeller(Request $request, string|int $id)
    {
        if (!auth()->check()) {
            return redirect()->route('auth.login')->with('error', 'Sesi login telah berakhir. Silakan login kembali.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $verification = IdentityVerification::find($id);

        if (!$verification) {
            return redirect()->back()->with('error', 'Data pengajuan verifikasi tidak ditemukan.');
        }

        if ($verification->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        try {
            $verification->update([
                'status'      => 'rejected',
                'verifier_id' => auth()->id(),
                'notes'       => $validated['notes'] ?? null,
                'verified_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Pengajuan identitas berhasil ditolak.');

        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Pengajuan gagal ditolak. Silakan coba lagi.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 5. AKUN & LAYANAN CUSTOMER SERVICE
    |--------------------------------------------------------------------------
    */
    public function serviceAccounts()
    {
        $roleCs = Role::where('role_name', 'customer_service')->first();
        $csUsers = $roleCs ? User::where('id_role', $roleCs->id_role)->get() : collect();

        $tickets = CustomerService::with('user')->latest()->get();

        $stats = [
            'selesai' => $tickets->where('status', 'selesai')->count(),
            'proses'  => $tickets->where('status', 'proses')->count(),
            'belum'   => $tickets->where('status', 'belum')->count(),
        ];

        return view('admin.manajemen.akun_service', compact('csUsers', 'tickets', 'stats'));
    }

    public function storeServiceAccount(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $role = Role::firstOrCreate(['role_name' => 'customer_service']);

        User::create([
            'id_role'  => $role->id_role,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'status'   => 'active',
        ]);

        return back()->with('success', 'Akun Customer Service berhasil ditambahkan!');
    }

    public function deleteServiceAccount(string|int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Akun Customer Service berhasil dihapus!');
    }

    public function updateTicketStatus(Request $request, string|int $id)
    {
        $ticket = CustomerService::findOrFail($id);
        $ticket->update([
            'status'     => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Status keluhan / masukan berhasil diperbarui!');
    }

    /*
    |--------------------------------------------------------------------------
    | 6. KATALOG: DAFTAR JASA (Product)
    |--------------------------------------------------------------------------
    */
    public function products(Request $request)
    {
        $search = $request->query('search');

        /** @var \Illuminate\Pagination\LengthAwarePaginator $products */
        $products = Product::with(['category', 'seller'])
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15);

        $products->withQueryString();

        $pendingCount = Product::where('status', 'pending')->count();
        $activeCount  = Product::where('status', 'active')->count();

        return view('admin.katalog.daftar_jasa', compact('products', 'pendingCount', 'activeCount'));
    }

    public function approveProduct(string|int $id)
    {
        Product::where('id_product', $id)->update(['status' => 'active']);
        return redirect()->back()->with('success', 'Produk berhasil disetujui.');
    }

    public function takedownProduct(string|int $id)
    {
        Product::where('id_product', $id)->update(['status' => 'inactive']);
        return redirect()->back()->with('success', 'Produk berhasil di-takedown.');
    }

    public function deleteProduct(string|int $id)
    {
        Product::where('id_product', $id)->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus permanen.');
    }

    /*
    |--------------------------------------------------------------------------
    | 7. KATALOG: KATEGORI JASA
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

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:aktif,nonaktif',
        ]);

        Category::create($validated);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, string|int $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id_category . ',id_category',
            'description' => 'nullable|string',
            'status'      => 'required|in:aktif,nonaktif',
        ]);

        $category->update($validated);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function deleteCategory(string|int $id)
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
    | 8. TRANSAKSI & KEUANGAN: RIWAYAT PESANAN
    |--------------------------------------------------------------------------
    */
    public function transactions(Request $request)
    {
        $search = $request->query('search');

        /** @var \Illuminate\Pagination\LengthAwarePaginator $orders */
        $orders = Order::with(['buyer', 'items.product.seller'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('buyer', fn ($qq) => $qq->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(15);

        $orders->withQueryString();

        $totalCommission = Order::where('payment_status', 'paid')->sum('total_price') * 0.05;

        $totalTransaksi = Order::count();
        $sedangDiproses = Order::whereIn('status', ['pending', 'diproses'])->count();
        $orderSelesai   = Order::where('status', 'selesai')->count();
        $dibatalkan     = Order::where('status', 'dibatalkan')->count();

        return view('admin.keuangan.riwayat_pesanan', compact(
            'orders', 'totalCommission', 'totalTransaksi', 'sedangDiproses', 'orderSelesai', 'dibatalkan'
        ));
    }

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
            fputcsv($handle, ['Pembeli', 'Total', 'Status Pembayaran', 'Status Order', 'Tanggal']);

            foreach ($orders as $order) {
                fputcsv($handle, [
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

    public function transactionDetail(string|int $id)
    {
        $order = Order::with(['buyer', 'items.product.seller'])->findOrFail($id);
        return response()->json($order);
    }

    /*
    |--------------------------------------------------------------------------
    | 9. KEUANGAN: PENARIKAN SALDO
    |--------------------------------------------------------------------------
    */
    public function withdrawals(Request $request)
    {
        $search = $request->query('search');

        /** @var \Illuminate\Pagination\LengthAwarePaginator $withdrawals */
        $withdrawals = Withdrawal::with('user')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', fn ($qq) => $qq->where('name', 'like', "%{$search}%"))
                  ->orWhere('id_withdrawal', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        $withdrawals->withQueryString();

        $menungguDiproses = Withdrawal::where('status', 'pending')->count();
        $selesaiBulanIni  = Withdrawal::where('status', 'processed')
            ->whereMonth('processed_at', now()->month)
            ->whereYear('processed_at', now()->year)
            ->sum('amount');
        $gagalDitolak = Withdrawal::where('status', 'rejected')->count();

        return view('admin.keuangan.penarikan_saldo', [
            'withdrawals'      => $withdrawals,
            'menungguDiproses' => $menungguDiproses,
            'selesaiBulanIni'  => $selesaiBulanIni,
            'gagalDitolak'     => $gagalDitolak,
        ]);
    }

    public function processWithdrawal(string|int $id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->update([
            'status'       => 'processed',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Penarikan saldo berhasil diproses.');
    }

    public function rejectWithdrawal(Request $request, string|int $id)
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
    | 10. MEMBERSHIP CARD MANAGEMENT
    |--------------------------------------------------------------------------
    */
    public function memberships()
    {
        $memberships = Membership::withCount('users')->get();
        $totalPelangganAktif = User::whereNotNull('id_membership')->count();

        $diamondCount = User::whereHas('membership', fn($q) => $q->where('name', 'LIKE', '%Diamond%'))->count();
        $goldCount    = User::whereHas('membership', fn($q) => $q->where('name', 'LIKE', '%Gold%'))->count();
        $silverCount  = User::whereHas('membership', fn($q) => $q->where('name', 'LIKE', '%Silver%'))->count();
        $bronzeCount  = User::whereHas('membership', fn($q) => $q->where('name', 'LIKE', '%Bronze%'))->count();

        return view('admin.membership.paket_membership', compact(
            'memberships',
            'totalPelangganAktif',
            'diamondCount',
            'goldCount',
            'silverCount',
            'bronzeCount'
        ));
    }

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

    public function updateMembership(Request $request, string|int $id)
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

    public function deleteMembership(string|int $id)
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
    | 11. LAPORAN PELANGGARAN (SISTEM)
    |--------------------------------------------------------------------------
    */
    public function pelanggaran()
    {
        $reportsUser = Report::with(['reporter', 'reportedUser'])
            ->whereNull('product_id')
            ->latest()
            ->paginate(10, ['*'], 'page_user');

        $reportsProduk = Report::with(['reporter', 'product.seller'])
            ->whereNotNull('product_id')
            ->latest()
            ->paginate(10, ['*'], 'page_produk');

        return view('admin.sistem.pelanggaran', compact('reportsUser', 'reportsProduk'));
    }

    public function tindakUserPelanggaran(Request $request, string|int $id)
    {
        $request->validate([
            'action'      => 'required|string|in:peringatan,suspend,abaikan',
            'admin_notes' => 'required|string|max:500',
        ]);

        $report = Report::findOrFail($id);

        $report->update([
            'status'      => $request->action === 'abaikan' ? 'dismissed' : 'reviewed',
            'admin_note'  => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Tindakan laporan pengguna berhasil diproses.');
    }

    public function tindakProdukPelanggaran(Request $request, string|int $id)
    {
        $request->validate([
            'action'      => 'required|string|in:peringatan,suspend,abaikan',
            'admin_notes' => 'required|string|max:500',
        ]);

        $report = Report::findOrFail($id);

        $report->update([
            'status'      => $request->action === 'abaikan' ? 'dismissed' : 'reviewed',
            'admin_note'  => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Tindakan laporan produk berhasil diproses.');
    }

    /*
    |--------------------------------------------------------------------------
    | 12. PROFILE ADMIN
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

    /*
    |--------------------------------------------------------------------------
    | 13. KEAMANAN SYSTEM & MONITORING IP
    |--------------------------------------------------------------------------
    */
    public function securityVerifyPage()
    {
        // Jika sudah terverifikasi sebelumnya, langsung arahkan ke index keamanan
        if (session()->has('security_verified_at')) {
            return redirect()->route('admin.security.index');
        }

        return view('admin.security.verify');
    }

    public function securityProcessVerify(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'pin'      => 'required|numeric',
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Password Admin Salah! Akses Ditolak.');
        }

        $secretPin = env('SECURITY_ACCESS_PIN', '123456');
        if ($request->pin != $secretPin) {
            return back()->with('error', 'Kode PIN Keamanan Salah! Akses Ditolak.');
        }

        // Simpan tanda verifikasi ke dalam session
        session(['security_verified_at' => now()]);

        return redirect()->route('admin.security.index')->with('success', 'Akses Keamanan Diberikan.');
    }

    public function securityIndex(Request $request)
    {
        // 🔒 PROTEKSI VERIFIKASI: Wajib Lolos PIN & Password Dulu
        if (!session()->has('security_verified_at')) {
            return redirect()->route('admin.security.verify')->with('warning', 'Silakan verifikasi kata sandi & PIN keamanan terlebih dahulu.');
        }

        $normalIps   = IpLog::where('status', 'normal')->latest('last_activity_at')->get();
        $abnormalIps = IpLog::where('status', 'abnormal')->latest('last_activity_at')->get();
        $allowedIps  = AllowedIp::latest()->get();
        $myIp        = $request->ip();

        return view('admin.security.index', compact('normalIps', 'abnormalIps', 'allowedIps', 'myIp'));
    }

    public function securityStoreAllowedIp(Request $request)
    {
        if (!session()->has('security_verified_at')) {
            return redirect()->route('admin.security.verify');
        }

        $request->validate([
            'ip_address' => 'required|ip|unique:allowed_ips,ip_address',
            'label'      => 'required|string|max:100',
        ]);

        AllowedIp::create([
            'ip_address' => $request->ip_address,
            'label'      => $request->label,
            'added_by'   => auth()->user()->name,
        ]);

        return back()->with('success', 'IP ' . $request->ip_address . ' berhasil ditambahkan ke Whitelist Akses!');
    }

    public function securityDestroyAllowedIp(string|int $id)
    {
        if (!session()->has('security_verified_at')) {
            return redirect()->route('admin.security.verify');
        }

        $ip = AllowedIp::findOrFail($id);

        if ($ip->ip_address === request()->ip()) {
            return back()->with('error', 'Anda tidak dapat menghapus IP Anda sendiri yang sedang aktif digunakan!');
        }

        $ip->delete();
        return back()->with('success', 'IP berhasil dihapus dari Whitelist.');
    }

    public function securityToggleStatus(string|int $id)
    {
        if (!session()->has('security_verified_at')) {
            return redirect()->route('admin.security.verify');
        }

        $ip = IpLog::findOrFail($id);
        $ip->status = $ip->status === 'normal' ? 'abnormal' : 'normal';
        $ip->reason = $ip->status === 'abnormal' ? 'Ditandai manual sebagai ancaman oleh Admin' : 'Dibersihkan oleh Admin';
        $ip->save();

        return back()->with('success', 'Status IP ' . $ip->ip_address . ' diperbarui.');
    }

    public function securityDestroyLog(string|int $id)
    {
        if (!session()->has('security_verified_at')) {
            return redirect()->route('admin.security.verify');
        }

        IpLog::findOrFail($id)->delete();
        return back()->with('success', 'Log IP dihapus.');
    }
}