<?php
/**
 * Karyaku Storage Repair Script
 * Perbaiki masalah foto/file upload tidak bisa dilihat di hosting.
 * Akses melalui browser: https://karyakuu.my.id/storage_fix.php
 * 
 * HAPUS file ini setelah selesai digunakan!
 */

header('Content-Type: text/html; charset=utf-8');

$baseDir     = dirname(__DIR__);
$publicDir   = __DIR__;
$targetDir   = $baseDir . '/storage/app/public';
$linkDir     = $publicDir . '/storage';
$results     = [];
$errors      = [];

// ─── 1. Pastikan folder storage/app/public dan subfoldernya ada ───────────────
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
    $path = $targetDir . '/' . $sub;
    if (!is_dir($path)) {
        if (@mkdir($path, 0755, true)) {
            $results[] = "✅ Folder dibuat: storage/app/public/$sub";
        } else {
            $errors[] = "❌ Gagal buat folder: storage/app/public/$sub";
        }
    } else {
        $results[] = "✅ Folder sudah ada: storage/app/public/$sub";
    }
}

// ─── 2. Coba perbaiki symlink public/storage → storage/app/public ─────────────
$symlinkOk = false;
$method    = '';

// Cek apakah symlink sudah valid
if (is_link($linkDir)) {
    $resolved = realpath($linkDir);
    $targetRes = realpath($targetDir);
    if ($resolved && $targetRes && $resolved === $targetRes) {
        $symlinkOk = true;
        $method    = 'symlink sudah valid';
        $results[] = "✅ Symlink public/storage sudah terhubung dengan benar ke storage/app/public";
    } else {
        // Symlink ada tapi rusak/salah target - hapus dan buat ulang
        @unlink($linkDir);
        $results[] = "🔧 Symlink lama rusak, dihapus untuk dibuat ulang.";
    }
}

// Jika bukan symlink tapi folder biasa yang valid (metode copy)
if (!$symlinkOk && is_dir($linkDir) && !is_link($linkDir)) {
    $symlinkOk = true;
    $method    = 'folder biasa (copy mode)';
    $results[] = "⚠️ public/storage adalah folder biasa (bukan symlink). Hosting tidak support symlink, mode copy digunakan.";
}

// Coba buat symlink baru jika belum ok
if (!$symlinkOk) {
    if (@symlink($targetDir, $linkDir)) {
        $symlinkOk = true;
        $method    = 'symlink baru berhasil dibuat';
        $results[] = "✅ Symlink public/storage berhasil dibuat → storage/app/public";
    } else {
        // Hosting tidak support symlink: buat folder biasa
        if (@mkdir($linkDir, 0755, true)) {
            $results[] = "⚠️ Symlink gagal. Folder public/storage dibuat sebagai folder biasa (mode copy).";
            $method    = 'folder biasa dibuat (symlink gagal)';
            $symlinkOk = true;
        } else {
            $errors[] = "❌ Gagal membuat symlink maupun folder public/storage. Periksa permission!";
        }
    }
}

// ─── 3. Buat subfolder di public/storage jika belum ada (untuk mode folder biasa) ──
if ($symlinkOk && is_dir($linkDir) && !is_link($linkDir)) {
    foreach ($subfolders as $sub) {
        $path = $linkDir . '/' . $sub;
        if (!is_dir($path)) {
            @mkdir($path, 0755, true);
        }
    }
}

// ─── 4. Salin file yang ada di storage/app/public ke public/storage ───────────
$copiedCount  = 0;
$skippedCount = 0;

function syncStorageFiles($src, $dst, &$copied, &$skipped) {
    if (!is_dir($src)) return;
    $items = @scandir($src);
    if (!$items) return;

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $srcPath = $src . '/' . $item;
        $dstPath = $dst . '/' . $item;

        if (is_dir($srcPath)) {
            if (!is_dir($dstPath)) {
                @mkdir($dstPath, 0755, true);
            }
            syncStorageFiles($srcPath, $dstPath, $copied, $skipped);
        } else {
            if (!file_exists($dstPath)) {
                if (@copy($srcPath, $dstPath)) {
                    $copied++;
                }
            } else {
                $skipped++;
            }
        }
    }
}

// Jika ada file yang tersimpan di storage/app/ (karena dulunya FILESYSTEM_DISK=local), pindahkan ke storage/app/public
$localAppDir = $baseDir . '/storage/app';
foreach ($subfolders as $sub) {
    $legacyFolder = $localAppDir . '/' . $sub;
    $targetFolder = $targetDir . '/' . $sub;
    if (is_dir($legacyFolder)) {
        syncStorageFiles($legacyFolder, $targetFolder, $copiedCount, $skippedCount);
    }
}

// Hanya salin kalau public/storage adalah folder biasa (bukan symlink)
if (is_dir($linkDir) && !is_link($linkDir)) {
    syncStorageFiles($targetDir, $linkDir, $copiedCount, $skippedCount);
    $results[] = "📋 Sinkronisasi file: $copiedCount file disalin, $skippedCount file sudah ada.";
} elseif (is_link($linkDir)) {
    $results[] = "ℹ️ Mode symlink: file langsung terbaca, tidak perlu disalin.";
}

// ─── 5. Test akses file ────────────────────────────────────────────────────────
$testFile    = $targetDir . '/.karyaku_test_' . time() . '.txt';
$testContent = 'Karyaku Storage Test - ' . date('Y-m-d H:i:s');
$accessTest  = false;

@file_put_contents($testFile, $testContent);
if (file_exists($testFile)) {
    $results[] = "✅ Write test berhasil: dapat menulis file ke storage/app/public";
    @unlink($testFile);
} else {
    $errors[] = "❌ Write test gagal: tidak dapat menulis file ke storage/app/public. Periksa permission folder!";
}

// ─── 6. Verifikasi file yang ada di database (cek apakah path-nya valid) ──────
$fileStats = [
    'thumbnails' => 0,
    'gallery'    => 0,
    'ktp'        => 0,
    'payment'    => 0,
    'total_files' => 0,
];

function countFilesInDir($dir) {
    if (!is_dir($dir)) return 0;
    $count = 0;
    $items = @scandir($dir);
    if (!$items) return 0;
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            $count += countFilesInDir($path);
        } else {
            $count++;
        }
    }
    return $count;
}

$fileStats['thumbnails']  = countFilesInDir($targetDir . '/products/thumbnails');
$fileStats['gallery']     = countFilesInDir($targetDir . '/products/gallery');
$fileStats['ktp']         = countFilesInDir($targetDir . '/identity-verifications/ktp');
$fileStats['payment']     = countFilesInDir($targetDir . '/identity-verifications/payment');
$fileStats['total_files'] = countFilesInDir($targetDir);

// ─── 7. Jalankan artisan via Laravel bootstrap (opsional) ─────────────────────
$artisanOutput = '';
try {
    if (file_exists($baseDir . '/vendor/autoload.php') && file_exists($baseDir . '/bootstrap/app.php')) {
        require_once $baseDir . '/vendor/autoload.php';
        $app = require_once $baseDir . '/bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        $artisanOutput = '✅ Artisan config:clear, view:clear, cache:clear berhasil dijalankan.';
    }
} catch (\Throwable $e) {
    $artisanOutput = '⚠️ Artisan: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karyaku — Storage Repair</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0b1120; color: #f8fafc; padding: 30px 16px; }
        .card { max-width: 720px; margin: 0 auto; background: #1e293b; border-radius: 16px; padding: 32px; box-shadow: 0 10px 40px rgba(0,0,0,0.5); border: 1px solid #334155; }
        h1 { color: #38bdf8; font-size: 22px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        h2 { font-size: 15px; color: #94a3b8; margin: 20px 0 10px; text-transform: uppercase; letter-spacing: 1px; }
        .badge { display: inline-block; background: #0ea5e9; color: white; font-size: 11px; border-radius: 6px; padding: 2px 8px; vertical-align: middle; }
        .result-list { list-style: none; }
        .result-list li { padding: 6px 10px; border-radius: 6px; font-size: 14px; margin: 4px 0; background: #0f172a; border-left: 3px solid #334155; }
        .result-list li:has(.ok) { border-left-color: #22c55e; }
        .error-list { list-style: none; }
        .error-list li { padding: 6px 10px; border-radius: 6px; font-size: 14px; margin: 4px 0; background: #450a0a; border-left: 3px solid #ef4444; color: #fca5a5; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; margin: 12px 0; }
        .stat-box { background: #0f172a; border-radius: 10px; padding: 14px 16px; text-align: center; border: 1px solid #1e3a5f; }
        .stat-num { font-size: 28px; font-weight: 700; color: #38bdf8; }
        .stat-label { font-size: 12px; color: #64748b; margin-top: 4px; }
        .artisan-box { background: #0f172a; border: 1px solid #1e3a5f; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #7dd3fc; margin: 10px 0; }
        .btn { display: inline-block; background: #0284c7; color: white; padding: 10px 22px; border-radius: 8px; text-decoration: none; font-weight: 600; margin-top: 20px; margin-right: 8px; font-size: 14px; }
        .btn:hover { background: #0369a1; }
        .btn-red { background: #dc2626; }
        .btn-red:hover { background: #b91c1c; }
        .warning { background: #431407; border: 1px solid #ea580c; border-radius: 8px; padding: 12px 16px; color: #fed7aa; font-size: 13px; margin: 16px 0; }
        code { background: #0f172a; padding: 1px 5px; border-radius: 4px; color: #f43f5e; font-family: monospace; font-size: 13px; }
    </style>
</head>
<body>
<div class="card">
    <h1>🛠️ Karyaku — Storage Repair <span class="badge">v2.0</span></h1>

    <div class="warning">
        ⚠️ <strong>KEAMANAN:</strong> Hapus file <code>storage_fix.php</code> setelah selesai! File ini memberikan akses ke sistem dan hanya boleh digunakan oleh admin.
    </div>

    <h2>📁 Status Folder & Symlink</h2>
    <ul class="result-list">
        <?php foreach ($results as $r): ?>
            <li><?= htmlspecialchars($r) ?></li>
        <?php endforeach; ?>
    </ul>

    <?php if (!empty($errors)): ?>
    <h2>❌ Error</h2>
    <ul class="error-list">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <h2>📊 Statistik File di Storage</h2>
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-num"><?= $fileStats['thumbnails'] ?></div>
            <div class="stat-label">Thumbnail Produk</div>
        </div>
        <div class="stat-box">
            <div class="stat-num"><?= $fileStats['gallery'] ?></div>
            <div class="stat-label">Foto Gallery</div>
        </div>
        <div class="stat-box">
            <div class="stat-num"><?= $fileStats['ktp'] ?></div>
            <div class="stat-label">Dokumen KTP</div>
        </div>
        <div class="stat-box">
            <div class="stat-num"><?= $fileStats['payment'] ?></div>
            <div class="stat-label">Bukti Pembayaran</div>
        </div>
        <div class="stat-box">
            <div class="stat-num"><?= $fileStats['total_files'] ?></div>
            <div class="stat-label">Total File</div>
        </div>
    </div>

    <h2>⚙️ Artisan Cache</h2>
    <div class="artisan-box"><?= htmlspecialchars($artisanOutput ?: 'Artisan tidak dapat dijalankan.') ?></div>

    <h2>📋 Informasi Sistem</h2>
    <ul class="result-list">
        <li>Storage target: <code><?= htmlspecialchars($targetDir) ?></code></li>
        <li>Public storage link: <code><?= htmlspecialchars($linkDir) ?></code></li>
        <li>Is symlink: <code><?= is_link($linkDir) ? 'Ya' : 'Tidak (folder biasa)' ?></code></li>
        <li>Folder dapat ditulis: <code><?= is_writable($targetDir) ? '✅ Ya' : '❌ Tidak' ?></code></li>
        <li>PHP version: <code><?= phpversion() ?></code></li>
        <li>Server: <code><?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') ?></code></li>
    </ul>

    <a href="/" class="btn">🏠 Kembali ke Beranda</a>
    <a href="/penjual/produk" class="btn">📦 Halaman Produk Penjual</a>
    <a href="/fix.php" class="btn btn-red">🔧 Jalankan fix.php</a>
</div>
</body>
</html>
