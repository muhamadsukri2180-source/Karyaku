<?php

namespace App\Http\Controllers;

use App\Models\{Product, Order, OrderItem, User, Role, Category, Membership, IdentityVerification, Withdrawal, Report, CustomerService, Notification, IpLog, AllowedIp, AccountAppeal};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Artisan, Hash, Storage, DB, Schema};
use Carbon\Carbon;
use ZipArchive;

class AdminController extends Controller
{
    /**
     * HELPER: Pengirim Notifikasi Cepat
     */
    private function sendNotif($targetUserId, $title, $description)
    {
        if ($targetUserId) {
            Notification::create([
                'user_id' => $targetUserId,
                'name' => $title,
                'description' => $description,
                'is_read' => false,
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 1. DASHBOARD SYSTEM
    |--------------------------------------------------------------------------
    */
    public function dashboard(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        $totalProducts = Product::count();
        $totalOrders  = Order::count();
        $monthlySales = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();

        $totalRevenue       = (float) Order::where('payment_status', 'paid')->sum('total_price');
        $platformCommission = $totalRevenue * 0.05;

        $totalUsers = User::whereHas('role', fn($q) => $q->whereIn('role_name', ['pembeli', 'penjual']))->count();
        $pendingIdentityCount = IdentityVerification::where('status', 'pending')->count();
        $pendingReportsCount  = Report::where('status', 'pending')->count();

        $chartRaw = Order::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('bulan')->pluck('total', 'bulan');

        $chartData = array_map(fn($m) => (int) ($chartRaw[$m] ?? 0), range(1, 12));

        $recentOrders = Order::with('buyer')->latest()->take(3)->get()->map(fn($o) => [
            'title' => 'Order Baru #' . $o->kode_order,
            'desc'  => 'Pembeli "' . ($o->buyer->name ?? '-') . '" membuat pesanan baru.',
            'time'  => $o->created_at, 'color' => 'emerald',
        ]);

        $recentProducts = Product::where('status', 'active')->latest('updated_at')->take(3)->get()->map(fn($p) => [
            'title' => 'Produk Diverifikasi', 'desc' => 'Produk "' . $p->title . '" telah disetujui.',
            'time'  => $p->updated_at, 'color' => 'sky',
        ]);

        $recentIdentities = IdentityVerification::with('user')->latest()->take(3)->get()->map(fn($iv) => [
            'title' => 'Pengajuan Identitas', 'desc' => 'Kreator "' . ($iv->user->name ?? '-') . '" mengunggah identitas.',
            'time'  => $iv->created_at, 'color' => 'amber',
        ]);

        $recentActivities = $recentOrders->concat($recentProducts)->concat($recentIdentities)
            ->sortByDesc('time')->take(5)->values();

        $isMaintenance = app()->isDownForMaintenance();

        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'monthlySales', 'totalRevenue', 'platformCommission',
            'totalUsers', 'pendingIdentityCount', 'pendingReportsCount', 'chartData', 'recentActivities',
            'isMaintenance', 'year'
        ));
    }

    public function dashboardChartData(Request $request)
    {
        $year = (int) $request->query('year', now()->year);
        $chartRaw = Order::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', $year)->groupBy('bulan')->pluck('total', 'bulan');
            
        $chartData = array_map(fn($m) => (int) ($chartRaw[$m] ?? 0), range(1, 12));
        return response()->json(['year' => $year, 'data' => $chartData]);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. MAINTENANCE MODE & BACKUP DATABASE
    |--------------------------------------------------------------------------
    */
    public function maintenance()
    {
        $statusFile = storage_path('framework/maintenance_mode.json');
        $currentMode = 'none';
        $currentEndAt = null;

        if (file_exists($statusFile)) {
            $data = json_decode(file_get_contents($statusFile), true);
            $targetRole = $data['target_role'] ?? 'none';
            $endAt = $data['end_at'] ?? null;

            if ($targetRole !== 'none' && $endAt) {
                $targetTs = $data['timestamp'] ?? Carbon::parse($endAt, 'Asia/Jakarta')->timestamp;
                if (now('Asia/Jakarta')->timestamp >= $targetTs) {
                    @unlink($statusFile);
                } else {
                    $currentMode = $targetRole;
                    $currentEndAt = $endAt;
                }
            }
        }

        Storage::disk('local')->makeDirectory('backups');
        $backups = collect(Storage::disk('local')->files('backups'))
            ->filter(fn($f) => str_ends_with($f, '.sql') || str_ends_with($f, '.zip'))
            ->map(fn($file) => [
                'name' => basename($file),
                'size' => $this->formatBytes(Storage::disk('local')->size($file)),
                'created_at' => Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file)),
            ])->sortByDesc('created_at')->values();

        return view('admin.sistem.maintenance', [
            'isMaintenance' => app()->isDownForMaintenance(),
            'currentMode' => $currentMode, 'currentEndAt' => $currentEndAt, 'backups' => $backups
        ]);
    }

    private function formatBytes(int $bytes, int $decimals = 2): string
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = max(0, min((int) floor(log($bytes, 1024)), count($units) - 1));
        return round($bytes / (1024 ** $power), $decimals) . ' ' . $units[$power];
    }

    public function toggleMaintenance(Request $request)
    {
        $targetRole = $request->input('target_role', 'none');
        $statusFile = storage_path('framework/maintenance_mode.json');

        if ($targetRole === 'none') {
            if (file_exists($statusFile)) @unlink($statusFile);
            if (app()->isDownForMaintenance()) Artisan::call('up');
            return back()->with('success', 'Sistem kembali Online dan Berjalan Normal.');
        }

        $validated = $request->validate(['end_at' => 'required|date']);
        $endAtCarbon = Carbon::parse($validated['end_at'], 'Asia/Jakarta');

        file_put_contents($statusFile, json_encode([
            'target_role' => $targetRole,
            'time'        => now('Asia/Jakarta')->toIso8601String(),
            'end_at'      => $endAtCarbon->toIso8601String(),
            'timestamp'   => $endAtCarbon->timestamp,
        ], JSON_PRETTY_PRINT));

        if (app()->isDownForMaintenance()) Artisan::call('up');

        return back()->with('warning', 'Mode Maintenance berhasil diterapkan untuk target: ' . strtoupper($targetRole));
    }

    public function createBackup()
    {
        $ts = now()->format('Y-m-d_His');
        $sqlFile = "backup-{$ts}.sql";
        $zipFile = "backup-{$ts}.zip";
        $backupDir = storage_path('app/backups');
        
        Storage::disk('local')->makeDirectory('backups');

        try {
            set_time_limit(300); ini_set('memory_limit', '512M');
            $dbName = config('database.connections.mysql.database');
            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $dbName;
            $sqlDump = "-- Backup Database Karyaku\n-- Tanggal: " . now()->format('d M Y - H:i:s') . "\n\nSET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $tableObj) {
                $table = $tableObj->$tableKey ?? current((array) $tableObj);
                $createSql = DB::select("SHOW CREATE TABLE `{$table}`")[0]->{'Create Table'} ?? null;

                if ($createSql) {
                    $sqlDump .= "DROP TABLE IF EXISTS `{$table}`;\n$createSql;\n\n";
                    $rows = DB::table($table)->get();
                    foreach ($rows as $row) {
                        $rowArr = (array) $row;
                        $vals = array_map(fn($v) => is_null($v) ? 'NULL' : DB::getPdo()->quote($v), $rowArr);
                        $sqlDump .= "INSERT INTO `{$table}` (`" . implode('`, `', array_keys($rowArr)) . "`) VALUES (" . implode(', ', $vals) . ");\n";
                    }
                    $sqlDump .= "\n";
                }
            }
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";
            file_put_contents("$backupDir/$sqlFile", $sqlDump);

            $zip = new ZipArchive();
            if ($zip->open("$backupDir/$zipFile", ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) throw new \Exception('Gagal membuat ZIP.');
            $zip->addFile("$backupDir/$sqlFile", $sqlFile);
            $zip->close();
            @unlink("$backupDir/$sqlFile");

            if (!file_exists("$backupDir/$zipFile") || filesize("$backupDir/$zipFile") === 0) throw new \Exception('File ZIP kosong/gagal.');

            $driveUploaded = false; $err = null;
            try {
                $stream = fopen("$backupDir/$zipFile", 'r');
                $driveUploaded = Storage::disk('google')->put($zipFile, $stream);
                if (is_resource($stream)) fclose($stream);
            } catch (\Throwable $e) { $err = $e->getMessage(); }

            if ($driveUploaded) return back()->with('success', 'Backup (ZIP) BERHASIL dibuat lokal & dikirim ke Google Drive!');
            return back()->with('warning', 'Backup (ZIP) BERHASIL dibuat lokal, tapi GAGAL dikirim ke Drive. Detail: ' . $err);

        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    public function downloadBackup(string $filename)
    {
        $path = storage_path('app/backups/' . basename($filename));
        if (!file_exists($path)) abort(404, 'File backup tidak ditemukan.');
        return response()->download($path);
    }

    public function deleteBackup(string $filename)
    {
        Storage::disk('local')->delete('backups/' . basename($filename));
        return back()->with('success', 'File backup berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | 3. MANAJEMEN PENGGUNA (AKUN PENGGUNA)
    |--------------------------------------------------------------------------
    */
    public function users(Request $request)
    {
        $search = $request->query('search');
        $users = User::with(['role', 'membership'])
            ->whereHas('role', fn($q) => $q->whereIn('role_name', ['pembeli', 'penjual']))
            ->when($search, fn($q) => $q->where(fn($qq) => $qq->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%")))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.manajemen.akun_pengguna', [
            'users' => $users,
            'totalUsers' => User::whereHas('role', fn($q) => $q->whereIn('role_name', ['pembeli', 'penjual']))->count(),
            'activeCreators' => User::whereHas('role', fn($q) => $q->where('role_name', 'penjual'))->whereHas('products')->count(),
            'newThisMonth' => User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'blockedUsers' => User::where('status', 'blocked')->count(),
            'roles' => Role::whereIn('role_name', ['pembeli', 'penjual'])->get()
        ]);
    }

    public function storeUser(Request $request)
    {
        $v = $request->validate([
            'name' => 'required|string|max:255', 'email' => 'required|email|unique:users',
            'password' => 'required|min:8', 'phone' => 'nullable|string|max:20',
            'id_role' => 'required|exists:roles,id_role', 'status' => 'nullable|in:active,inactive,blocked',
        ]);
        $v['password'] = Hash::make($v['password']);
        $v['status'] = $v['status'] ?? 'active';

        User::create($v);
        return back()->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function updateUser(Request $request, string|int $id)
    {
        $user = User::findOrFail($id);
        $v = $request->validate([
            'name' => 'required|string|max:255', 'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'phone' => 'nullable|string|max:20', 'id_role' => 'required|exists:roles,id_role',
            'status' => 'required|in:active,inactive,blocked', 'password' => 'nullable|min:8',
        ]);

        if (!empty($v['password'])) $v['password'] = Hash::make($v['password']);
        else unset($v['password']);

        $user->update($v);
        return back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function deleteUser(string|int $id)
    {
        $user = User::findOrFail($id);
        if ($user->role?->role_name === 'admin') return back()->with('error', 'Akun Admin tidak dapat dihapus.');
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    public function suspendUser(Request $request, string|int $id)
    {
        $user = User::findOrFail($id);
        if ($user->role?->role_name === 'admin') return back()->with('error', 'Admin tidak dapat disuspend.');

        if ($user->status === 'blocked') {
            $user->update(['status' => 'active', 'suspended_until' => null, 'suspend_reason' => null]);
            $this->sendNotif($user->id_user, '✅ Akun Diaktifkan Kembali', 'Akun Anda telah diaktifkan kembali oleh Admin.');
            return back()->with('success', 'Akun pengguna "' . $user->name . '" berhasil diaktifkan kembali.');
        }

        $days = (int) $request->input('suspend_days', 0);
        $hours = (int) $request->input('suspend_hours', 0);
        $minutes = (int) $request->input('suspend_minutes', 0);
        $reason = $request->input('suspend_reason') ?: 'Pelanggaran syarat dan ketentuan komunitas Karyaku';

        $totalMinutes = ($days * 1440) + ($hours * 60) + $minutes;
        $user->status = 'blocked';
        $user->suspend_reason = $reason;

        if ($totalMinutes > 0) {
            $user->suspended_until = now()->addMinutes($totalMinutes);
            $durationText = trim(($days ? "$days Hari " : '') . ($hours ? "$hours Jam " : '') . ($minutes ? "$minutes Menit" : ''));
        } else {
            $user->suspended_until = null;
            $durationText = 'Permanen (Tanpa batas waktu)';
        }
        $user->save();

        $this->sendNotif($user->id_user, '⚠️ Status Akun Ditangguhkan', 'Akun Anda dinonaktifkan sementara (' . $durationText . '). Alasan: ' . $reason);
        return back()->with('success', 'Akun "' . $user->name . '" berhasil disuspend (' . $durationText . ').');
    }

    /*
    |--------------------------------------------------------------------------
    | 4. AKUN VERIFIKATOR & VERIFIKASI IDENTITAS
    |--------------------------------------------------------------------------
    */
    public function verifikator()
    {
        $verifikatorRole = Role::where('role_name', 'verifikator')->first();
        $verifikators = $verifikatorRole ? User::where('id_role', $verifikatorRole->id_role)->latest('id_user')->get()->map(function ($v) {
            $v->total_checked = IdentityVerification::where('verifier_id', $v->id_user)->whereIn('status', ['approved', 'rejected'])->count();
            return $v;
        }) : collect();

        $pendingQueue = IdentityVerification::with(['user', 'membership'])->where('status', 'pending')
            ->latest('id_identity_verification')->paginate(10)->withQueryString();

        return view('admin.manajemen.akun_verifikator', [
            'verifikators' => $verifikators, 'pendingQueue' => $pendingQueue,
            'totalVerifikator' => $verifikators->count(),
            'antreanMasuk' => IdentityVerification::where('status', 'pending')->count(),
            'selesaiHariIni' => IdentityVerification::whereDate('verified_at', today())->whereIn('status', ['approved', 'rejected'])->count()
        ]);
    }

    public function addVerifier(Request $request)
    {
        $v = $request->validate(['name' => 'required|string|max:255', 'email' => 'required|email|unique:users', 'password' => 'required|min:8']);
        $v['password'] = Hash::make($v['password']);
        $v['id_role'] = Role::firstOrCreate(['role_name' => 'verifikator'])->id_role;
        $v['status'] = 'active';
        User::create($v);
        return back()->with('success', 'Verifikator berhasil ditambahkan.');
    }

    public function updateVerifier(Request $request, string|int $id)
    {
        $v = $request->validate([
            'name' => 'required|string|max:255', 'email' => 'required|email|unique:users,email,' . $id . ',id_user', 'password' => 'nullable|min:8',
        ]);
        if (!empty($v['password'])) $v['password'] = Hash::make($v['password']);
        else unset($v['password']);
        
        User::findOrFail($id)->update($v);
        return back()->with('success', 'Data verifikator berhasil diperbarui.');
    }

    public function deleteVerifier(string|int $id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Verifikator berhasil dihapus.');
    }

    public function approveSeller(Request $request, string|int $id)
    {
        if (!auth()->check()) return redirect()->route('auth.login')->with('error', 'Sesi login telah berakhir.');
        $verif = IdentityVerification::find($id);
        if (!$verif || $verif->status !== 'pending') return back()->with('error', 'Pengajuan tidak valid atau sudah diproses.');
        
        $user = User::find($verif->user_id ?? $verif->id_user);
        $role = Role::where('role_name', 'penjual')->first();
        if (!$user || !$role) return back()->with('error', 'User pemohon atau Role penjual tidak ditemukan.');

        DB::beginTransaction();
        try {
            $verif->update(['status' => 'approved', 'verifier_id' => auth()->id(), 'verified_at' => now()]);
            $user->update(['id_role' => $role->id_role, 'id_membership' => $verif->membership_id ?? $user->id_membership, 'status' => 'active']);
            $this->sendNotif($user->id_user, '🎉 Pendaftaran Penjual Disetujui', 'Selamat! Verifikasi identitas Anda telah disetujui.');
            DB::commit();
            return back()->with('success', 'Pengajuan disetujui. Akun user sekarang menjadi penjual.');
        } catch (\Throwable $e) {
            DB::rollBack(); report($e);
            return back()->with('error', 'Pengajuan gagal disetujui.');
        }
    }

    public function rejectSeller(Request $request, string|int $id)
    {
        if (!auth()->check()) return redirect()->route('auth.login')->with('error', 'Sesi login telah berakhir.');
        $verif = IdentityVerification::find($id);
        if (!$verif || $verif->status !== 'pending') return back()->with('error', 'Pengajuan tidak valid atau sudah diproses.');

        $verif->update(['status' => 'rejected', 'verifier_id' => auth()->id(), 'notes' => $request->notes, 'verified_at' => now()]);
        $this->sendNotif($verif->user_id ?? $verif->id_user ?? null, '❌ Pendaftaran Penjual Ditolak', 'Pengajuan ditolak. Alasan: ' . ($request->notes ?? 'Dokumen tidak sesuai.'));
        
        return back()->with('success', 'Pengajuan identitas berhasil ditolak.');
    }

    /*
    |--------------------------------------------------------------------------
    | 5. AKUN & LAYANAN CUSTOMER SERVICE
    |--------------------------------------------------------------------------
    */
    public function serviceAccounts()
    {
        $roleCs = Role::where('role_name', 'customer_service')->first();
        $tickets = CustomerService::with('user')->latest()->get();
        return view('admin.manajemen.akun_service', [
            'csUsers' => $roleCs ? User::where('id_role', $roleCs->id_role)->get() : collect(),
            'tickets' => $tickets,
            'stats' => [
                'selesai' => $tickets->whereIn('status', ['selesai', 'resolved', 'closed'])->count(),
                'proses'  => $tickets->whereIn('status', ['proses', 'in_progress'])->count(),
                'belum'   => $tickets->whereIn('status', ['belum', 'pending'])->count(),
            ]
        ]);
    }

    public function storeServiceAccount(Request $request)
    {
        $v = $request->validate(['name' => 'required|string|max:255', 'email' => 'required|email|unique:users', 'password' => 'required|min:6']);
        $v['id_role'] = Role::firstOrCreate(['role_name' => 'customer_service'])->id_role;
        $v['password'] = Hash::make($v['password']);
        $v['status'] = 'active';
        User::create($v);
        return back()->with('success', 'Akun Customer Service berhasil ditambahkan!');
    }

    public function deleteServiceAccount(string|int $id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Akun Customer Service dihapus!');
    }

    public function updateTicketStatus(Request $request, string|int $id)
    {
        $ticket = CustomerService::findOrFail($id);
        $ticket->update($request->only('status', 'admin_note'));
        $this->sendNotif($ticket->user_id ?? $ticket->id_user ?? null, 'Pembaharuan Tiket Pengaduan', 'Status tiket ' . $ticket->subject . ' menjadi: ' . strtoupper($request->status));
        return back()->with('success', 'Status keluhan berhasil diperbarui!');
    }

    /*
    |--------------------------------------------------------------------------
    | 6. KATALOG: DAFTAR JASA
    |--------------------------------------------------------------------------
    */
    public function products(Request $request)
    {
        $search = $request->query('search');
        $products = Product::with(['category', 'seller'])
            ->when($search, fn($q) => $q->where('title', 'like', "%$search%"))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.katalog.daftar_jasa', [
            'products' => $products,
            'pendingCount' => Product::where('status', 'pending')->count(),
            'activeCount'  => Product::where('status', 'active')->count()
        ]);
    }

    public function approveProduct(string|int $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'active']);
        $this->sendNotif($product->user_id ?? $product->id_user ?? null, '✅ Produk Disetujui', 'Produk "' . $product->title . '" telah disetujui.');
        return back()->with('success', 'Produk berhasil disetujui.');
    }

    public function takedownProduct(string|int $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'inactive']);
        $this->sendNotif($product->user_id ?? $product->id_user ?? null, '⚠️ Produk Disembunyikan', 'Produk "' . $product->title . '" telah dinonaktifkan oleh Admin.');
        return back()->with('success', 'Produk berhasil di-takedown.');
    }

    public function deleteProduct(string|int $id)
    {
        Product::where('id_product', $id)->delete();
        return back()->with('success', 'Produk dihapus permanen.');
    }

    /*
    |--------------------------------------------------------------------------
    | 7. KATEGORI JASA
    |--------------------------------------------------------------------------
    */
    public function categories()
    {
        $categories = Category::withCount('products')->latest()->get();
        return view('admin.katalog.kategori_jasa', [
            'categories' => $categories, 'totalKategori' => $categories->count(),
            'kategoriPopuler' => $categories->sortByDesc('products_count')->first(),
            'kategoriNonaktif' => $categories->where('status', 'nonaktif')->count()
        ]);
    }

    public function storeCategory(Request $request)
    {
        Category::create($request->validate(['name' => 'required|string|max:255|unique:categories', 'description' => 'nullable|string', 'status' => 'nullable|in:aktif,nonaktif']));
        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function updateCategory(Request $request, string|int $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validate(['name' => 'required|string|max:255|unique:categories,name,' . $id . ',id_category', 'description' => 'nullable|string', 'status' => 'required|in:aktif,nonaktif']));
        return back()->with('success', 'Kategori diperbarui.');
    }

    public function deleteCategory(string|int $id)
    {
        $cat = Category::findOrFail($id);
        if ($cat->products()->exists()) return back()->with('error', 'Gagal: Kategori masih memiliki produk.');
        $cat->delete();
        return back()->with('success', 'Kategori dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | 8. TRANSAKSI & KEUANGAN
    |--------------------------------------------------------------------------
    */
    public function transactions(Request $request)
    {
        $search = $request->query('search');
        $orders = Order::with(['buyer', 'items.product.seller'])
            ->when($search, fn($q) => $q->whereHas('buyer', fn($qq) => $qq->where('name', 'like', "%$search%")))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.keuangan.riwayat_pesanan', [
            'orders' => $orders, 'totalTransaksi' => Order::count(),
            'totalCommission' => Order::where('payment_status', 'paid')->sum('total_price') * 0.05,
            'sedangDiproses' => Order::whereIn('status', ['pending', 'diproses'])->count(),
            'orderSelesai' => Order::where('status', 'selesai')->count(),
            'dibatalkan' => Order::where('status', 'dibatalkan')->count()
        ]);
    }

    public function exportTransactions()
    {
        $orders = Order::with(['buyer', 'items'])->latest()->get();
        return response()->stream(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Pembeli', 'Total', 'Status Pembayaran', 'Status Order', 'Tanggal']);
            foreach ($orders as $o) fputcsv($handle, [$o->buyer->name ?? '-', $o->total_price, $o->payment_status, $o->status, $o->created_at->format('Y-m-d H:i')]);
            fclose($handle);
        }, 200, ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename=riwayat-pesanan-' . now()->format('Ymd_His') . '.csv']);
    }

    public function transactionDetail(string|int $id)
    {
        return response()->json(Order::with(['buyer', 'items.product.seller'])->findOrFail($id));
    }

    /*
    |--------------------------------------------------------------------------
    | 9. PENARIKAN SALDO
    |--------------------------------------------------------------------------
    */
    public function withdrawals(Request $request)
    {
        $search = $request->query('search');
        $withdrawals = Withdrawal::with('user')
            ->when($search, fn($q) => $q->whereHas('user', fn($qq) => $qq->where('name', 'like', "%$search%"))->orWhere('id_withdrawal', 'like', "%$search%"))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.keuangan.penarikan_saldo', [
            'withdrawals' => $withdrawals, 'menungguDiproses' => Withdrawal::where('status', 'pending')->count(),
            'gagalDitolak' => Withdrawal::where('status', 'rejected')->count(),
            'selesaiBulanIni' => Withdrawal::where('status', 'processed')->whereMonth('processed_at', now()->month)->whereYear('processed_at', now()->year)->sum('amount')
        ]);
    }

    public function processWithdrawal(string|int $id)
    {
        $w = Withdrawal::findOrFail($id);
        $w->update(['status' => 'processed', 'processed_by' => auth()->id(), 'processed_at' => now()]);
        $this->sendNotif($w->user_id ?? $w->id_user ?? null, '💸 Penarikan Saldo Berhasil', 'Penarikan Rp' . number_format($w->amount, 0, ',', '.') . ' berhasil diproses.');
        return back()->with('success', 'Penarikan saldo diproses.');
    }

    public function rejectWithdrawal(Request $request, string|int $id)
    {
        $w = Withdrawal::findOrFail($id);
        $w->update(['status' => 'rejected', 'notes' => $request->notes, 'processed_by' => auth()->id(), 'processed_at' => now()]);
        $this->sendNotif($w->user_id ?? $w->id_user ?? null, '❌ Penarikan Saldo Ditolak', 'Penarikan ditolak. Catatan: ' . ($request->notes ?? 'Data tidak valid.'));
        return back()->with('success', 'Penarikan ditolak.');
    }

    /*
    |--------------------------------------------------------------------------
    | 10. MEMBERSHIP CARD MANAGEMENT
    |--------------------------------------------------------------------------
    */
    public function memberships()
    {
        return view('admin.membership.paket_membership', [
            'memberships' => Membership::withCount('users')->get(),
            'totalPelangganAktif' => User::whereNotNull('id_membership')->count(),
            'diamondCount' => User::whereHas('membership', fn($q) => $q->where('name', 'LIKE', '%Diamond%'))->count(),
            'silverCount'  => User::whereHas('membership', fn($q) => $q->where('name', 'LIKE', '%Silver%'))->count(),
            'bronzeCount'  => User::whereHas('membership', fn($q) => $q->where('name', 'LIKE', '%Bronze%'))->count(),
        ]);
    }

    private function prepareMembershipData(Request $request)
    {
        if ($request->has('price')) $request->merge(['price' => str_replace('.', '', $request->price)]);
        
        $b = [];
        if ($request->filled('max_upload')) $b[] = "Maksimal Upload: {$request->max_upload} karya";
        if ($request->boolean('feat_max_products') && $request->filled('val_max_products')) $b[] = "Batas Jasa/Barang: {$request->val_max_products} item";
        if ($request->boolean('feat_max_ads') && $request->filled('val_max_ads')) $b[] = "Iklan Promosi: {$request->val_max_ads} slot";
        if ($request->boolean('feat_verified_badge')) $b[] = 'Lencana Kreator Terverifikasi';
        if ($request->boolean('feat_priority_cs')) $b[] = 'Dukungan CS Prioritas 24/7';
        if ($request->filled('custom_benefit')) $b[] = $request->custom_benefit;

        foreach ($request->custom_features ?? [] as $feat) {
            if (!empty($feat['name']) && (!isset($feat['checked']) || $feat['checked'])) {
                $b[] = trim($feat['name']) . (!empty($feat['val']) ? ': ' . trim($feat['val']) : '');
            }
        }
        $request->merge(['benefit' => $b ? implode(' | ', array_unique($b)) : 'Fitur standar keanggotaan']);
    }

    public function storeMembership(Request $request)
    {
        $this->prepareMembershipData($request);
        Membership::create($request->validate(['name' => 'required|string|max:255', 'price' => 'required|numeric|min:0', 'duration_days' => 'required|integer|min:1', 'max_upload' => 'required|integer|min:0', 'benefit' => 'required|string']));
        return back()->with('success', 'Paket membership ditambahkan.');
    }

    public function updateMembership(Request $request, string|int $id)
    {
        $this->prepareMembershipData($request);
        Membership::findOrFail($id)->update($request->validate(['name' => 'required|string|max:255', 'price' => 'required|numeric|min:0', 'duration_days' => 'required|integer|min:1', 'max_upload' => 'required|integer|min:0', 'benefit' => 'required|string']));
        return back()->with('success', 'Paket membership diperbarui.');
    }

    public function deleteMembership(string|int $id)
    {
        $m = Membership::findOrFail($id);
        if ($m->users()->exists()) return back()->with('error', 'Paket tidak dapat dihapus karena masih ada pelanggan aktif.');
        $m->delete();
        return back()->with('success', 'Paket dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | 11. PELANGGARAN & BANDING
    |--------------------------------------------------------------------------
    */
    public function pelanggaran()
    {
        return view('admin.sistem.pelanggaran', [
            'reportsUser'   => Report::with(['reporter', 'reportedUser'])->whereNull('product_id')->latest()->paginate(10, ['*'], 'page_user')->withQueryString(),
            'reportsProduk' => Report::with(['reporter', 'product.seller'])->whereNotNull('product_id')->latest()->paginate(10, ['*'], 'page_produk')->withQueryString(),
            'reportsAppeal' => AccountAppeal::with(['user.role', 'reviewer'])->latest()->paginate(10, ['*'], 'page_banding')->withQueryString(),
            'pendingAppealCount' => AccountAppeal::where('status', 'pending')->count()
        ]);
    }

    public function tindakUserPelanggaran(Request $request, string|int $id)
    {
        $req = $request->validate(['action' => 'required|in:peringatan,suspend,abaikan', 'admin_notes' => 'required|string|max:500']);
        $r = Report::findOrFail($id);
        $r->update(['status' => $req['action'] === 'abaikan' ? 'dismissed' : 'reviewed', 'admin_note' => $req['admin_notes'], 'reviewed_at' => now(), 'reviewed_by' => auth()->id()]);

        if ($req['action'] === 'suspend' && $r->reported_user_id) User::where('id_user', $r->reported_user_id)->update(['status' => 'blocked']);
        if ($req['action'] === 'peringatan') $this->sendNotif($r->reported_user_id, '⚠️ Peringatan Laporan', 'Peringatan Admin: ' . $req['admin_notes']);
        
        $this->sendNotif($r->user_id ?? $r->id_user ?? null, 'Status Laporan Anda', 'Telah ditindaklanjuti. Catatan: ' . $req['admin_notes']);
        return back()->with('success', 'Tindakan berhasil diproses.');
    }

    public function tindakProdukPelanggaran(Request $request, string|int $id)
    {
        $req = $request->validate(['action' => 'required|in:peringatan,suspend,abaikan', 'admin_notes' => 'required|string|max:500']);
        $r = Report::findOrFail($id);
        $r->update(['status' => $req['action'] === 'abaikan' ? 'dismissed' : 'reviewed', 'admin_note' => $req['admin_notes'], 'reviewed_at' => now(), 'reviewed_by' => auth()->id()]);

        if ($req['action'] === 'suspend' && $r->product_id) Product::where('id_product', $r->product_id)->update(['status' => 'inactive']);
        if ($req['action'] === 'peringatan') $this->sendNotif($r->product->user_id ?? $r->product->id_user ?? null, '⚠️ Peringatan Produk', 'Peringatan Admin: ' . $req['admin_notes']);
        
        $this->sendNotif($r->user_id ?? $r->id_user ?? null, 'Status Laporan Anda', 'Telah ditindaklanjuti. Catatan: ' . $req['admin_notes']);
        return back()->with('success', 'Tindakan berhasil diproses.');
    }

    public function tindakAppeal(Request $request, string|int $id)
    {
        $req = $request->validate(['action' => 'required|in:setujui,tolak', 'admin_notes' => 'nullable|string|max:500']);
        $a = AccountAppeal::findOrFail($id);
        $u = $a->user;
        $note = $req['admin_notes'];

        if ($req['action'] === 'setujui') {
            $a->update(['status' => 'approved', 'admin_note' => $note ?: 'Disetujui. Akun aktif kembali.', 'reviewed_at' => now(), 'reviewed_by' => auth()->id()]);
            if ($u) {
                $u->update(['status' => 'active', 'suspended_until' => null, 'suspend_reason' => null]);
                $this->sendNotif($u->id_user, '🎉 Banding Disetujui', 'Banding disetujui. ' . ($note ? 'Catatan: ' . $note : ''));
            }
            return back()->with('success', 'Banding disetujui.');
        } 
        
        $a->update(['status' => 'rejected', 'admin_note' => $note ?: 'Banding ditolak.', 'reviewed_at' => now(), 'reviewed_by' => auth()->id()]);
        $this->sendNotif($u?->id_user, '❌ Banding Ditolak', 'Banding ditolak. Catatan: ' . ($note ?: 'Alasan tidak mencukupi.'));
        return back()->with('success', 'Banding ditolak.');
    }

    public function hapusAppeal(string|int $id)
    {
        $a = AccountAppeal::findOrFail($id);
        if ($a->proof_image && Storage::disk('public')->exists($a->proof_image)) Storage::disk('public')->delete($a->proof_image);
        $a->delete();
        return back()->with('success', 'Riwayat banding dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | 12. PROFILE & 13. SECURITY
    |--------------------------------------------------------------------------
    */
    public function profile() { return view('admin.profile', ['admin' => auth()->user()]); }

    public function updateProfile(Request $request)
    {
        $v = $request->validate(['name' => 'required|string|max:255', 'email' => 'required|email|unique:users,email,' . auth()->id() . ',id_user', 'phone' => 'nullable|string', 'password' => 'nullable|min:8']);
        if (!empty($v['password'])) $v['password'] = Hash::make($v['password']);
        else unset($v['password']);
        User::findOrFail(auth()->id())->update($v);
        return back()->with('success', 'Profil Admin diperbarui.');
    }

    public function securityVerifyPage(Request $request)
    {
        if ($request->has('reset')) session()->forget('security_verified_at');
        return session()->has('security_verified_at') ? redirect()->route('admin.security.index') : view('admin.security.verify');
    }

    public function securityProcessVerify(Request $request)
    {
        $request->validate(['password' => 'required', 'pin' => 'required|numeric']);
        if (!Hash::check($request->password, auth()->user()->password)) return back()->with('error', 'Password Salah!');
        if ($request->pin != env('SECURITY_ACCESS_PIN', '123456')) return back()->with('error', 'PIN Salah!');
        
        session(['security_verified_at' => now()]);
        return redirect()->route('admin.security.index')->with('success', 'Akses Keamanan Diberikan.');
    }

    public function securityIndex(Request $request)
    {
        if (!session()->has('security_verified_at')) return redirect()->route('admin.security.verify')->with('warning', 'Verifikasi dahulu.');
        return view('admin.security.index', [
            'normalIps' => IpLog::where('status', 'normal')->latest('last_activity_at')->get(),
            'abnormalIps' => IpLog::where('status', 'abnormal')->latest('last_activity_at')->get(),
            'allowedIps' => AllowedIp::latest()->get(), 'myIp' => $request->ip()
        ]);
    }

    public function securityToggleStatus(string|int $id)
    {
        if (!session()->has('security_verified_at')) return redirect()->route('admin.security.verify');
        $ip = IpLog::findOrFail($id);
        $ip->update(['status' => $ip->status === 'normal' ? 'abnormal' : 'normal', 'reason' => $ip->status === 'normal' ? 'Dibersihkan Admin' : 'Ditandai manual']);
        return back()->with('success', "Status IP {$ip->ip_address} diperbarui.");
    }

    public function securityDestroyLog(string|int $id)
    {
        if (!session()->has('security_verified_at')) return redirect()->route('admin.security.verify');
        IpLog::findOrFail($id)->delete();
        return back()->with('success', 'Log IP dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | 14. OPTIMASI & CACHE
    |--------------------------------------------------------------------------
    */
    public function clearCache()
    {
        $res = [];
        $tasks = [
            'App Cache' => fn() => Artisan::call('cache:clear'), 'Config' => fn() => Artisan::call('config:clear'),
            'Route' => fn() => Artisan::call('route:clear'), 'View' => fn() => Artisan::call('view:clear'),
            'Event' => fn() => Artisan::call('event:clear'),
            'Tabel cache' => fn() => Schema::hasTable('cache') ? DB::table('cache')->delete() : null,
            'Tabel cache_locks' => fn() => Schema::hasTable('cache_locks') ? DB::table('cache_locks')->delete() : null,
            'Session DB' => fn() => Schema::hasTable('sessions') ? DB::table('sessions')->where('last_activity', '<', now()->subMinutes(config('session.lifetime', 120))->getTimestamp())->delete() : null,
            'Failed Jobs' => fn() => Schema::hasTable('failed_jobs') ? DB::table('failed_jobs')->delete() : null,
            'Notif Lama' => fn() => Notification::where('created_at', '<', now()->subMonth())->delete()
        ];
        foreach ($tasks as $name => $task) {
            try { $task(); $res[] = "$name: bersih"; } 
            catch (\Throwable $e) { if ($name !== 'Event') $res[] = "$name: gagal"; }
        }
        $this->sendNotif(null, '🧹 Cache Dibersihkan', 'Admin membersihkan cache aplikasi.');
        return back()->with('success', 'Clear Cache berhasil! ' . implode(' • ', $res));
    }

    public function optimizeApp()
    {
        $res = [];
        try { Artisan::call('optimize:clear'); } catch (\Throwable $e) {}
        
        foreach (['config:cache' => 'Config', 'route:cache' => 'Route', 'view:cache' => 'View'] as $cmd => $name) {
            try { Artisan::call($cmd); $res[] = "$name: di-cache"; } 
            catch (\Throwable $e) { $res[] = "$name: gagal"; }
        }
        return back()->with('success', 'Optimasi selesai! ' . implode(' • ', $res));
    }


        /*
    |--------------------------------------------------------------------------
    | 15. KELOLA NOTIFIKASI (KIRIM MANUAL KE PENGGUNA TERTENTU / SEMUA)
    |--------------------------------------------------------------------------
    */
    public function notifikasi()
    {
        $notifications = Notification::with('targetUser')
            ->latest()
            ->paginate(15);

        // Dropdown daftar SEMUA pengguna (kecuali Admin) untuk dipilih tujuan notifikasi.
        // View tinggal loop $allUsers, otomatis nambah kalau ada pengguna baru daftar.
        $allUsers = User::with('role')
            ->whereHas('role', fn ($q) => $q->where('role_name', '!=', 'admin'))
            ->orderBy('name')
            ->get(['id_user', 'name', 'email', 'id_role']);

        return view('admin.sistem.notifikasi', compact('notifications', 'allUsers'));
    }

    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'target_type' => 'required|in:semua,tertentu',
            'user_id'     => 'nullable|required_if:target_type,tertentu|exists:users,id_user',
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:2000',
        ]);
        $targetUserId = $validated['target_type'] === 'tertentu'
            ? $validated['user_id']
            : null;
        Notification::create([
            'user_id'     => $targetUserId,
            'name'        => $validated['title'],
            'description' => $validated['description'],
            'is_read'     => false,
        ]);
        $message = $targetUserId
            ? 'Notifikasi berhasil dikirim ke pengguna yang dipilih.'
            : 'Notifikasi berhasil dikirim sebagai broadcast ke SEMUA pengguna.';
        return back()->with('success', $message);
    }

    public function deleteNotification(string|int $id)
    {
        Notification::findOrFail($id)->delete();
        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}