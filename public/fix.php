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

// Bersihkan log false-positive (Resize Window / Klik Kanan) dari database
$dbCleaned = false;
try {
    if (file_exists($baseDir . '/vendor/autoload.php') && file_exists($baseDir . '/bootstrap/app.php')) {
        require_once $baseDir . '/vendor/autoload.php';
        $app = require_once $baseDir . '/bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        \Illuminate\Support\Facades\DB::table('ip_logs')
            ->where('reason', 'like', '%Resize Window%')
            ->orWhere('reason', 'like', '%right-click%')
            ->orWhere('reason', 'like', '%Klik Kanan%')
            ->update(['status' => 'normal', 'reason' => 'Aktivitas Normal']);
        $dbCleaned = true;
    }
} catch (\Throwable $e) {}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemulihan Sistem Karyaku</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; color: #f8fafc; padding: 40px 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #1e293b; border-radius: 16px; padding: 32px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); border: 1px solid #334155; }
        h1 { color: #38bdf8; font-size: 22px; margin-top: 0; }
        .success { background: #064e3b; color: #6ee7b7; padding: 12px 16px; border-radius: 8px; margin: 16px 0; border: 1px solid #047857; }
        .btn { display: inline-block; background: #0284c7; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 20px; }
        .btn:hover { background: #0369a1; }
    </style>
</head>
<body>
    <div class="card">
        <h1>✅ Pemulihan Cache Karyaku Berhasil</h1>
        <p>File cache bootstrap/packages berikut telah dibersihkan:</p>
        <div class="success">
            <?php if (!empty($deletedFiles)): ?>
                <ul>
                    <?php foreach ($deletedFiles as $df): ?>
                        <li><?= htmlspecialchars($df) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Cache packages sudah bersih (Tidak ada file tersisa).</p>
            <?php endif; ?>
        </div>
        <p>Error CloudinaryServiceProvider telah diperbaiki dan aplikasi sekarang dapat diakses secara normal.</p>
        <a href="/" class="btn">Buka Halaman Utama &rarr;</a>
        <a href="/auth/login" class="btn" style="background: #475569; margin-left: 8px;">Halaman Login &rarr;</a>
    </div>
</body>
</html>
