<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    /**
     * Upload an image or video file.
     * Safely stores to local/public disk storage without external library dependency.
     */
    public static function uploadFile(?UploadedFile $file, string $folder = 'uploads', string $fallbackFolder = 'uploads'): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        return $file->store($fallbackFolder, 'public');
    }
}
