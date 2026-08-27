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
use App\Models\Notification;
use App\Models\IpLog;
use App\Models\AllowedIp;
use App\Models\AccountAppeal;
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
    | 1. DASHBOARD SYSTEM
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

        $totalUsers = User::whereHas('role', function ($q) {
            $q->whereIn('role_name', ['pembeli', 'penjual']);
        })->count();

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
            $cat->order_count = OrderItem::whereHas('product', function ($q) use ($cat) {
                $q->where('category_id', $cat->id_category);
            })->count();
            return $cat;
        })->sortByDesc('order_count')->take(4)->values();

        $totalCategoryOrders = max(1, $topCategories->sum('order_count'));
        $topCategories = $topCategories->map(function ($cat) use ($totalCategoryOrders) {
            $cat->percentage = round(($cat->order_count / $totalCategoryOrders) * 100);
            return $cat;
        });

        $recentOrders = Order::with('buyer')->latest()->take(3)->get()->map(function ($o) {
            return [
                'title' => 'Order Baru #' . $o->kode_order,
                'desc'  => 'Pembeli "' . ($o->buyer->name ?? '-') . '" membuat pesanan baru.',
                'time'  => $o->created_at,
                'color' => 'emerald',
            ];
        });

        $recentProducts = Product::where('status', 'active')->latest('updated_at')->take(3)->get()->map(function ($p) {
            return [
                'title' => 'Produk Diverifikasi',
                'desc'  => 'Produk "' . $p->title . '" telah disetujui.',
                'time'  => $p->updated_at,
                'color' => 'sky',
            ];
        });

        $recentIdentities = IdentityVerification::with('user')->latest()->take(3)->get()->map(function ($iv) {
            return [
                'title' => 'Pengajuan Identitas',
                'desc'  => 'Kreator "' . ($iv->user->name ?? '-') . '" mengunggah identitas.',
                'time'  => $iv->created_at,
                'color' => 'amber',
            ];
        });

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
    | 2. MAINTENANCE MODE & BACKUP DATABASE
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
            ->filter(function ($f) {
                return str_ends_with($f, '.sql') || str_ends_with($f, '.zip');
            })
            ->map(function ($file) {
                return [
                    'name'       => basename($file),
                    'size'       => $this->formatBytes(Storage::disk('local')->size($file)),
                    'created_at' => Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file)),
                ];
            })
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
                throw new \Exception('Proses kompresi ZIP menghasilkan file kosong. Backup dibatalkan.');
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
    | 3. MANAJEMEN PENGGUNA (AKUN PENGGUNA)
    |--------------------------------------------------------------------------
    */
    public function users(Request $request)
    {
        $search = $request->query('search');

        $users = User::with(['role', 'membership'])
            ->whereHas('role', function ($q) {
                $q->whereIn('role_name', ['pembeli', 'penjual']);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        $users->withQueryString();

        $totalUsers    = User::whereHas('role', function ($q) {
            $q->whereIn('role_name', ['pembeli', 'penjual']);
        })->count();

        $activeCreators = User::whereHas('role', function ($q) {
            $q->where('role_name', 'penjual');
        })->whereHas('products')->count();

        $newThisMonth   = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

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
        if (!empty($validated['password'])) {
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

    public function suspendUser(Request $request, string|int $id)
    {
        $user = User::findOrFail($id);

        if ($user->role?->role_name === 'admin') {
            return redirect()->back()->with('error', 'Akun Admin tidak dapat disuspend.');
        }

        // Jika saat ini berstatus 'blocked', aksi ini adalah AKTIFKAN KEMBALI (Unsuspend)
        if ($user->status === 'blocked') {
            $user->status = 'active';
            $user->suspended_until = null;
            $user->suspend_reason = null;
            $user->save();

            Notification::create([
                'user_id'     => $user->id_user,
                'name'        => '✅ Akun Diaktifkan Kembali',
                'description' => 'Akun Anda telah diaktifkan kembali oleh Admin. Anda kini dapat login dan beraktivitas seperti biasa.',
                'is_read'     => false,
            ]);

            return redirect()->back()->with('success', 'Akun pengguna "' . $user->name . '" berhasil diaktifkan kembali.');
        }

        // AKSI SUSPEND DENGAN DURASI WAKTU & ALASAN
        $days = (int) $request->input('suspend_days', 0);
        $hours = (int) $request->input('suspend_hours', 0);
        $minutes = (int) $request->input('suspend_minutes', 0);
        $reason = $request->input('suspend_reason', 'Pelanggaran syarat dan ketentuan komunitas Karyaku');

        if (empty(trim($reason))) {
            $reason = 'Pelanggaran syarat dan ketentuan komunitas Karyaku';
        }

        $user->status = 'blocked';
        $user->suspend_reason = $reason;

        $totalMinutes = ($days * 24 * 60) + ($hours * 60) + $minutes;
        if ($totalMinutes > 0) {
            $user->suspended_until = now()->addDays($days)->addHours($hours)->addMinutes($minutes);
            $parts = [];
            if ($days > 0) $parts[] = $days . ' Hari';
            if ($hours > 0) $parts[] = $hours . ' Jam';
            if ($minutes > 0) $parts[] = $minutes . ' Menit';
            $durationText = implode(' ', $parts);
        } else {
            $user->suspended_until = null; // Permanen
            $durationText = 'Permanen (Tanpa batas waktu)';
        }
        $user->save();

        Notification::create([
            'user_id'     => $user->id_user,
            'name'        => '⚠️ Status Akun Ditangguhkan',
            'description' => 'Akun Anda dinonaktifkan sementara (' . $durationText . '). Alasan: ' . $reason,
            'is_read'     => false,
        ]);

        return redirect()->back()->with('success', 'Akun "' . $user->name . '" berhasil disuspend (' . $durationText . ').');
    }

    /*
    |--------------------------------------------------------------------------
    | 4. AKUN VERIFIKATOR & VERIFIKASI IDENTITAS
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

        $pendingQueue = IdentityVerification::with(['user', 'membership'])
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
        if (!empty($validated['password'])) {
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

        $targetUserId = $verification->user_id ?? $verification->id_user ?? null;
        $user = User::where('id_user', $targetUserId)->first();

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
                'id_role'       => $penjualRole->id_role,
                'id_membership' => $verification->membership_id ?? $user->id_membership,
                'status'        => 'active',
            ]);

            Notification::create([
                'user_id'     => $user->id_user,
                'name'        => '🎉 Pendaftaran Penjual Disetujui',
                'description' => 'Selamat! Verifikasi identitas Anda telah disetujui. Akun Anda kini beralih menjadi Penjual.',
                'is_read'     => false,
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

        $targetUserId = $verification->user_id ?? $verification->id_user ?? null;

        try {
            $verification->update([
                'status'      => 'rejected',
                'verifier_id' => auth()->id(),
                'notes'       => $validated['notes'] ?? null,
                'verified_at' => now(),
            ]);

            if ($targetUserId) {
                Notification::create([
                    'user_id'     => $targetUserId,
                    'name'        => '❌ Pendaftaran Penjual Ditolak',
                    'description' => 'Pengajuan identitas Anda ditolak. Alasan: ' . ($validated['notes'] ?? 'Dokumen tidak sesuai syarat.'),
                    'is_read'     => false,
                ]);
            }

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
            'selesai' => $tickets->whereIn('status', ['selesai', 'resolved', 'closed'])->count(),
            'proses'  => $tickets->whereIn('status', ['proses', 'in_progress'])->count(),
            'belum'   => $tickets->whereIn('status', ['belum', 'pending'])->count(),
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

        $targetUserId = $ticket->user_id ?? $ticket->id_user ?? null;
        if ($targetUserId) {
            Notification::create([
                'user_id'     => $targetUserId,
                'name'        => 'Pembaharuan Tiket Pengaduan',
                'description' => 'Status tiket ' . $ticket->subject . ' diperbarui menjadi: ' . strtoupper($request->status),
                'is_read'     => false,
            ]);
        }

        return back()->with('success', 'Status keluhan / masukan berhasil diperbarui!');
    }

    /*
    |--------------------------------------------------------------------------
    | 6. KATALOG: DAFTAR JASA (PRODUCT)
    |--------------------------------------------------------------------------
    */
    public function products(Request $request)
    {
        $search = $request->query('search');

        $products = Product::with(['category', 'seller'])
            ->when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        $products->withQueryString();

        $pendingCount = Product::where('status', 'pending')->count();
        $activeCount  = Product::where('status', 'active')->count();

        return view('admin.katalog.daftar_jasa', compact('products', 'pendingCount', 'activeCount'));
    }

    public function approveProduct(string|int $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'active']);

        $targetUserId = $product->user_id ?? $product->id_user ?? null;
        if ($targetUserId) {
            Notification::create([
                'user_id'     => $targetUserId,
                'name'        => '✅ Produk/Jasa Disetujui',
                'description' => 'Produk "' . $product->title . '" Anda telah disetujui dan aktif di marketplace.',
                'is_read'     => false,
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil disetujui.');
    }

    public function takedownProduct(string|int $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'inactive']);

        $targetUserId = $product->user_id ?? $product->id_user ?? null;
        if ($targetUserId) {
            Notification::create([
                'user_id'     => $targetUserId,
                'name'        => '⚠️ Produk Disembunyikan',
                'description' => 'Produk "' . $product->title . '" telah dinonaktifkan dari katalog oleh Admin.',
                'is_read'     => false,
            ]);
        }

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

        $orders = Order::with(['buyer', 'items.product.seller'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('buyer', function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%");
                });
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

        $withdrawals = Withdrawal::with('user')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%");
                })->orWhere('id_withdrawal', 'like', "%{$search}%");
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

        return view('admin.keuangan.penarikan_saldo', compact(
            'withdrawals', 'menungguDiproses', 'selesaiBulanIni', 'gagalDitolak'
        ));
    }

    public function processWithdrawal(string|int $id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->update([
            'status'       => 'processed',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        $targetUserId = $withdrawal->user_id ?? $withdrawal->id_user ?? null;
        if ($targetUserId) {
            Notification::create([
                'user_id'     => $targetUserId,
                'name'        => '💸 Penarikan Saldo Berhasil',
                'description' => 'Penarikan saldo sebesar Rp' . number_format($withdrawal->amount, 0, ',', '.') . ' telah berhasil diproses.',
                'is_read'     => false,
            ]);
        }

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

        $targetUserId = $withdrawal->user_id ?? $withdrawal->id_user ?? null;
        if ($targetUserId) {
            Notification::create([
                'user_id'     => $targetUserId,
                'name'        => '❌ Penarikan Saldo Ditolak',
                'description' => 'Penarikan saldo ditolak oleh Admin. Catatan: ' . ($request->notes ?? 'Data pencairan tidak sesuai.'),
                'is_read'     => false,
            ]);
        }

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

        $diamondCount = User::whereHas('membership', function ($q) {
            $q->where('name', 'LIKE', '%Diamond%');
        })->count();

        $silverCount = User::whereHas('membership', function ($q) {
            $q->where('name', 'LIKE', '%Silver%');
        })->count();

        $bronzeCount = User::whereHas('membership', function ($q) {
            $q->where('name', 'LIKE', '%Bronze%');
        })->count();

        return view('admin.membership.paket_membership', compact(
            'memberships',
            'totalPelangganAktif',
            'diamondCount',
            'silverCount',
            'bronzeCount'
        ));
    }

    public function storeMembership(Request $request)
    {
        // 1. Bersihkan format titik ribuan pada harga
        if ($request->has('price')) {
            $request->merge(['price' => str_replace('.', '', $request->price)]);
        }

        // 2. Olah daftar Checkbox Fitur menjadi teks terstruktur
        $benefitsList = [];
        
        if ($request->filled('max_upload')) {
            $benefitsList[] = 'Maksimal Upload: ' . $request->max_upload . ' karya';
        }

        if ($request->boolean('feat_max_products') && $request->filled('val_max_products')) {
            $benefitsList[] = 'Batas Jasa/Barang: ' . $request->val_max_products . ' item';
        }

        if ($request->boolean('feat_max_ads') && $request->filled('val_max_ads')) {
            $benefitsList[] = 'Iklan Promosi: ' . $request->val_max_ads . ' slot';
        }

        if ($request->boolean('feat_verified_badge')) {
            $benefitsList[] = 'Lencana Kreator Terverifikasi';
        }

        if ($request->boolean('feat_priority_cs')) {
            $benefitsList[] = 'Dukungan CS Prioritas 24/7';
        }

        if ($request->filled('custom_benefit')) {
            $benefitsList[] = $request->custom_benefit;
        }

        if ($request->has('custom_features') && is_array($request->custom_features)) {
            foreach ($request->custom_features as $feat) {
                if (!empty($feat['name']) && (!isset($feat['checked']) || $feat['checked'] == '1' || $feat['checked'] === true)) {
                    $featText = trim($feat['name']);
                    if (!empty($feat['val'])) {
                        $featText .= ': ' . trim($feat['val']);
                    }
                    $benefitsList[] = $featText;
                }
            }
        }

        $benefitText = !empty($benefitsList) ? implode(' | ', array_unique($benefitsList)) : 'Fitur standar keanggotaan';
        $request->merge(['benefit' => $benefitText]);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_upload'    => 'required|integer|min:0',
            'benefit'       => 'required|string',
        ]);

        Membership::create($validated);

        return redirect()->back()->with('success', 'Kartu paket membership baru berhasil ditambahkan.');
    }

    public function updateMembership(Request $request, string|int $id)
    {
        // 1. Bersihkan format titik ribuan pada harga
        if ($request->has('price')) {
            $request->merge(['price' => str_replace('.', '', $request->price)]);
        }

        // 2. Olah daftar Checkbox Fitur menjadi teks terstruktur
        $benefitsList = [];

        if ($request->filled('max_upload')) {
            $benefitsList[] = 'Maksimal Upload: ' . $request->max_upload . ' karya';
        }

        if ($request->boolean('feat_max_products') && $request->filled('val_max_products')) {
            $benefitsList[] = 'Batas Jasa/Barang: ' . $request->val_max_products . ' item';
        }

        if ($request->boolean('feat_max_ads') && $request->filled('val_max_ads')) {
            $benefitsList[] = 'Iklan Promosi: ' . $request->val_max_ads . ' slot';
        }

        if ($request->boolean('feat_verified_badge')) {
            $benefitsList[] = 'Lencana Kreator Terverifikasi';
        }

        if ($request->boolean('feat_priority_cs')) {
            $benefitsList[] = 'Dukungan CS Prioritas 24/7';
        }

        if ($request->filled('custom_benefit')) {
            $benefitsList[] = $request->custom_benefit;
        }

        if ($request->has('custom_features') && is_array($request->custom_features)) {
            foreach ($request->custom_features as $feat) {
                if (!empty($feat['name']) && (!isset($feat['checked']) || $feat['checked'] == '1' || $feat['checked'] === true)) {
                    $featText = trim($feat['name']);
                    if (!empty($feat['val'])) {
                        $featText .= ': ' . trim($feat['val']);
                    }
                    $benefitsList[] = $featText;
                }
            }
        }

        $benefitText = !empty($benefitsList) ? implode(' | ', array_unique($benefitsList)) : 'Fitur standar keanggotaan';
        $request->merge(['benefit' => $benefitText]);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
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

        $reportsAppeal = AccountAppeal::with(['user.role', 'reviewer'])
            ->latest()
            ->paginate(10, ['*'], 'page_banding');

        $pendingAppealCount = AccountAppeal::where('status', 'pending')->count();

        return view('admin.sistem.pelanggaran', compact('reportsUser', 'reportsProduk', 'reportsAppeal', 'pendingAppealCount'));
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

        if ($request->action === 'suspend' && $report->reported_user_id) {
            User::where('id_user', $report->reported_user_id)->update(['status' => 'blocked']);
        }

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

        if ($request->action === 'suspend' && $report->product_id) {
            Product::where('id_product', $report->product_id)->update(['status' => 'inactive']);
        }

        return redirect()->back()->with('success', 'Tindakan laporan produk berhasil diproses.');
    }

    public function tindakAppeal(Request $request, string|int $id)
    {
        $request->validate([
            'action'      => 'required|string|in:setujui,tolak',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $appeal = AccountAppeal::findOrFail($id);
        $user = $appeal->user;

        if ($request->action === 'setujui') {
            $appeal->update([
                'status'      => 'approved',
                'admin_note'  => $request->admin_notes ?: 'Banding disetujui. Akun telah diaktifkan kembali.',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            if ($user) {
                $user->update([
                    'status'          => 'active',
                    'suspended_until' => null,
                    'suspend_reason'  => null,
                ]);

                Notification::create([
                    'user_id'     => $user->id_user,
                    'name'        => '🎉 Banding Disetujui & Akun Aktif',
                    'description' => 'Pengajuan banding Anda telah disetujui oleh Admin. Akun Anda telah diaktifkan kembali. ' . ($request->admin_notes ? 'Catatan Admin: ' . $request->admin_notes : ''),
                    'is_read'     => false,
                ]);
            }

            return redirect()->back()->with('success', 'Banding disetujui dan akun pengguna "' . ($user->name ?? 'User') . '" berhasil diaktifkan kembali.');
        } else {
            $appeal->update([
                'status'      => 'rejected',
                'admin_note'  => $request->admin_notes ?: 'Banding ditolak oleh admin.',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            if ($user) {
                Notification::create([
                    'user_id'     => $user->id_user,
                    'name'        => '❌ Pengajuan Banding Ditolak',
                    'description' => 'Pengajuan banding akun Anda ditolak oleh Admin. Catatan Admin: ' . ($request->admin_notes ?: 'Alasan pembelaan atau bukti tidak mencukupi.'),
                    'is_read'     => false,
                ]);
            }

            return redirect()->back()->with('success', 'Pengajuan banding telah ditolak.');
        }
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
    public function securityVerifyPage(Request $request)
    {
        if ($request->has('reset') && session()->has('security_verified_at')) {
            session()->forget('security_verified_at');
        }

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

        session(['security_verified_at' => now()]);

        return redirect()->route('admin.security.index')->with('success', 'Akses Keamanan Diberikan.');
    }

    public function securityIndex(Request $request)
    {
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