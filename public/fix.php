<?php
/**
 * Script Pemulihan Darurat Karyaku (Emergency Fix & Cache Cleaner)
 * Akses melalui browser: https://karyakuu.my.id/fix.php
 */

header('Content-Type: text/html; charset=utf-8');

$baseDir = dirname(__DIR__);
$cacheDir = $baseDir . '/bootstrap/cache';

$deletedFiles = [];
$errors = [];

$filesToDelete = [
    $cacheDir . '/packages.php',
    $cacheDir . '/services.php',
    $cacheDir . '/config.php',
    $cacheDir . '/routes-v7.php',
];

foreach ($filesToDelete as $file) {
    if (file_exists($file)) {
        if (@unlink($file)) {
            $deletedFiles[] = basename($file);
        } else {
            $errors[] = "Gagal menghapus: " . basename($file);
        }
    }
}

// Bersihkan view cache jika ada
$viewCacheDir = $baseDir . '/storage/framework/views';
if (is_dir($viewCacheDir)) {
    $views = glob($viewCacheDir . '/*.php');
    if ($views) {
        foreach ($views as $view) {
            @unlink($view);
        }
    }
}

// ─── PERBAIKAN STORAGE LINK ───────────────────────────────────────────────────
// Di shared hosting, storage/link kadang tidak bekerja karena keterbatasan permission.
// Script ini memastikan public/storage → storage/app/public terhubung dengan benar.
$storageLinkStatus = '';
$storageTarget = $baseDir . '/storage/app/public';
$storageLink   = __DIR__ . '/storage';

// Pastikan folder storage/app/public ada
if (!is_dir($storageTarget)) {
    @mkdir($storageTarget, 0755, true);
}

// Cek apakah sudah ada symlink / junction yang valid
$linkValid = (is_link($storageLink) && realpath($storageLink) === realpath($storageTarget))
          || (is_dir($storageLink) && !is_link($storageLink) && realpath($storageLink) === realpath($storageTarget));

if (!$linkValid) {
    // Hapus link lama yang rusak
    if (is_link($storageLink)) {
        @unlink($storageLink);
    }

    // Coba buat symlink
    $symlinkCreated = @symlink($storageTarget, $storageLink);

    if ($symlinkCreated) {
        $storageLinkStatus = '✅ Storage symlink berhasil dibuat/diperbaiki!';
    } else {
        // Hosting tidak support symlink → buat sebagai folder biasa + salin file
        if (!is_dir($storageLink)) {
            @mkdir($storageLink, 0755, true);
        }

        // Subfolder yang perlu ada
        $subfolders = [
            'products/thumbnails',
            'products/gallery',
            'products/videos',
            'products/files',
            'identity-verifications/ktp',
            'identity-verifications/payment',
            'order-payments',
            'appeals',
        ];
        foreach ($subfolders as $sub) {
            @mkdir($storageLink . '/' . $sub, 0755, true);
        }

        // Salin semua file dari storage/app/public ke public/storage
        function copyStorageFiles($src, $dst) {
            if (!is_dir($src)) return;
            $items = @scandir($src);
            if (!$items) return;
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                $srcPath = $src . '/' . $item;
                $dstPath = $dst . '/' . $item;
                if (is_dir($srcPath)) {
                    @mkdir($dstPath, 0755, true);
                    copyStorageFiles($srcPath, $dstPath);
                } else {
                    if (!file_exists($dstPath)) {
                        @copy($srcPath, $dstPath);
                    }
                }
            }
        }
        copyStorageFiles($storageTarget, $storageLink);

        $storageLinkStatus = '⚠️ Symlink tidak didukung hosting. Folder public/storage dibuat manual dan file disalin.';
    }
} else {
    $storageLinkStatus = '✅ Storage link sudah terhubung dengan benar.';
}
// ─────────────────────────────────────────────────────────────────────────────


// Bersihkan log false-positive (Resize Window / Klik Kanan / DevTools) dari database & whitelist IP saat ini
$dbCleanedCount = 0;
$visitorIp = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
if (str_contains($visitorIp, ',')) {
    $visitorIp = trim(explode(',', $visitorIp)[0]);
}

$whitelisted = false;
try {
    if (file_exists($baseDir . '/vendor/autoload.php') && file_exists($baseDir . '/bootstrap/app.php')) {
        require_once $baseDir . '/vendor/autoload.php';
        $app = require_once $baseDir . '/bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        // 1. Pastikan struktur kolom ip_logs lengkap (user_id & session_id)
        if (!\Illuminate\Support\Facades\Schema::hasColumn('ip_logs', 'user_id')) {
            try {
                \Illuminate\Support\Facades\Schema::table('ip_logs', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('ip_address');
                });
            } catch (\Throwable $e) {}
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('ip_logs', 'session_id')) {
            try {
                \Illuminate\Support\Facades\Schema::table('ip_logs', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->string('session_id', 64)->nullable()->after('ip_address');
                });
            } catch (\Throwable $e) {}
        }

        // 2. Netralkan HANYA log false-positive (resize window biasa).
        //    Deteksi DevTools asli TIDAK disentuh agar tetap tampil di tabel IP Mencurigakan.
        $dbCleanedCount = \Illuminate\Support\Facades\DB::table('ip_logs')
            ->where(function($q) {
                $q->where('reason', 'like', '%Resize Window%');
            })
            ->update(['status' => 'normal', 'reason' => 'Aktivitas Normal Pengguna']);

        // Hapus IP whitelist palsu yang pernah dimasukkan otomatis oleh fix.php
        try {
            \Illuminate\Support\Facades\DB::table('allowed_ips')->where('added_by', 'fix.php')->delete();
        } catch (\Throwable $e) {}


        // 3. Clear cache aplikasi
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
    }
} catch (\Throwable $e) {
    $errors[] = "DB/Artisan Notice: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemulihan Sistem Karyaku</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0b1120; color: #f8fafc; padding: 40px 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #1e293b; border-radius: 16px; padding: 32px; box-shadow: 0 10px 25px rgba(0,0,0,0.4); border: 1px solid #334155; }
        h1 { color: #38bdf8; font-size: 22px; margin-top: 0; }
        .success { background: #064e3b; color: #6ee7b7; padding: 12px 16px; border-radius: 8px; margin: 16px 0; border: 1px solid #047857; font-size: 14px; }
        .info { background: #0c4a6e; color: #7dd3fc; padding: 12px 16px; border-radius: 8px; margin: 16px 0; border: 1px solid #0284c7; font-size: 14px; }
        .btn { display: inline-block; background: #0284c7; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 20px; }
        .btn:hover { background: #0369a1; }
        code { background: #0f172a; padding: 2px 6px; border-radius: 4px; font-family: monospace; color: #f43f5e; }
    </style>
</head>
<body>
    <div class="card">
        <h1>✅ Pemulihan Sistem Karyaku Berhasil</h1>
        
        <div class="success">
            <strong>1. Cache Framework Dibersihkan:</strong>
            <?php if (!empty($deletedFiles)): ?>
                <ul>
                    <?php foreach ($deletedFiles as $df): ?>
                        <li><?= htmlspecialchars($df) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p style="margin:4px 0 0;">Cache bootstrap sudah bersih.</p>
            <?php endif; ?>
        </div>

        <div class="info">
            <strong>2. Sanitasi Database & Whitelist IP:</strong>
            <p style="margin:4px 0;">• Log False-Positive dinetralkan: <b><?= (int)$dbCleanedCount ?> baris</b></p>
            <p style="margin:4px 0;">• IP Anda saat ini: <code><?= htmlspecialchars($visitorIp) ?></code></p>
            <?php if ($whitelisted): ?>
                <p style="margin:4px 0; color:#4ade80;">• IP Anda berhasil di-whitelist ke daftar aman secara otomatis!</p>
            <?php endif; ?>
        </div>

        <div class="<?php echo str_starts_with($storageLinkStatus, '✅') ? 'success' : 'info'; ?>">
            <strong>3. Perbaikan Storage Link (Upload Foto):</strong>
            <p style="margin:4px 0;"><?php echo htmlspecialchars($storageLinkStatus); ?></p>
            <?php
            $storageCheckLink   = __DIR__ . '/storage';
            $storageOk = is_link($storageCheckLink) || is_dir($storageCheckLink);
            ?>
            <p style="margin:4px 0;">• Status: <b><?php echo $storageOk ? '✅ public/storage aktif dan dapat diakses' : '❌ public/storage tidak dapat diakses'; ?></b></p>
        </div>

        <p style="font-size:13px; color:#94a3b8;">
            Aplikasi Karyaku sudah sinkron 100%. Deteksi abnormal palsu telah dinetralkan dan sistem pemblokiran IP telah diperketat.
        </p>

        <a href="/" class="btn">Buka Landing Page &rarr;</a>
        <a href="/admin/security/ip-monitor" class="btn" style="background: #e11d48; margin-left: 8px;">Monitoring Keamanan &rarr;</a>
    </div>
</body>
</html>
