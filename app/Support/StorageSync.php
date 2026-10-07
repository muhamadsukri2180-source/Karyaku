<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

class StorageSync
{
    /**
     * Simpan file yang diunggah ke disk 'public', lalu otomatis sinkronkan ke folder
     * public/storage dan public_html/storage untuk kompatibilitas web hosting / cPanel.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string Relative path yang disimpan di database
     */
    public static function store(UploadedFile $file, string $folder): string
    {
        // 1. Simpan ke storage Laravel standar disk 'public'
        $path = $file->store($folder, 'public');

        // 2. Sinkronkan secara fisik ke folder publik web hosting
        self::syncFile($path);

        return $path;
    }

    /**
     * Sinkronkan file dari storage/app/public ke folder public/storage fisik
     */
    public static function syncFile(string $relativePath): void
    {
        try {
            $source = storage_path('app/public/' . $relativePath);
            if (!file_exists($source) || !is_file($source)) {
                return;
            }

            $destinations = [
                public_path('storage/' . $relativePath),
            ];

            // Deteksi jika berada di struktur cPanel standar (public_html berdampingan)
            $cpanelPublicHtml = base_path('../public_html/storage/' . $relativePath);
            if (is_dir(dirname(base_path('../public_html')))) {
                $destinations[] = $cpanelPublicHtml;
            }

            foreach ($destinations as $dest) {
                // Lewati jika sudah ada symlink valid atau file sudah identik
                if (is_link($dest) || realpath($dest) === realpath($source)) {
                    continue;
                }

                $dir = dirname($dest);
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }

                @copy($source, $dest);
            }
        } catch (\Throwable $e) {
            // Abaikan kesalahan minor agar flow utama aplikasi tidak terputus
        }
    }

    /**
     * Hapus file dari semua lokasi (storage/app/public dan public/storage)
     */
    public static function delete(?string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }

        $cleanPath = ltrim(preg_replace('/^(public\/|storage\/)/', '', $relativePath), '/');

        $targets = [
            storage_path('app/public/' . $cleanPath),
            public_path('storage/' . $cleanPath),
            base_path('../public_html/storage/' . $cleanPath),
        ];

        foreach ($targets as $target) {
            if (file_exists($target) && !is_dir($target)) {
                @unlink($target);
            }
        }
    }
}
