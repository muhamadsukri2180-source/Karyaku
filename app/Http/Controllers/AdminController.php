<?php

namespace App\Http\Controllers;

use App\Models\{Product, Order, OrderItem, User, Role, Category, Membership, IdentityVerification, Withdrawal, Report, CustomerService, Notification, IpLog, AllowedIp, AccountAppeal, LoginHistory};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Artisan, Hash, Storage, DB, Schema, Cache};
use Carbon\Carbon;
use ZipArchive;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\{Alignment, Border, Fill, Font, NumberFormat};

class AdminController extends Controller
{
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
            'time'  => $o->created_at, 'color' => 'emerald', 'icon' => 'fa-bag-shopping',
        ]);

        $recentProducts = Product::where('status', 'active')->latest('updated_at')->take(3)->get()->map(fn($p) => [
            'title' => 'Produk Diverifikasi', 'desc' => 'Produk "' . $p->title . '" telah disetujui.',
            'time'  => $p->updated_at, 'color' => 'sky', 'icon' => 'fa-box',
        ]);

        $recentIdentities = IdentityVerification::with('user')->latest()->take(3)->get()->map(fn($iv) => [
            'title' => 'Pengajuan Identitas', 'desc' => 'Kreator "' . ($iv->user->name ?? '-') . '" mengunggah identitas.',
            'time'  => $iv->created_at, 'color' => 'amber', 'icon' => 'fa-id-card',
        ]);

        $recentActivities = $recentOrders->concat($recentProducts)->concat($recentIdentities)
            ->sortByDesc('time')->take(5)->values();

        $isMaintenance = app()->isDownForMaintenance();

        $totalGrossVolume = $totalRevenue;
        $totalVerifikator = User::whereHas('role', fn($q) => $q->where('role_name', 'verifikator'))->count();
        $currentYear      = $year;

        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'monthlySales', 'totalRevenue', 'platformCommission',
            'totalUsers', 'pendingIdentityCount', 'pendingReportsCount', 'chartData', 'recentActivities',
            'isMaintenance', 'year', 'totalGrossVolume', 'totalVerifikator', 'currentYear'
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

        $cacheClearedFile = storage_path('framework/cache_cleared_at.json');
        $lastCacheClearedAt = null;
        if (file_exists($cacheClearedFile)) {
            $clearedData = json_decode(file_get_contents($cacheClearedFile), true);
            if (!empty($clearedData['cleared_at'])) {
                $lastCacheClearedAt = Carbon::parse($clearedData['cleared_at'], 'Asia/Jakarta');
            }
        }

        return view('admin.sistem.maintenance', [
            'isMaintenance' => app()->isDownForMaintenance(),
            'currentMode' => $currentMode,
            'currentEndAt' => $currentEndAt,
            'backups' => $backups,
            'lastCacheClearedAt' => $lastCacheClearedAt,
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
        $safeName = basename($filename);

        // Hanya izinkan file .zip
        if (!preg_match('/^[\w\-]+\.zip$/i', $safeName)) {
            abort(403, 'Nama file tidak valid.');
        }

        $path = storage_path('app/backups/' . $safeName);
        if (!file_exists($path)) abort(404, 'File backup tidak ditemukan.');
        return response()->download($path);
    }

    public function deleteBackup(string $filename)
    {
        Storage::disk('local')->delete('backups/' . basename($filename));
        return back()->with('success', 'File backup berhasil dihapus.');
    }
    public function users(Request $request)
    {
        $search = $request->query('search');
        $users = User::with(['role', 'membership'])
            ->whereHas('role', fn($q) => $q->whereIn('role_name', ['pembeli', 'penjual']))
            ->when($search, fn($q) => $q->where(fn($qq) => $qq->where('name', 'like', "%$search%")->orWhere('phone', 'like', "%$search%")))
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
            $this->sendNotif($user->id_user, 'Akun Diaktifkan Kembali', 'Akun Anda telah diaktifkan kembali oleh Admin.');
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

        $this->sendNotif($user->id_user, 'Status Akun Ditangguhkan', 'Akun Anda dinonaktifkan sementara (' . $durationText . '). Alasan: ' . $reason);
        return back()->with('success', 'Akun "' . $user->name . '" berhasil disuspend (' . $durationText . ').');
    }
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
            $membership = $verif->membership_id ? Membership::find($verif->membership_id) : null;
            $userData = ['id_role' => $role->id_role, 'status' => 'active'];

            if ($membership) {
                $userData['id_membership'] = $membership->id_membership;
                $durationDays = $membership->duration_days ?? 30;
                $isSamePlanActive = ($user->id_membership == $membership->id_membership) && $user->membership_expires_at && $user->membership_expires_at->isFuture();
                $userData['membership_expires_at'] = $isSamePlanActive
                    ? $user->membership_expires_at->copy()->addDays($durationDays)
                    : now()->addDays($durationDays);
            }

            $verif->update(['status' => 'approved', 'verifier_id' => auth()->id(), 'verified_at' => now()]);
            $user->update($userData);
            
            $membershipName = $membership->name ?? 'Paket Penjual';
            $this->sendNotif($user->id_user, 'Paket Penjual / Membership Disetujui', 'Selamat! Verifikasi pendaftaran/pembayaran paket ' . $membershipName . ' Anda telah disetujui.');
            DB::commit();
            return back()->with('success', 'Pengajuan disetujui. Akun penjual / paket membership berhasil diperbarui.');
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
        $this->sendNotif($verif->user_id ?? $verif->id_user ?? null, 'Pendaftaran Penjual Ditolak', 'Pengajuan ditolak. Alasan: ' . ($request->notes ?? 'Dokumen tidak sesuai.'));
        
        return back()->with('success', 'Pengajuan identitas berhasil ditolak.');
    }
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

    public function updateServiceAccount(Request $request, string|int $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id . ',id_user',
            'password' => 'nullable|min:6',
            'status'   => 'required|in:active,blocked',
        ]);

        $data = [
            'name'   => $validated['name'],
            'email'  => $validated['email'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        return back()->with('success', 'Akun Customer Service berhasil diperbarui!');
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
    public function products(Request $request)
    {
        $search = $request->query('search');
        $tab    = $request->query('tab', 'pending');

        $query = Product::with(['category', 'seller'])
            ->when($search, fn($q) => $q->where('title', 'like', "%$search%"));

        if ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'active') {
            $query->where('status', 'active');
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        $pendingCount = Product::where('status', 'pending')->count();
        $activeCount  = Product::where('status', 'active')->count();
        $allCount     = Product::count();

        return view('admin.katalog.daftar_jasa', [
            'products'     => $products,
            'tab'          => $tab,
            'pendingCount' => $pendingCount,
            'activeCount'  => $activeCount,
            'allCount'     => $allCount,
        ]);
    }

    public function approveProduct(string|int $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'active']);
        $this->sendNotif($product->seller_id, 'Produk Disetujui', 'Produk "' . $product->title . '" telah disetujui.');
        return back()->with('success', 'Produk berhasil disetujui.');
    }

    public function takedownProduct(string|int $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'inactive']);
        $this->sendNotif($product->seller_id, 'Produk Disembunyikan', 'Produk "' . $product->title . '" telah dinonaktifkan oleh Admin.');
        return back()->with('success', 'Produk berhasil di-takedown.');
    }

    public function deleteProduct(string|int $id)
    {
        Product::where('id_product', $id)->delete();
        return back()->with('success', 'Produk dihapus permanen.');
    }
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

    private function buildOrderQuery(Request $request)
    {
        $search = trim($request->query('search', ''));
        $status = $request->query('status');

        $query = Order::with(['buyer', 'verifier', 'items.product.seller']);

        if (!empty($search)) {
            $cleanSearch = ltrim(str_ireplace('ORD-', '', $search), '0');
            $query->where(function ($q) use ($search, $cleanSearch) {
                if (is_numeric($cleanSearch) && $cleanSearch > 0) {
                    $q->orWhere('id_order', (int)$cleanSearch);
                }
                $q->orWhere('id_order', 'like', "%{$search}%")
                  ->orWhereHas('buyer', function ($b) use ($search) {
                      $b->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('items.product', function ($p) use ($search) {
                      $p->where('title', 'like', "%{$search}%")
                        ->orWhereHas('seller', function ($s) use ($search) {
                            $s->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                        });
                  });
            });
        }

        if (!empty($status)) {
            if ($status === 'pending_verif') {
                $query->where('payment_status', 'pending_verification');
            } elseif ($status === 'diproses') {
                $query->where('status', 'diproses')->where('payment_status', '!=', 'pending_verification');
            } elseif ($status === 'selesai') {
                $query->where(function($q) {
                    $q->where('status', 'selesai')->orWhere('payment_status', 'paid');
                });
            } elseif ($status === 'dibatalkan') {
                $query->where(function($q) {
                    $q->where('status', 'dibatalkan')->orWhere('payment_status', 'rejected');
                });
            }
        }

        return $query->latest();
    }

    public function transactions(Request $request)
    {
        $orders = $this->buildOrderQuery($request)->paginate(15)->withQueryString();

        $totalTransaksi = Order::count();
        $totalCommission = Order::where('payment_status', 'paid')->sum('total_price') * 0.05;
        $sedangDiproses = Order::whereIn('status', ['pending', 'diproses'])
            ->orWhereIn('payment_status', ['pending_verification', 'pending'])
            ->count();
        $orderSelesai = Order::where('status', 'selesai')
            ->orWhere('payment_status', 'paid')
            ->count();
        $dibatalkan = Order::where('status', 'dibatalkan')
            ->orWhere('payment_status', 'rejected')
            ->count();

        return view('admin.keuangan.riwayat_pesanan', compact(
            'orders', 'totalTransaksi', 'totalCommission', 'sedangDiproses', 'orderSelesai', 'dibatalkan'
        ));
    }

    public function exportTransactions(Request $request)
    {
        $orders = $this->buildOrderQuery($request)->get();

        return response()->stream(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'ID Pesanan',
                'Tanggal Pesanan',
                'Nama Pembeli',
                'Email Pembeli',
                'Produk / Layanan',
                'Kreator / Penjual',
                'Total Nilai (Rp)',
                'Metode Pembayaran',
                'Status Pembayaran',
                'Status Order'
            ]);

            foreach ($orders as $o) {
                $firstItem = $o->items->first();
                $productTitle = $firstItem?->product?->title ?? '-';
                if ($o->items->count() > 1) {
                    $productTitle .= ' (+' . ($o->items->count() - 1) . ' item lainnya)';
                }
                $sellerName = $firstItem?->product?->seller?->name ?? '-';

                fputcsv($handle, [
                    'ORD-' . str_pad($o->id_order, 6, '0', STR_PAD_LEFT),
                    $o->created_at ? $o->created_at->format('d/m/Y H:i') : '-',
                    $o->buyer->name ?? '-',
                    $o->buyer->email ?? '-',
                    $productTitle,
                    $sellerName,
                    (float) $o->total_price,
                    strtoupper($o->payment_method ?? 'TRANSFER'),
                    strtoupper($o->payment_status ?? '-'),
                    strtoupper($o->status ?? '-')
                ]);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=riwayat-pesanan-' . now()->format('Ymd_His') . '.csv',
        ]);
    }

    public function transactionDetail(string|int $id)
    {
        $order = Order::with(['buyer', 'verifier', 'items.product.seller'])->findOrFail($id);

        $proofUrl = null;
        if ($order->payment_proof) {
            if (str_starts_with($order->payment_proof, 'http://') || str_starts_with($order->payment_proof, 'https://')) {
                $proofUrl = $order->payment_proof;
            } else {
                $path = ltrim($order->payment_proof, '/');
                if (str_starts_with($path, 'public/')) $path = preg_replace('/^public\//', '', $path);
                if (str_starts_with($path, 'storage/')) $proofUrl = asset($path);
                else $proofUrl = asset('storage/' . $path);
            }
        }

        $orderData = $order->toArray();
        $orderData['kode_order'] = 'ORD-' . str_pad($order->id_order, 6, '0', STR_PAD_LEFT);
        $orderData['payment_proof_url'] = $proofUrl;
        $orderData['created_at_formatted'] = $order->created_at ? $order->created_at->format('d M Y, H:i') : '-';

        return response()->json($orderData);
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
            'selesaiBulanIni' => Withdrawal::whereIn('status', ['processed', 'completed'])->whereMonth('processed_at', now()->month)->whereYear('processed_at', now()->year)->sum('amount')
        ]);
    }

    public function processWithdrawal(string|int $id)
    {
        $w = Withdrawal::findOrFail($id);
        $w->update(['status' => 'processed', 'processed_by' => auth()->id(), 'processed_at' => now()]);
        $this->sendNotif($w->user_id ?? $w->id_user ?? null, 'Penarikan Saldo Berhasil', 'Penarikan Rp' . number_format($w->amount, 0, ',', '.') . ' berhasil diproses.');
        return back()->with('success', 'Penarikan saldo diproses.');
    }

    public function rejectWithdrawal(Request $request, string|int $id)
    {
        $w = Withdrawal::findOrFail($id);
        $w->update(['status' => 'rejected', 'notes' => $request->notes, 'processed_by' => auth()->id(), 'processed_at' => now()]);
        $this->sendNotif($w->user_id ?? $w->id_user ?? null, 'Penarikan Saldo Ditolak', 'Penarikan ditolak. Catatan: ' . ($request->notes ?? 'Data tidak valid.'));
        return back()->with('success', 'Penarikan ditolak.');
    }
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
        if ($req['action'] === 'peringatan' && $r->reported_user_id) $this->sendNotif($r->reported_user_id, 'Peringatan Laporan', 'Peringatan Admin: ' . $req['admin_notes']);
        
        $this->sendNotif($r->user_id ?? $r->id_user ?? null, 'Status Laporan Anda', 'Telah ditindaklanjuti. Catatan: ' . $req['admin_notes']);
        return back()->with('success', 'Tindakan berhasil diproses.');
    }

    public function tindakProdukPelanggaran(Request $request, string|int $id)
    {
        $req = $request->validate(['action' => 'required|in:peringatan,suspend,abaikan', 'admin_notes' => 'required|string|max:500']);
        $r = Report::with('product')->findOrFail($id);
        $sellerId = $r->reported_user_id ?? ($r->product->seller_id ?? null);

        $r->update([
            'reported_user_id' => $sellerId,
            'status'           => $req['action'] === 'abaikan' ? 'dismissed' : 'reviewed',
            'admin_note'       => $req['admin_notes'],
            'reviewed_at'       => now(),
            'reviewed_by'       => auth()->id(),
        ]);

        if ($req['action'] === 'suspend' && $r->product_id) Product::where('id_product', $r->product_id)->update(['status' => 'inactive']);
        if ($req['action'] === 'peringatan' && $sellerId) $this->sendNotif($sellerId, 'Peringatan Produk', 'Peringatan Admin: ' . $req['admin_notes']);
        
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
                $this->sendNotif($u->id_user, 'Banding Disetujui', 'Banding disetujui. ' . ($note ? 'Catatan: ' . $note : ''));
            }
            return back()->with('success', 'Banding disetujui.');
        } 
        
        $a->update(['status' => 'rejected', 'admin_note' => $note ?: 'Banding ditolak.', 'reviewed_at' => now(), 'reviewed_by' => auth()->id()]);
        $this->sendNotif($u?->id_user, 'Banding Ditolak', 'Banding ditolak. Catatan: ' . ($note ?: 'Alasan tidak mencukupi.'));
        return back()->with('success', 'Banding ditolak.');
    }

    public function hapusAppeal(string|int $id)
    {
        $a = AccountAppeal::findOrFail($id);
        if ($a->proof_image && Storage::disk('public')->exists($a->proof_image)) Storage::disk('public')->delete($a->proof_image);
        $a->delete();
        return back()->with('success', 'Riwayat banding dihapus.');
    }

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
        if (session()->has('security_verified_at')) return redirect()->route('admin.security.index');
        
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        session(['security_captcha_answer' => $num1 + $num2, 'security_captcha_q' => "$num1 + $num2"]);
        
        return view('admin.security.verify');
    }

    public function securityProcessVerify(Request $request)
    {
        $request->validate(['password' => 'required', 'pin' => 'required|numeric', 'captcha' => 'required|numeric']);
        $expectedPassword = config('app.security_access_password', env('SECURITY_ACCESS_PASSWORD', 'KaryakuAman123!'));
        $expectedPin = config('app.security_access_pin', env('SECURITY_ACCESS_PIN', '789101'));

        if ($request->password !== $expectedPassword) return back()->with('error', 'Security Password Salah!');
        if ($request->pin != $expectedPin) return back()->with('error', 'PIN Salah!');
        if ($request->captcha != session('security_captcha_answer')) return back()->with('error', 'Jawaban Keamanan (Captcha) Salah!');
        
        session(['security_verified_at' => now()]);

        // Otomatis masukkan IP Admin yang berhasil verifikasi ke Whitelist
        try {
            AllowedIp::firstOrCreate(
                ['ip_address' => $request->ip()],
                ['label' => 'Admin Verified (' . (auth()->user()->name ?? 'Admin') . ')', 'added_by' => auth()->user()->name ?? 'Admin']
            );
        } catch (\Throwable $e) {}

        return redirect()->route('admin.security.index')->with('success', 'Akses Keamanan Diberikan.');
    }

    public function securityIndex(Request $request)
    {
        if (!session()->has('security_verified_at')) return redirect()->route('admin.security.verify')->with('warning', 'Verifikasi dahulu.');

        $currentAdminIp = $request->ip();

        // 1. Otomatis Whitelist IP Admin yang sedang login agar tidak pernah terdeteksi ancaman
        try {
            AllowedIp::firstOrCreate(
                ['ip_address' => $currentAdminIp],
                [
                    'label'    => 'IP Admin Otomatis (' . (auth()->user()->name ?? 'Administrator') . ')',
                    'added_by' => auth()->user()->name ?? 'System'
                ]
            );
            Cache::put("allowed_ip_{$currentAdminIp}", true, 86400);
        } catch (\Throwable $e) {}

        // 2. Pembersihan otomatis log false-positive (seperti resize window, klik kanan, atau devtools lama)
        try {
            IpLog::where(function($q) {
                $q->where('reason', 'like', '%Resize Window%')
                  ->orWhere('reason', 'like', '%right-click%')
                  ->orWhere('reason', 'like', '%Klik Kanan%')
                  ->orWhere('reason', 'like', '%DevTools Terdeteksi via Resize Window%');
            })->update([
                'status' => 'normal',
                'reason' => 'Aktivitas Normal Pengguna'
            ]);
        } catch (\Throwable $e) {}

        // Bangun lookup: ip_address => username dari LoginHistory (untuk IP yang belum ada user_id)
        $loginHistoryLookup = LoginHistory::whereNotNull('username')
            ->latest()
            ->get()
            ->groupBy('ip_address')
            ->map(fn($items) => $items->first()->username);

        // Daftar IP Admin & Whitelist yang harus dikecualikan dari tabel ancaman
        $adminIps = AllowedIp::pluck('ip_address')->toArray();
        $adminIps[] = $currentAdminIp;
        $adminIps[] = '127.0.0.1';
        $adminIps[] = '::1';
        $adminIps = array_unique(array_filter($adminIps));

        // Periksa apakah kolom user_id ada di database (Self-Healing Migration jika belum ter-migrate)
        $hasUserIdCol = Schema::hasColumn('ip_logs', 'user_id');
        if (!$hasUserIdCol) {
            try {
                Schema::table('ip_logs', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('ip_address');
                });
                $hasUserIdCol = true;
            } catch (\Throwable $e) {}
        }

        // Query log mencurigakan secara aman (tidak akan error meskipun kolom user_id belum ada)
        $suspiciousQuery = IpLog::query();
        if ($hasUserIdCol) {
            $suspiciousQuery->with(['user.role']);
        }

        $suspiciousQuery->whereIn('status', ['abnormal', 'suspicious'])
            ->where('reason', '!=', 'Aktivitas Normal Pengguna')
            ->whereNotIn('ip_address', $adminIps);

        if ($hasUserIdCol) {
            $suspiciousQuery->where(function($q) {
                $q->whereDoesntHave('user.role', fn($rq) => $rq->whereIn('role_name', ['admin', 'verifikator', 'customer_service']))
                  ->orWhereNull('user_id');
            });
        }

        $allSuspiciousLogs = $suspiciousQuery->latest('last_activity_at')->get();

        // Pastikan setiap log tanpa user_id dilengkapi dari LoginHistory jika kolom user_id ada
        if ($hasUserIdCol) {
            foreach ($allSuspiciousLogs as $log) {
                if (!$log->user_id && isset($loginHistoryLookup[$log->ip_address])) {
                    $username = $loginHistoryLookup[$log->ip_address];
                    $foundUser = User::where('name', $username)->orWhere('email', $username)->first();
                    if ($foundUser) {
                        try {
                            IpLog::where('id', $log->id)->update(['user_id' => $foundUser->id_user]);
                            $log->user_id = $foundUser->id_user;
                            $log->setRelation('user', $foundUser->load('role'));
                        } catch (\Throwable $e) {}
                    }
                }
            }
        }

        $normalQuery = IpLog::query();
        if ($hasUserIdCol) $normalQuery->with(['user.role']);
        $normalIps = $normalQuery->where(function($q) {
            $q->where('status', 'normal')->orWhereNull('status');
        })->latest('last_activity_at')->get()->groupBy('ip_address');

        $botQuery = IpLog::query();
        if ($hasUserIdCol) $botQuery->with(['user.role']);
        $botIps = $botQuery->where(function($q) {
            $q->where('reason', 'like', '%Bot%')->orWhere('reason', 'like', '%Spam%')->orWhere('reason', 'like', '%DoS%')->orWhere('reason', 'like', '%Flood%');
        })->latest('last_activity_at')->get()->groupBy('ip_address');

        return view('admin.security.index', [
            'normalIps' => $normalIps,
            'abnormalIps' => $allSuspiciousLogs->groupBy('ip_address'),
            'botIps' => $botIps,
            'loginHistories' => LoginHistory::latest()->simplePaginate(10),
            'loginHistoryLookup' => $loginHistoryLookup,
            'allowedIps' => AllowedIp::latest()->get(), 
            'myIp' => $currentAdminIp
        ]);
    }

    public function securityToggleStatus(Request $request, string|int $id)
    {
        if (!session()->has('security_verified_at')) return redirect()->route('admin.security.verify');
        $ip = IpLog::findOrFail($id);
        $targetIp = $ip->ip_address;
        $currentAdminIp = $request->ip();

        // 1. Temukan Akun Pengguna yang terikat dengan log ini atau IP ini
        $targetUser = null;
        if (!empty($ip->user_id)) {
            $targetUser = User::find($ip->user_id);
        }
        if (!$targetUser) {
            $loginHist = LoginHistory::where('ip_address', $targetIp)->whereNotNull('username')->latest()->first();
            if ($loginHist) {
                $targetUser = User::where('name', $loginHist->username)->orWhere('email', $loginHist->username)->first();
            }
        }

        // Keamanan: Cegah Admin memblokir akun mereka sendiri atau akun staff lainnya
        if (auth()->check() && $targetUser && $targetUser->id_user === auth()->id()) {
            return back()->with('error', 'Tidak dapat memblokir akun Administrator Anda sendiri!');
        }
        if ($targetUser && in_array(strtolower($targetUser->role?->role_name ?? ''), ['admin', 'verifikator', 'customer_service'])) {
            return back()->with('error', 'Tidak dapat memblokir akun Staff atau Admin!');
        }

        // Tentukan status baru (toggle abnormal vs normal)
        $isUserBanned = $targetUser && ($targetUser->status === 'blocked' || Cache::has("banned_user_{$targetUser->id_user}"));
        $isIpBanned   = ($ip->status === 'abnormal') || Cache::has("banned_ip_{$targetIp}");
        $isCurrentlyBanned = $isUserBanned || $isIpBanned;

        $newStatus = $isCurrentlyBanned ? 'normal' : 'abnormal';
        
        $reason = $request->input('reason');
        if (empty($reason)) {
            $reason = ($newStatus === 'abnormal') 
                ? 'Akun dan Alamat IP Anda telah diblokir oleh Administrator sistem.' 
                : 'Aktivitas Normal Pengguna';
        }

        if ($newStatus === 'abnormal') {
            $days = (int) $request->input('freeze_days', 0);
            $hours = (int) $request->input('freeze_hours', 0);
            $seconds = (int) $request->input('freeze_seconds', 0);
            $totalSeconds = ($days * 86400) + ($hours * 3600) + $seconds;

            // 1. Blokir Akun Pengguna Terkait
            if ($targetUser) {
                $targetUser->status = 'blocked';
                $targetUser->suspend_reason = $reason;
                if ($totalSeconds > 0) {
                    $targetUser->suspended_until = now()->addSeconds($totalSeconds);
                    Cache::put("banned_user_{$targetUser->id_user}", $reason, now()->addSeconds($totalSeconds));
                } else {
                    $targetUser->suspended_until = null;
                    Cache::forever("banned_user_{$targetUser->id_user}", $reason);
                }
                $targetUser->save();
            }

            // 2. Blokir semua akun non-admin lain yang login dari IP ini
            $hasUserIdCol = Schema::hasColumn('ip_logs', 'user_id');
            $allLinkedUserIds = [];
            if ($hasUserIdCol) {
                $allLinkedUserIds = IpLog::where('ip_address', $targetIp)->whereNotNull('user_id')->pluck('user_id')->toArray();
            }
            $historyUsernames = LoginHistory::where('ip_address', $targetIp)->whereNotNull('username')->pluck('username')->toArray();
            if (!empty($historyUsernames)) {
                $fromHistory = User::whereIn('name', $historyUsernames)->orWhereIn('email', $historyUsernames)->pluck('id_user')->toArray();
                $allLinkedUserIds = array_unique(array_merge($allLinkedUserIds, $fromHistory));
            }

            if (!empty($allLinkedUserIds)) {
                User::whereIn('id_user', $allLinkedUserIds)
                    ->whereDoesntHave('role', fn($rq) => $rq->whereIn('role_name', ['admin', 'verifikator', 'customer_service']))
                    ->update([
                        'status'         => 'blocked',
                        'suspend_reason' => $reason
                    ]);
                foreach ($allLinkedUserIds as $uId) {
                    if ($totalSeconds > 0) {
                        Cache::put("banned_user_{$uId}", $reason, now()->addSeconds($totalSeconds));
                    } else {
                        Cache::forever("banned_user_{$uId}", $reason);
                    }
                }
            }

            // 3. Update status log IP di database
            IpLog::where('ip_address', $targetIp)->update([
                'status' => 'abnormal',
                'reason' => $reason,
            ]);

            // 4. Simpan ban ke Cache secara global berdasarkan IP (jika bukan IP admin aktif)
            if ($targetIp !== $currentAdminIp && !in_array($targetIp, ['127.0.0.1', '::1'])) {
                if ($totalSeconds > 0) {
                    Cache::put("banned_ip_{$targetIp}", $reason, now()->addSeconds($totalSeconds));
                } else {
                    Cache::forever("banned_ip_{$targetIp}", $reason);
                }
            }

            // 5. Bekukan session yang terdata
            if (!empty($ip->session_id)) {
                if ($totalSeconds > 0) {
                    Cache::put("frozen_session_{$ip->session_id}", true, now()->addSeconds($totalSeconds));
                } else {
                    Cache::forever("frozen_session_{$ip->session_id}", true);
                }
            }

            $userDisplayName = $targetUser ? "Pengguna '{$targetUser->name}' & IP {$targetIp}" : "IP {$targetIp}";
            return back()->with('success', "{$userDisplayName} berhasil DIBLOKIR TOTAL. Pengguna tidak dapat login maupun mengakses platform.");
        } else {
            // BUKA BLOKIR (UNBAN)
            if ($targetUser) {
                $targetUser->status = 'active';
                $targetUser->suspended_until = null;
                $targetUser->suspend_reason = null;
                $targetUser->save();
                Cache::forget("banned_user_{$targetUser->id_user}");
            }

            // Aktifkan kembali akun non-admin yang tadinya terblokir oleh IP ini
            $hasUserIdCol = Schema::hasColumn('ip_logs', 'user_id');
            $allLinkedUserIds = [];
            if ($hasUserIdCol) {
                $allLinkedUserIds = IpLog::where('ip_address', $targetIp)->whereNotNull('user_id')->pluck('user_id')->toArray();
            }
            $historyUsernames = LoginHistory::where('ip_address', $targetIp)->whereNotNull('username')->pluck('username')->toArray();
            if (!empty($historyUsernames)) {
                $fromHistory = User::whereIn('name', $historyUsernames)->orWhereIn('email', $historyUsernames)->pluck('id_user')->toArray();
                $allLinkedUserIds = array_unique(array_merge($allLinkedUserIds, $fromHistory));
            }

            if (!empty($allLinkedUserIds)) {
                User::whereIn('id_user', $allLinkedUserIds)
                    ->where('status', 'blocked')
                    ->whereDoesntHave('role', fn($rq) => $rq->whereIn('role_name', ['admin', 'verifikator', 'customer_service']))
                    ->update([
                        'status'         => 'active',
                        'suspend_reason' => null
                    ]);
                foreach ($allLinkedUserIds as $uId) {
                    Cache::forget("banned_user_{$uId}");
                }
            }

            // Normalkan IP Log
            IpLog::where('ip_address', $targetIp)->update([
                'status' => 'normal',
                'reason' => 'Aktivitas Normal Pengguna',
            ]);

            Cache::forget("banned_ip_{$targetIp}");
            if (!empty($ip->session_id)) {
                Cache::forget("frozen_session_{$ip->session_id}");
            }

            $userDisplayName = $targetUser ? "Pengguna '{$targetUser->name}' & IP {$targetIp}" : "IP {$targetIp}";
            return back()->with('success', "Blokir {$userDisplayName} berhasil DIBUKA. Pengguna dapat mengakses dan login kembali secara normal.");
        }
    }

    public function securityDestroyLog(string|int $id)
    {
        if (!session()->has('security_verified_at')) return redirect()->route('admin.security.verify');
        IpLog::findOrFail($id)->delete();
        return back()->with('success', 'Log IP dihapus.');
    }

    public function securityStoreAllowedIp(Request $request)
    {
        if (!session()->has('security_verified_at')) return redirect()->route('admin.security.verify');

        $validated = $request->validate([
            'ip_address' => 'required|ip|unique:allowed_ips,ip_address',
            'label'      => 'required|string|max:100',
        ]);

        AllowedIp::create([
            'ip_address' => $validated['ip_address'],
            'label'      => $validated['label'],
            'added_by'   => auth()->user()->name ?? 'Admin',
        ]);

        return back()->with('success', "IP {$validated['ip_address']} berhasil ditambahkan ke daftar izin (Whitelist).");
    }

    public function securityDestroyAllowedIp(string|int $id)
    {
        if (!session()->has('security_verified_at')) return redirect()->route('admin.security.verify');

        $allowedIp = AllowedIp::findOrFail($id);
        $ip = $allowedIp->ip_address;
        $allowedIp->delete();

        return back()->with('success', "IP {$ip} berhasil dihapus dari Whitelist.");
    }

    /**
     * Endpoint untuk menerima beacon deteksi DevTools / Inspect Element dari browser.
     * Dipanggil via navigator.sendBeacon() atau fetch() dari script JS di semua halaman.
     * Mencatat IP pengunjung ke ip_logs sebagai 'suspicious' dengan detail metode deteksi.
     */
    public function devtoolsPing(Request $request)
    {
        try {
            $ip = $request->ip();

            // Abaikan jika user adalah admin, verifikator, atau customer service
            if (auth()->check()) {
                $userRole = strtolower(auth()->user()->role?->role_name ?? '');
                if (in_array($userRole, ['admin', 'verifikator', 'customer_service'])) {
                    return response()->json(['ok' => true]);
                }
            }

            // Abaikan jika IP ada di whitelist manual atau localhost
            if (in_array($ip, ['127.0.0.1', '::1'])) {
                return response()->json(['ok' => true]);
            }

            $isWhitelisted = \Illuminate\Support\Facades\Cache::remember("allowed_ip_{$ip}", 60, function () use ($ip) {
                    try { return AllowedIp::where('ip_address', $ip)->exists(); } catch (\Throwable $e) { return false; }
                });

            if ($isWhitelisted) {
                return response()->json(['ok' => true]);
            }

            // Baca body JSON dari sendBeacon (Content-Type: application/json)
            $body = [];
            $contentType = $request->header('Content-Type', '');
            if (str_contains($contentType, 'application/json')) {
                $body = json_decode($request->getContent(), true) ?? [];
            } else {
                $body = $request->all();
            }

            // Tentukan alasan berdasarkan metode deteksi yang dikirim JS
            $method = $body['method'] ?? 'unknown';

            // PENTING: Abaikan metode false-positive (seperti resize window, klik kanan, atau debugger timing)
            if (in_array($method, ['window-resize', 'right-click', 'debugger-timing', 'console-getter'])) {
                return response()->json(['ok' => true]);
            }

            $methodMap = [
                'F12'          => 'Menekan tombol F12 (DevTools)',
                'Ctrl+Shift+I' => 'Menekan Ctrl+Shift+I (DevTools)',
                'Ctrl+Shift+C' => 'Menekan Ctrl+Shift+C (Inspect Element)',
                'Ctrl+Shift+J' => 'Menekan Ctrl+Shift+J (Console DevTools)',
                'Ctrl+Shift+K' => 'Menekan Ctrl+Shift+K (Web Console)',
                'Ctrl+U'       => 'Membuka View Source (Ctrl+U)',
                'unknown'      => 'Shortcut DevTools Terdeteksi',
            ];
            $reason = 'Terdeteksi: ' . ($methodMap[$method] ?? 'Shortcut DevTools Terdeteksi');

            $userAgent = $request->header('User-Agent') ?? 'Unknown';

            // Buat session_id yang konsisten
            $sessionIdCookie = $request->cookie(config('session.cookie'));
            $sessionId = $sessionIdCookie
                ? substr(md5($sessionIdCookie), 0, 16)
                : substr(md5($userAgent . $ip . now()->toDateString()), 0, 16);

            $today = now()->toDateString();
            $hasSessionIdCol = \Illuminate\Support\Facades\Schema::hasColumn('ip_logs', 'session_id');
            $hasUserIdCol    = \Illuminate\Support\Facades\Schema::hasColumn('ip_logs', 'user_id');

            // Cari log hari ini berdasarkan IP + session
            $query = IpLog::where('ip_address', $ip)->whereDate('created_at', $today);
            if ($hasSessionIdCol && $sessionId) {
                $query->where('session_id', $sessionId);
            }
            $ipLog = $query->first();

            if (!$ipLog) {
                $attrs = ['ip_address' => $ip, 'status' => 'normal'];
                if ($hasSessionIdCol && $sessionId) $attrs['session_id'] = $sessionId;
                $ipLog = new IpLog($attrs);
            }

            // Tandai suspicious (jangan override jika sudah abnormal/diblokir)
            if ($ipLog->status !== 'abnormal') {
                $ipLog->status = 'suspicious';
                $ipLog->reason = $reason;
            }

            $referer = $request->header('Referer', 'N/A');
            $ipLog->user_agent       = substr($userAgent, 0, 255);
            $ipLog->last_activity    = substr("[DevTools] {$referer}", 0, 500);
            $ipLog->request_count    = ($ipLog->request_count ?? 0) + 1;
            $ipLog->last_activity_at = now();

            // Hubungkan dengan akun user yang sedang login
            if ($hasUserIdCol) {
                if (auth()->check()) {
                    $ipLog->user_id = auth()->id();
                } elseif (!$ipLog->user_id) {
                    // Fallback: cari dari riwayat login berdasarkan IP
                    $loginHist = LoginHistory::where('ip_address', $ip)
                        ->whereNotNull('username')
                        ->latest()
                        ->first();
                    if ($loginHist) {
                        $foundUser = User::where('name', $loginHist->username)
                            ->orWhere('email', $loginHist->username)
                            ->first();
                        if ($foundUser) {
                            $ipLog->user_id = $foundUser->id_user;
                        }
                    }
                }
            }

            try {
                $ipLog->save();
            } catch (\Throwable $saveEx) {
                // Fallback: hosting lama mungkin belum punya enum 'suspicious'
                if ($ipLog->status === 'suspicious') {
                    $ipLog->status = 'normal';
                    try { $ipLog->save(); } catch (\Throwable $e2) {}
                }
            }

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('DevTools ping error: ' . $e->getMessage());
        }

        return response()->json(['ok' => true]);
    }

    public function clearCache(Request $request)
    {
        $step = $request->input('step');

        if ($step) {
            try {
                switch ($step) {
                    case 'app':
                        Artisan::call('cache:clear');
                        if (Schema::hasTable('cache')) DB::table('cache')->delete();
                        if (Schema::hasTable('cache_locks')) DB::table('cache_locks')->delete();
                        return response()->json(['success' => true, 'step' => 'app', 'name' => 'App Cache', 'label' => 'Bersih']);

                    case 'config':
                        Artisan::call('config:clear');
                        return response()->json(['success' => true, 'step' => 'config', 'name' => 'Config Cache', 'label' => 'Bersih']);

                    case 'route':
                        Artisan::call('route:clear');
                        return response()->json(['success' => true, 'step' => 'route', 'name' => 'Route Cache', 'label' => 'Bersih']);

                    case 'view':
                        Artisan::call('view:clear');
                        return response()->json(['success' => true, 'step' => 'view', 'name' => 'View Cache', 'label' => 'Bersih']);

                    case 'event':
                        Artisan::call('event:clear');
                        if (Schema::hasTable('sessions')) {
                            DB::table('sessions')
                                ->where('last_activity', '<', now()->subMinutes(config('session.lifetime', 120))->getTimestamp())
                                ->delete();
                        }
                        return response()->json(['success' => true, 'step' => 'event', 'name' => 'Event Cache', 'label' => 'Bersih']);

                    case 'finish':
                        $now = now('Asia/Jakarta');
                        file_put_contents(storage_path('framework/cache_cleared_at.json'), json_encode([
                            'cleared_at' => $now->toIso8601String(),
                            'by' => auth()->user()->name ?? 'Admin',
                        ], JSON_PRETTY_PRINT));

                        try {
                            $this->sendNotif(null, '🧹 Cache Dibersihkan', 'Admin membersihkan cache aplikasi.');
                        } catch (\Throwable $e) {}

                        return response()->json([
                            'success' => true,
                            'step' => 'finish',
                            'cleared_at' => $now->toIso8601String(),
                            'cleared_at_formatted' => $now->translatedFormat('d M Y, H:i') . ' WIB',
                            'message' => 'Cache aplikasi berhasil dibersihkan sepenuhnya.'
                        ]);

                    default:
                        return response()->json(['success' => false, 'message' => 'Step tidak dikenali.'], 400);
                }
            } catch (\Throwable $e) {
                return response()->json(['success' => false, 'step' => $step, 'message' => $e->getMessage()], 500);
            }
        }

        $res = [];
        $tasks = [
            'App Cache' => function () {
                Artisan::call('cache:clear');
                if (Schema::hasTable('cache')) DB::table('cache')->delete();
                if (Schema::hasTable('cache_locks')) DB::table('cache_locks')->delete();
            },
            'Config Cache' => fn() => Artisan::call('config:clear'),
            'Route Cache' => fn() => Artisan::call('route:clear'),
            'View Cache' => fn() => Artisan::call('view:clear'),
            'Event Cache' => function () {
                Artisan::call('event:clear');
                if (Schema::hasTable('sessions')) {
                    DB::table('sessions')
                        ->where('last_activity', '<', now()->subMinutes(config('session.lifetime', 120))->getTimestamp())
                        ->delete();
                }
            },
        ];

        foreach ($tasks as $name => $task) {
            try {
                $task();
                $res[] = "$name: bersih";
            } catch (\Throwable $e) {
                if ($name !== 'Event Cache') $res[] = "$name: gagal";
            }
        }

        $now = now('Asia/Jakarta');
        file_put_contents(storage_path('framework/cache_cleared_at.json'), json_encode([
            'cleared_at' => $now->toIso8601String(),
            'by' => auth()->user()->name ?? 'Admin',
        ], JSON_PRETTY_PRINT));

        try {
            $this->sendNotif(null, '🧹 Cache Dibersihkan', 'Admin membersihkan cache aplikasi.');
        } catch (\Throwable $e) {}

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cache berhasil dibersihkan',
                'cleared_at_formatted' => $now->translatedFormat('d M Y, H:i') . ' WIB',
                'results' => $res,
            ]);
        }

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
    
    public function notifikasi()
    {
        $notifications = Notification::with('targetUser')
            ->latest()
            ->paginate(15);

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

    private function getFinancialReportDateRange(Request $request): array
    {
        $filterType = $request->input('filter_type', 'bulanan'); // 'bulanan', 'mingguan', 'custom'

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $launchYear  = 2026;
        $launchMonth = 9; // Launching di bulan September 2026
        $currentYear = (int) now()->year;
        $currentMonth = (int) now()->month;
        $maxYear     = max($launchYear, $currentYear);

        if ($filterType === 'mingguan') {
            $startDate = $request->filled('start_date')
                ? Carbon::parse($request->start_date)->startOfDay()
                : now()->subDays(6)->startOfDay();
            $endDate = $request->filled('end_date')
                ? Carbon::parse($request->end_date)->endOfDay()
                : now()->endOfDay();
            $month = (int) $startDate->month;
            $year  = (int) $startDate->year;
            $periodLabel = 'Mingguan (' . $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y') . ')';
        } elseif ($filterType === 'custom') {
            $startDate = $request->filled('start_date')
                ? Carbon::parse($request->start_date)->startOfDay()
                : now()->startOfMonth();
            $endDate = $request->filled('end_date')
                ? Carbon::parse($request->end_date)->endOfDay()
                : now()->endOfDay();
            $month = (int) $startDate->month;
            $year  = (int) $startDate->year;
            $periodLabel = 'Periode ' . $startDate->format('d M Y') . ' s/d ' . $endDate->format('d M Y');
        } else {
            $filterType = 'bulanan';
            $inputMonth = (int) $request->input('month', $currentMonth);
            $inputYear  = (int) $request->input('year', $currentYear);

            $year  = min(max($inputYear, $launchYear), $maxYear);

            // Batas minimal dan maksimal bulan tergantung dari tahun yang dipilih
            $minMonth = ($year === $launchYear) ? $launchMonth : 1;
            $maxMonth = ($year === $currentYear) ? $currentMonth : 12;

            $month = min(max($inputMonth, $minMonth), $maxMonth);

            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            $periodLabel = ($monthNames[$month] ?? 'Bulan ' . $month) . ' ' . $year;
        }

        $currentMonthCarbon = Carbon::createFromDate($year, $month, 1);
        $prevCarbon = $currentMonthCarbon->copy()->subMonth();
        $nextCarbon = $currentMonthCarbon->copy()->addMonth();

        // Cek prev/next berdasarkan rentang valid (Mulai Sept 2026 - Waktu Sekarang Realtime)
        $hasPrev = !($prevCarbon->year < $launchYear || ($prevCarbon->year === $launchYear && $prevCarbon->month < $launchMonth));
        $hasNext = !($nextCarbon->year > $currentYear || ($nextCarbon->year === $currentYear && $nextCarbon->month > $currentMonth));

        $availableYears = range($launchYear, $maxYear);

        // Hanya mengembalikan nama bulan yang valid untuk tahun yang dipilih ke UI dropdown
        $availableMonthsUI = [];
        $uiMinMonth = ($year === $launchYear) ? $launchMonth : 1;
        $uiMaxMonth = ($year === $currentYear) ? $currentMonth : 12;
        
        for ($m = $uiMinMonth; $m <= $uiMaxMonth; $m++) {
            $availableMonthsUI[$m] = $monthNames[$m];
        }

        return [
            'filter_type'     => $filterType,
            'month'           => $month,
            'year'            => $year,
            'month_name'      => $monthNames[$month] ?? 'Bulan ' . $month,
            'period_label'    => $periodLabel,
            'start_date'      => $startDate,
            'end_date'        => $endDate,
            'prev_month'      => $prevCarbon->month,
            'prev_year'       => $prevCarbon->year,
            'next_month'      => $nextCarbon->month,
            'next_year'       => $nextCarbon->year,
            'has_prev'        => $hasPrev,
            'has_next'        => $hasNext,
            'available_years' => $availableYears,
            'month_names'     => $availableMonthsUI,
        ];
    }

    public function laporanKeuangan(Request $request)
    {
        $dateRange = $this->getFinancialReportDateRange($request);
        $startDate = $dateRange['start_date'];
        $endDate   = $dateRange['end_date'];
        $status    = $request->input('status', 'all');

        $orderQuery = Order::with(['buyer', 'items.product.seller'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($status !== 'all' && !empty($status)) {
            $orderQuery->where('payment_status', $status);
        }

        $orders = $orderQuery->latest('created_at')->get();

        $withdrawals = Withdrawal::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest('created_at')
            ->get();

        $totalPemasukan          = (float) $orders->where('payment_status', 'paid')->sum('total_price');
        $totalKomisiPlatform     = (float) ($totalPemasukan * 0.05); // Komisi 5% platform
        $totalOrdersPaid         = (int) $orders->where('payment_status', 'paid')->count();
        $totalOrdersUnpaid       = (int) $orders->where('payment_status', 'unpaid')->count();
        $totalOrdersFailed       = (int) $orders->whereIn('payment_status', ['failed', 'dibatalkan', 'expired'])->count();
        $totalOrdersCount        = (int) $orders->count();
        
        $totalPenarikanDisetujui = (float) $withdrawals->whereIn('status', ['approved', 'selesai', 'success', 'processed'])->sum('amount');
        $totalPenarikanPending   = (float) $withdrawals->where('status', 'pending')->sum('amount');
        $totalPenarikanDitolak   = (float) $withdrawals->where('status', 'rejected')->sum('amount');
        $countPenarikanDisetujui = (int) $withdrawals->whereIn('status', ['approved', 'selesai', 'success', 'processed'])->count();
        $countPenarikanPending   = (int) $withdrawals->where('status', 'pending')->count();

        $saldoBersih             = (float) ($totalPemasukan - $totalPenarikanDisetujui);
        $rataRataTransaksi       = $totalOrdersPaid > 0 ? ($totalPemasukan / $totalOrdersPaid) : 0;
        $successRate             = $totalOrdersCount > 0 ? round(($totalOrdersPaid / $totalOrdersCount) * 100, 1) : 0;

        $daysInPeriod = $startDate->diffInDays($endDate) + 1;
        $dailyBreakdown = [];
        $chartLabels = [];
        $chartInflow = [];
        $chartOutflow = [];
        $chartOrderCounts = [];

        for ($i = 0; $i < $daysInPeriod; $i++) {
            $dayCarbon = $startDate->copy()->addDays($i);
            $dayStart = $dayCarbon->copy()->startOfDay();
            $dayEnd = $dayCarbon->copy()->endOfDay();
            $dateString = $dayCarbon->format('Y-m-d');
            $dayLabel = $dayCarbon->format('d M');

            $dayOrders = $orders->filter(function ($order) use ($dayStart, $dayEnd) {
                return $order->created_at >= $dayStart && $order->created_at <= $dayEnd;
            });
            $dayPaidOrders = $dayOrders->where('payment_status', 'paid');
            $dayInflow = (float) $dayPaidOrders->sum('total_price');
            $dayCommission = (float) ($dayInflow * 0.05);

            $dayWithdrawals = $withdrawals->filter(function ($w) use ($dayStart, $dayEnd) {
                return $w->created_at >= $dayStart && $w->created_at <= $dayEnd;
            });
            $dayOutflow = (float) $dayWithdrawals->whereIn('status', ['approved', 'selesai', 'success', 'processed'])->sum('amount');

            $dailyBreakdown[] = [
                'date'         => $dateString,
                'formatted'    => $dayCarbon->translatedFormat('d F Y') ?: $dayCarbon->format('d M Y'),
                'day_num'      => $dayCarbon->format('d'),
                'day_name'     => $dayCarbon->format('l'),
                'order_count'  => $dayPaidOrders->count(),
                'inflow'       => $dayInflow,
                'commission'   => $dayCommission,
                'outflow'      => $dayOutflow,
                'net'          => $dayInflow - $dayOutflow,
            ];

            $chartLabels[] = $dayCarbon->format('d');
            $chartInflow[] = $dayInflow;
            $chartOutflow[] = $dayOutflow;
            $chartOrderCounts[] = $dayPaidOrders->count();
        }

        $chartData = [
            'labels'      => $chartLabels,
            'inflow'      => $chartInflow,
            'outflow'     => $chartOutflow,
            'orderCounts' => $chartOrderCounts,
        ];

        $summary = [
            'filter_type'               => $dateRange['filter_type'],
            'month'                     => $dateRange['month'],
            'year'                      => $dateRange['year'],
            'month_name'                => $dateRange['month_name'],
            'period_label'              => $dateRange['period_label'],
            'start_date'                => $startDate->format('Y-m-d'),
            'end_date'                  => $endDate->format('Y-m-d'),
            'total_pemasukan'           => $totalPemasukan,
            'total_komisi_platform'     => $totalKomisiPlatform,
            'total_penarikan_disetujui' => $totalPenarikanDisetujui,
            'total_penarikan_pending'   => $totalPenarikanPending,
            'total_penarikan_ditolak'   => $totalPenarikanDitolak,
            'saldo_bersih'              => $saldoBersih,
            'total_orders_paid'         => $totalOrdersPaid,
            'total_orders_unpaid'       => $totalOrdersUnpaid,
            'total_orders_failed'       => $totalOrdersFailed,
            'total_orders_count'        => $totalOrdersCount,
            'count_penarikan_disetujui' => $countPenarikanDisetujui,
            'count_penarikan_pending'   => $countPenarikanPending,
            'rata_rata_transaksi'       => $rataRataTransaksi,
            'success_rate'              => $successRate,
        ];

        if ($request->wantsJson() || $request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status'         => 'success',
                'summary'        => $summary,
                'orders'         => $orders,
                'withdrawals'    => $withdrawals,
                'dailyBreakdown' => $dailyBreakdown,
                'chartData'      => $chartData,
            ]);
        }

        return view('admin.keuangan.laporan_keuangan', compact('summary', 'orders', 'withdrawals', 'dateRange', 'dailyBreakdown', 'chartData'));
    }

    public function exportLaporanKeuanganExcel(Request $request)
    {
        $dateRange = $this->getFinancialReportDateRange($request);
        $startDate = $dateRange['start_date'];
        $endDate   = $dateRange['end_date'];
        $status    = $request->input('status', 'all');

        $orderQuery = Order::with(['buyer', 'items.product.seller'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($status !== 'all' && !empty($status)) {
            $orderQuery->where('payment_status', $status);
        }

        $orders = $orderQuery->latest('created_at')->get();

        $withdrawals = Withdrawal::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest('created_at')
            ->get();

        $totalPemasukan          = (float) $orders->where('payment_status', 'paid')->sum('total_price');
        $totalKomisiPlatform     = (float) ($totalPemasukan * 0.05);
        $totalPenarikanDisetujui = (float) $withdrawals->whereIn('status', ['approved', 'selesai', 'success', 'processed'])->sum('amount');
        $saldoBersih             = (float) ($totalPemasukan - $totalPenarikanDisetujui);
        $totalOrdersPaid         = (int) $orders->where('payment_status', 'paid')->count();
        $totalOrdersCount        = (int) $orders->count();

        if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            $monthClean = str_replace(' ', '_', $dateRange['month_name']);
            $filename = 'Laporan_Keuangan_Karyaku_' . $monthClean . '_' . $dateRange['year'] . '.csv';

            return response()->streamDownload(function () use ($orders) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, ['Tanggal & Waktu', 'Pembeli', 'Total Harga (Rp)', 'Komisi Platform 5% (Rp)', 'Status Pembayaran']);
                foreach ($orders as $order) {
                    $total = (float) $order->total_price;
                    fputcsv($file, [
                        $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-',
                        $order->buyer->name ?? 'Pembeli',
                        $total,
                        $total * 0.05,
                        strtoupper($order->payment_status ?? 'PENDING'),
                    ]);
                }
                fclose($file);
            }, $filename, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ]);
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Segoe UI')->setSize(10);

        $sheetOrders = $spreadsheet->getActiveSheet();
        $sheetOrders->setTitle('Transaksi Penjualan');
        $sheetOrders->setShowGridLines(true);

        $sheetOrders->mergeCells('A1:G1');
        $sheetOrders->setCellValue('A1', 'LAPORAN KEUANGAN KARYAKU MARKETPLACE');
        $sheetOrders->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 15, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0B3D62']]
        ]);
        $sheetOrders->getRowDimension(1)->setRowHeight(38);

        $sheetOrders->mergeCells('A2:G2');
        $sheetOrders->setCellValue('A2', 'Periode: ' . $dateRange['period_label'] . ' (' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y') . ')  |  Dicetak pada: ' . now()->format('d/m/Y H:i') . ' WIB');
        $sheetOrders->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0284C7']]
        ]);
        $sheetOrders->getRowDimension(2)->setRowHeight(24);

        $sheetOrders->mergeCells('A4:B4');
        $sheetOrders->setCellValue('A4', 'TOTAL PEMASUKAN (LUNAS)');
        $sheetOrders->setCellValue('C4', 'KOMISI PLATFORM (5%)');
        $sheetOrders->mergeCells('D4:E4');
        $sheetOrders->setCellValue('D4', 'PENARIKAN SALDO PENJUAL');
        $sheetOrders->mergeCells('F4:G4');
        $sheetOrders->setCellValue('F4', 'SALDO BERSIH (NET INFLOW)');

        $sheetOrders->mergeCells('A5:B5');
        $sheetOrders->setCellValue('A5', $totalPemasukan);
        $sheetOrders->setCellValue('C5', $totalKomisiPlatform);
        $sheetOrders->mergeCells('D5:E5');
        $sheetOrders->setCellValue('D5', $totalPenarikanDisetujui);
        $sheetOrders->mergeCells('F5:G5');
        $sheetOrders->setCellValue('F5', $saldoBersih);

        $sheetOrders->getStyle('A4:B4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '065F46']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1FAE5']]
        ]);
        $sheetOrders->getStyle('A5:B5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '065F46']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ECFDF5']],
            'numberFormat' => ['formatCode' => '"Rp "#,##0']
        ]);

        $sheetOrders->getStyle('C4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '0369A1']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0F2FE']]
        ]);
        $sheetOrders->getStyle('C5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '0369A1']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F9FF']],
            'numberFormat' => ['formatCode' => '"Rp "#,##0']
        ]);

        $sheetOrders->getStyle('D4:E4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '92400E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF3C7']]
        ]);
        $sheetOrders->getStyle('D5:E5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '92400E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBEB']],
            'numberFormat' => ['formatCode' => '"Rp "#,##0']
        ]);

        $sheetOrders->getStyle('F4:G4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '4338CA']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EDE9FE']]
        ]);
        $sheetOrders->getStyle('F5:G5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '4338CA']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F3FF']],
            'numberFormat' => ['formatCode' => '"Rp "#,##0']
        ]);

        $sheetOrders->getStyle('A4:G5')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]]
        ]);
        $sheetOrders->getRowDimension(4)->setRowHeight(20);
        $sheetOrders->getRowDimension(5)->setRowHeight(28);

        $sheetOrders->mergeCells('A7:G7');
        $sheetOrders->setCellValue('A7', 'RINCIAN TRANSAKSI PENJUALAN (ORDERS)');
        $sheetOrders->getStyle('A7')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '0F172A']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheetOrders->getRowDimension(7)->setRowHeight(22);

        $orderHeaders = [
            'A8' => 'Tanggal & Waktu',
            'B8' => 'Nama Pembeli',
            'C8' => 'Rincian Produk / Layanan',
            'D8' => 'Status Pembayaran',
            'E8' => 'Status Pesanan',
            'F8' => 'Total Transaksi',
            'G8' => 'Komisi Platform (5%)'
        ];

        foreach ($orderHeaders as $cell => $text) {
            $sheetOrders->setCellValue($cell, $text);
        }

        $sheetOrders->getStyle('A8:G8')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0284C7']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '0369A1']]]
        ]);
        $sheetOrders->getRowDimension(8)->setRowHeight(26);

        $rowOrder = 9;
        $noOrder = 1;

        if ($orders->count() > 0) {
            foreach ($orders as $o) {
                $itemList = $o->items->map(function ($item) {
                    return ($item->product->title ?? 'Item') . ' (' . $item->quantity . 'x)';
                })->implode(', ');

                $isEven = ($noOrder % 2 === 0);
                $rowBg = $isEven ? 'F8FAFC' : 'FFFFFF';

                $sheetOrders->setCellValue('A' . $rowOrder, $o->created_at ? $o->created_at->format('d/m/Y H:i') : '-');
                $sheetOrders->setCellValue('B' . $rowOrder, $o->buyer->name ?? 'User #' . $o->buyer_id);
                $sheetOrders->setCellValue('C' . $rowOrder, $itemList ?: '-');
                
                $paymentStatusIndo = match (strtolower($o->payment_status)) {
                    'paid' => 'LUNAS',
                    'unpaid' => 'BELUM BAYAR',
                    'pending' => 'MENUNGGU',
                    'failed' => 'GAGAL',
                    'expired' => 'KEDALUWARSA',
                    'dibatalkan' => 'DIBATALKAN',
                    default => strtoupper($o->payment_status)
                };
                $sheetOrders->setCellValue('D' . $rowOrder, $paymentStatusIndo);

                $orderStatusIndo = match (strtolower($o->status)) {
                    'selesai' => 'SELESAI',
                    'success' => 'BERHASIL',
                    'pending' => 'MENUNGGU',
                    'failed' => 'GAGAL',
                    'canceled' => 'DIBATALKAN',
                    'processed' => 'DIPROSES',
                    default => strtoupper($o->status)
                };
                $sheetOrders->setCellValue('E' . $rowOrder, $orderStatusIndo);
                
                $sheetOrders->setCellValue('F' . $rowOrder, (float) $o->total_price);
                $sheetOrders->setCellValue('G' . $rowOrder, (float) ($o->total_price * 0.05));

                // Basic Row Styling
                $sheetOrders->getStyle('A' . $rowOrder . ':G' . $rowOrder)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $rowBg]],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
                ]);

                // Alignment & Format Kolom
                $sheetOrders->getStyle('A' . $rowOrder)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheetOrders->getStyle('D' . $rowOrder)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheetOrders->getStyle('E' . $rowOrder)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $sheetOrders->getStyle('F' . $rowOrder)->getNumberFormat()->setFormatCode('"Rp "#,##0');
                $sheetOrders->getStyle('G' . $rowOrder)->getNumberFormat()->setFormatCode('"Rp "#,##0');

                // Pewarnaan Badge Status Pembayaran
                $payStatus = strtolower($o->payment_status);
                if ($payStatus === 'paid' || $payStatus === 'lunas') {
                    $sheetOrders->getStyle('D' . $rowOrder)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '166534']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DCFCE7']]
                    ]);
                } elseif ($payStatus === 'unpaid' || $payStatus === 'pending') {
                    $sheetOrders->getStyle('D' . $rowOrder)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '854D0E']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF9C3']]
                    ]);
                } else {
                    $sheetOrders->getStyle('D' . $rowOrder)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '991B1B']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEE2E2']]
                    ]);
                }

                $sheetOrders->getRowDimension($rowOrder)->setRowHeight(22);
                $rowOrder++;
                $noOrder++;
            }
        } else {
            $sheetOrders->mergeCells('A' . $rowOrder . ':G' . $rowOrder);
            $sheetOrders->setCellValue('A' . $rowOrder, 'Tidak ada data transaksi pada periode ini.');
            $sheetOrders->getStyle('A' . $rowOrder)->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => '64748B']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]]
            ]);
            $sheetOrders->getRowDimension($rowOrder)->setRowHeight(24);
            $rowOrder++;
        }

        // Baris Total Transaksi
        $sheetOrders->mergeCells('A' . $rowOrder . ':E' . $rowOrder);
        $sheetOrders->setCellValue('A' . $rowOrder, 'TOTAL TRANSAKSI KESELURUHAN:');
        if ($orders->count() > 0) {
            $sheetOrders->setCellValue('F' . $rowOrder, '=SUM(F8:F' . ($rowOrder - 1) . ')');
            $sheetOrders->setCellValue('G' . $rowOrder, '=SUM(G8:G' . ($rowOrder - 1) . ')');
        } else {
            $sheetOrders->setCellValue('F' . $rowOrder, 0);
            $sheetOrders->setCellValue('G' . $rowOrder, 0);
        }

        $sheetOrders->getStyle('A' . $rowOrder . ':G' . $rowOrder)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '0F172A']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E8F0']],
            'borders' => [
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '94A3B8']],
                'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '0F172A']],
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]
            ],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheetOrders->getStyle('A' . $rowOrder)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheetOrders->getStyle('F' . $rowOrder)->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheetOrders->getStyle('G' . $rowOrder)->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheetOrders->getRowDimension($rowOrder)->setRowHeight(26);

        // Auto-fit Columns
        foreach (range('A', 'G') as $col) {
            $sheetOrders->getColumnDimension($col)->setAutoSize(true);
        }
        $sheetOrders->getColumnDimension('C')->setAutoSize(false)->setWidth(35);

        $sheetWd = $spreadsheet->createSheet();
        $sheetWd->setTitle('Penarikan Saldo');
        $sheetWd->setShowGridLines(true);

        // Header Banner Penarikan (Teal Theme)
        $sheetWd->mergeCells('A1:H1');
        $sheetWd->setCellValue('A1', 'DETAIL PENARIKAN SALDO PENJUAL (WITHDRAWALS)');
        $sheetWd->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F766E']]
        ]);
        $sheetWd->getRowDimension(1)->setRowHeight(36);

        $sheetWd->mergeCells('A2:H2');
        $sheetWd->setCellValue('A2', 'Periode: ' . $dateRange['period_label'] . '  |  Total Penarikan Disetujui: Rp ' . number_format($totalPenarikanDisetujui, 0, ',', '.'));
        $sheetWd->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D9488']]
        ]);
        $sheetWd->getRowDimension(2)->setRowHeight(22);

        // Header Kolom Tabel Penarikan (Row 4)
        $wdHeaders = [
            'A4' => 'Tanggal Pengajuan',
            'B4' => 'Nama Penjual',
            'C4' => 'Nama Bank',
            'D4' => 'No. Rekening',
            'E4' => 'Atas Nama Rekening',
            'F4' => 'Nominal Penarikan',
            'G4' => 'Tanggal Diproses',
            'H4' => 'Status'
        ];

        foreach ($wdHeaders as $cell => $text) {
            $sheetWd->setCellValue($cell, $text);
        }

        $sheetWd->getStyle('A4:H4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F766E']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '115E59']]]
        ]);
        $sheetWd->getRowDimension(4)->setRowHeight(26);

        // Data Rows Penarikan
        $rowWd = 5;
        $noWd = 1;

        if ($withdrawals->count() > 0) {
            foreach ($withdrawals as $w) {
                $isEven = ($noWd % 2 === 0);
                $rowBg = $isEven ? 'F0FDFA' : 'FFFFFF';

                $sheetWd->setCellValue('A' . $rowWd, $w->created_at ? $w->created_at->format('d/m/Y H:i') : '-');
                $sheetWd->setCellValue('B' . $rowWd, $w->user->name ?? 'Penjual #' . $w->user_id);
                $sheetWd->setCellValue('C' . $rowWd, strtoupper($w->bank_name ?? '-'));
                $sheetWd->setCellValueExplicit('D' . $rowWd, (string) $w->bank_account_number, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheetWd->setCellValue('E' . $rowWd, $w->bank_account_name ?? '-');
                $sheetWd->setCellValue('F' . $rowWd, (float) $w->amount);
                $sheetWd->setCellValue('G' . $rowWd, $w->processed_at ? $w->processed_at->format('d/m/Y H:i') : '-');
                
                $wdStatusIndo = match (strtolower($w->status)) {
                    'pending' => 'MENUNGGU',
                    'approved' => 'DISETUJUI',
                    'processed' => 'DIPROSES',
                    'success' => 'BERHASIL',
                    'selesai' => 'SELESAI',
                    'rejected' => 'DITOLAK',
                    default => strtoupper($w->status)
                };
                $sheetWd->setCellValue('H' . $rowWd, $wdStatusIndo);

                $sheetWd->getStyle('A' . $rowWd . ':H' . $rowWd)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $rowBg]],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCFBF1']]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
                ]);

                $sheetWd->getStyle('A' . $rowWd)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheetWd->getStyle('C' . $rowWd)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheetWd->getStyle('D' . $rowWd)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheetWd->getStyle('G' . $rowWd)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheetWd->getStyle('H' . $rowWd)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $sheetWd->getStyle('F' . $rowWd)->getNumberFormat()->setFormatCode('"Rp "#,##0');

                $wStatus = strtolower($w->status);
                if (in_array($wStatus, ['processed', 'approved', 'selesai', 'success'])) {
                    $sheetWd->getStyle('H' . $rowWd)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '166534']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DCFCE7']]
                    ]);
                } elseif ($wStatus === 'pending') {
                    $sheetWd->getStyle('H' . $rowWd)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '854D0E']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF9C3']]
                    ]);
                } else {
                    $sheetWd->getStyle('H' . $rowWd)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '991B1B']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEE2E2']]
                    ]);
                }

                $sheetWd->getRowDimension($rowWd)->setRowHeight(22);
                $rowWd++;
                $noWd++;
            }
        } else {
            $sheetWd->mergeCells('A' . $rowWd . ':H' . $rowWd);
            $sheetWd->setCellValue('A' . $rowWd, 'Tidak ada data pengajuan penarikan pada periode ini.');
            $sheetWd->getStyle('A' . $rowWd)->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => '64748B']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]]
            ]);
            $sheetWd->getRowDimension($rowWd)->setRowHeight(24);
            $rowWd++;
        }

        $sheetWd->mergeCells('A' . $rowWd . ':E' . $rowWd);
        $sheetWd->setCellValue('A' . $rowWd, 'TOTAL PENARIKAN SALDO:');
        if ($withdrawals->count() > 0) {
            $sheetWd->setCellValue('F' . $rowWd, '=SUM(F4:F' . ($rowWd - 1) . ')');
        } else {
            $sheetWd->setCellValue('F' . $rowWd, 0);
        }

        $sheetWd->getStyle('A' . $rowWd . ':H' . $rowWd)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '0F172A']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E8F0']],
            'borders' => [
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '94A3B8']],
                'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '0F172A']],
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]
            ],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheetWd->getStyle('A' . $rowWd)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheetWd->getStyle('F' . $rowWd)->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheetWd->getRowDimension($rowWd)->setRowHeight(26);

        foreach (range('A', 'H') as $col) {
            $sheetWd->getColumnDimension($col)->setAutoSize(true);
        }

        $sheetDaily = $spreadsheet->createSheet();
        $sheetDaily->setTitle('Rekap Harian');
        $sheetDaily->setShowGridLines(true);
        $sheetDaily->mergeCells('A1:G1');
        $sheetDaily->setCellValue('A1', 'REKAPITULASI ARUS KAS HARIAN');
        $sheetDaily->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4338CA']]
        ]);
        $sheetDaily->getRowDimension(1)->setRowHeight(36);

        $sheetDaily->mergeCells('A2:G2');
        $sheetDaily->setCellValue('A2', 'Rangkuman Penerimaan dan Pengeluaran Harian Periode ' . $dateRange['period_label']);
        $sheetDaily->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6366F1']]
        ]);
        $sheetDaily->getRowDimension(2)->setRowHeight(22);

        $dailyHeaders = [
            'A4' => 'Tanggal',
            'B4' => 'Hari',
            'C4' => 'Transaksi Lunas',
            'D4' => 'Pemasukan Bruto (Rp)',
            'E4' => 'Komisi Platform 5% (Rp)',
            'F4' => 'Penarikan Saldo (Rp)',
            'G4' => 'Arus Kas Bersih (Rp)'
        ];

        foreach ($dailyHeaders as $cell => $text) {
            $sheetDaily->setCellValue($cell, $text);
        }

        $sheetDaily->getStyle('A4:G4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '3730A3']]]
        ]);
        $sheetDaily->getRowDimension(4)->setRowHeight(26);

        $rowDaily = 5;
        $noDaily = 1;
        $daysCount = $startDate->diffInDays($endDate) + 1;

        for ($i = 0; $i < $daysCount; $i++) {
            $dayCarbon = $startDate->copy()->addDays($i);
            $dayStart = $dayCarbon->copy()->startOfDay();
            $dayEnd = $dayCarbon->copy()->endOfDay();

            $dayOrders = $orders->filter(function ($order) use ($dayStart, $dayEnd) {
                return $order->created_at >= $dayStart && $order->created_at <= $dayEnd && $order->payment_status === 'paid';
            });
            $dayInflow = (float) $dayOrders->sum('total_price');
            $dayCommission = (float) ($dayInflow * 0.05);

            $dayWd = $withdrawals->filter(function ($w) use ($dayStart, $dayEnd) {
                return $w->created_at >= $dayStart && $w->created_at <= $dayEnd && in_array($w->status, ['approved', 'selesai', 'success', 'processed']);
            });
            $dayOutflow = (float) $dayWd->sum('amount');
            $dayNet = $dayInflow - $dayOutflow;

            $isEven = ($noDaily % 2 === 0);
            $rowBg = $isEven ? 'F5F3FF' : 'FFFFFF';

            $daysIndo = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            $dayNameIndo = $daysIndo[$dayCarbon->format('l')] ?? $dayCarbon->format('l');

            $sheetDaily->setCellValue('A' . $rowDaily, $dayCarbon->format('d/m/Y'));
            $sheetDaily->setCellValue('B' . $rowDaily, $dayNameIndo);
            $sheetDaily->setCellValue('C' . $rowDaily, $dayOrders->count());
            $sheetDaily->setCellValue('D' . $rowDaily, $dayInflow);
            $sheetDaily->setCellValue('E' . $rowDaily, $dayCommission);
            $sheetDaily->setCellValue('F' . $rowDaily, $dayOutflow);
            $sheetDaily->setCellValue('G' . $rowDaily, $dayNet);

            $sheetDaily->getStyle('A' . $rowDaily . ':G' . $rowDaily)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $rowBg]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E0E7FF']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
            ]);

            $sheetDaily->getStyle('A' . $rowDaily)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetDaily->getStyle('B' . $rowDaily)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetDaily->getStyle('C' . $rowDaily)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $sheetDaily->getStyle('D' . $rowDaily)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheetDaily->getStyle('E' . $rowDaily)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheetDaily->getStyle('F' . $rowDaily)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheetDaily->getStyle('G' . $rowDaily)->getNumberFormat()->setFormatCode('"Rp "#,##0');

            $sheetDaily->getRowDimension($rowDaily)->setRowHeight(20);
            $rowDaily++;
            $noDaily++;
        }

        $sheetDaily->mergeCells('A' . $rowDaily . ':B' . $rowDaily);
        $sheetDaily->setCellValue('A' . $rowDaily, 'TOTAL PERIODE:');
        $sheetDaily->setCellValue('C' . $rowDaily, '=SUM(C4:C' . ($rowDaily - 1) . ')');
        $sheetDaily->setCellValue('D' . $rowDaily, '=SUM(D4:D' . ($rowDaily - 1) . ')');
        $sheetDaily->setCellValue('E' . $rowDaily, '=SUM(E4:E' . ($rowDaily - 1) . ')');
        $sheetDaily->setCellValue('F' . $rowDaily, '=SUM(F4:F' . ($rowDaily - 1) . ')');
        $sheetDaily->setCellValue('G' . $rowDaily, '=SUM(G4:G' . ($rowDaily - 1) . ')');

        $sheetDaily->getStyle('A' . $rowDaily . ':G' . $rowDaily)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '0F172A']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E8F0']],
            'borders' => [
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '94A3B8']],
                'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '0F172A']],
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]
            ],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheetDaily->getStyle('A' . $rowDaily)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheetDaily->getStyle('C' . $rowDaily)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheetDaily->getStyle('D' . $rowDaily)->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheetDaily->getStyle('E' . $rowDaily)->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheetDaily->getStyle('F' . $rowDaily)->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheetDaily->getStyle('G' . $rowDaily)->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheetDaily->getRowDimension($rowDaily)->setRowHeight(26);

        foreach (range('A', 'G') as $col) {
            $sheetDaily->getColumnDimension($col)->setAutoSize(true);
        }
        $spreadsheet->setActiveSheetIndex(0);

        $monthClean = str_replace(' ', '_', $dateRange['month_name']);
        $filename = 'Laporan_Keuangan_Karyaku_' . $monthClean . '_' . $dateRange['year'] . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
    public function memberships()
    {
        $memberships = Membership::withCount('users')->orderBy('price', 'asc')->get();
        $totalPaket = $memberships->count();
        $totalPenjualBerlangganan = User::whereNotNull('id_membership')->count();
        $paketTerlaris = $memberships->sortByDesc('users_count')->first();

        return view('admin.membership.paket_membership', compact('memberships', 'totalPaket', 'totalPenjualBerlangganan', 'paketTerlaris'));
    }

    public function storeMembership(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'price'         => 'required',
            'duration_days' => 'required|integer|min:1',
            'max_upload'    => 'required|integer|min:1',
            'benefits'      => 'nullable|array',
            'benefits.*'    => 'string',
        ]);

        $cleanPrice = (float) str_replace(['.', ','], ['', '.'], $validated['price']);
        $benefitString = !empty($validated['benefits']) ? implode(' | ', $validated['benefits']) : 'Fitur Kuota Upload Produk (Sesuai Limit)';

        Membership::create([
            'name'          => $validated['name'],
            'price'         => $cleanPrice,
            'duration_days' => $validated['duration_days'],
            'max_upload'    => $validated['max_upload'],
            'benefit'       => $benefitString,
        ]);

        return redirect()->route('admin.memberships')->with('success', 'Paket membership baru berhasil disimpan.');
    }

    public function updateMembership(Request $request, $id)
    {
        $membership = Membership::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'price'         => 'required',
            'duration_days' => 'required|integer|min:1',
            'max_upload'    => 'required|integer|min:1',
            'benefits'      => 'nullable|array',
            'benefits.*'    => 'string',
        ]);

        $cleanPrice = (float) str_replace(['.', ','], ['', '.'], $validated['price']);
        $benefitString = !empty($validated['benefits']) ? implode(' | ', $validated['benefits']) : 'Fitur Kuota Upload Produk (Sesuai Limit)';

        $membership->update([
            'name'          => $validated['name'],
            'price'         => $cleanPrice,
            'duration_days' => $validated['duration_days'],
            'max_upload'    => $validated['max_upload'],
            'benefit'       => $benefitString,
        ]);

        return redirect()->route('admin.memberships')->with('success', 'Data paket membership berhasil diperbarui.');
    }

    public function deleteMembership($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->delete();

        return redirect()->route('admin.memberships')->with('success', 'Paket membership berhasil dihapus.');
    }

    public function reportSuspicious(Request $request)
    {
        // Fitur ini dinonaktifkan agar tidak mencatat ribuan spam log 
        // ketika tab browser masih terbuka dengan cache Javascript yang lama.
        return response()->json(['status' => 'success', 'message' => 'Laporan diabaikan.']);
    }
}