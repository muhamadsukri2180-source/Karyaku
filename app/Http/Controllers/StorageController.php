<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageController extends Controller
{
    /**
     * Menampilkan dan melayani file publik dari storage di web hosting.
     * Mengatasi kendala symlink rusak / tidak ada di cPanel shared hosting,
     * serta secara otomatis menyalin file ke direktori public/storage agar
     * selanjutnya bisa disajikan langsung oleh web server (Apache/Nginx).
     */
    public function show(Request $request, string $path)
    {
        // 1. Pencegahan Directory Traversal
        if (str_contains($path, '..') || str_contains($path, '\\')) {
            abort(403, 'Akses path tidak valid.');
        }

        $cleanPath = ltrim(preg_replace('/^(public\/|storage\/)/', '', $path), '/');

        // 2. Daftar direktori yang mungkin menyimpan file tersebut di hosting / cPanel
        $possiblePaths = [
            storage_path('app/public/' . $cleanPath),
            storage_path('app/public/' . $path),
            public_path('storage/' . $cleanPath),
            public_path('storage/' . $path),
            storage_path('app/' . $cleanPath),
            storage_path('app/' . $path),
            base_path('storage/app/public/' . $cleanPath),
            // Struktur khas cPanel jika public_html sejajar dengan project
            base_path('../public_html/storage/' . $cleanPath),
            dirname(base_path()) . '/public_html/storage/' . $cleanPath,
        ];

        $foundFilePath = null;
        foreach ($possiblePaths as $fullPath) {
            if (!empty($fullPath) && file_exists($fullPath) && is_file($fullPath)) {
                $foundFilePath = $fullPath;
                break;
            }
        }

        // 3. Jika tidak ditemukan lewat file_exists langsung, periksa via Laravel Storage disk public
        if (!$foundFilePath) {
            if (Storage::disk('public')->exists($cleanPath)) {
                $foundFilePath = Storage::disk('public')->path($cleanPath);
            } elseif (Storage::disk('public')->exists($path)) {
                $foundFilePath = Storage::disk('public')->path($path);
            } elseif (Storage::disk('local')->exists($cleanPath)) {
                $foundFilePath = Storage::disk('local')->path($cleanPath);
            }
        }

        // 4. Jika file fisik ditemukan:
        if ($foundFilePath && file_exists($foundFilePath) && is_file($foundFilePath)) {
            // Auto-sync: salin file ke public/storage dan public_html/storage jika belum ada
            // sehingga di File Manager hosting file tampak nyata dan web server dapat melayani statis
            $this->syncToPublicFolder($foundFilePath, $cleanPath);

            $mimeType = @mime_content_type($foundFilePath) ?: 'application/octet-stream';

            // Jika extension svg/css/js
            $ext = strtolower(pathinfo($foundFilePath, PATHINFO_EXTENSION));
            if ($ext === 'svg') {
                $mimeType = 'image/svg+xml';
            } elseif ($ext === 'webp') {
                $mimeType = 'image/webp';
            }

            return response()->file($foundFilePath, [
                'Content-Type'                => $mimeType,
                'Cache-Control'               => 'public, max-age=31536000, immutable',
                'Access-Control-Allow-Origin' => '*',
            ]);
        }

        // 5. Fallback jika file benar-benar belum pernah ter-upload atau terhapus
        return $this->renderFallbackPlaceholder($cleanPath);
    }

    /**
     * Salin file ke folder public/storage agar terlihat di File Manager hosting
     * dan dapat disajikan langsung oleh web server.
     */
    protected function syncToPublicFolder(string $sourcePath, string $relativePath): void
    {
        try {
            $destinations = [
                public_path('storage/' . $relativePath),
            ];

            // Deteksi jika berjalan di cPanel dengan direktori public_html
            $cpanelPublicHtml = base_path('../public_html/storage/' . $relativePath);
            if (is_dir(dirname(base_path('../public_html')))) {
                $destinations[] = $cpanelPublicHtml;
            }

            foreach ($destinations as $dest) {
                // Jangan salin jika file tujuan sudah sama persis atau merupakan symlink yang valid
                if (is_link($dest) || realpath($dest) === realpath($sourcePath)) {
                    continue;
                }

                if (!file_exists($dest)) {
                    $targetDir = dirname($dest);
                    if (!is_dir($targetDir)) {
                        @mkdir($targetDir, 0755, true);
                    }
                    @copy($sourcePath, $dest);
                }
            }
        } catch (\Throwable $e) {
            // Abaikan error background copy agar response file utama tetap berjalan lancar
        }
    }

    /**
     * Render SVG placeholder yang informatif jika file tidak ditemukan
     */
    protected function renderFallbackPlaceholder(string $path)
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];

        // Jika bukan file gambar (misal zip, pdf), return 404 standar
        if (!in_array($ext, $imageExtensions) && !empty($ext)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        $fileName = htmlspecialchars(basename($path));
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="600" height="400" viewBox="0 0 600 400">
            <defs>
                <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#0f172a"/>
                    <stop offset="100%" stop-color="#1e293b"/>
                </linearGradient>
            </defs>
            <rect width="100%" height="100%" rx="16" fill="url(#bg)"/>
            <circle cx="300" cy="160" r="48" fill="#334155"/>
            <path d="M280 160h40M300 140v40" stroke="#94a3b8" stroke-width="4" stroke-linecap="round"/>
            <text x="300" y="240" text-anchor="middle" fill="#f8fafc" font-family="-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif" font-size="18" font-weight="700">Berkas Belum Tersedia di Storage</text>
            <text x="300" y="270" text-anchor="middle" fill="#94a3b8" font-family="-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif" font-size="13">' . $fileName . '</text>
            <text x="300" y="300" text-anchor="middle" fill="#64748b" font-family="-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif" font-size="11">Silakan unggah ulang berkas melalui menu aplikasi</text>
        </svg>';

        return response($svg, 200, [
            'Content-Type'                => 'image/svg+xml',
            'Cache-Control'               => 'no-cache, private',
            'Access-Control-Allow-Origin' => '*',
        ]);
    }
}
