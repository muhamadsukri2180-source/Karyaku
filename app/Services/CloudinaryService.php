<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * Upload an image or video file to Cloudinary.
     * Returns the secure HTTPS URL from Cloudinary.
     * Fallbacks to local storage if Cloudinary upload encounters an issue.
     */
    public static function uploadFile(?UploadedFile $file, string $folder = 'uploads', string $fallbackFolder = 'uploads'): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        try {
            $mime = $file->getMimeType();
            $resourceType = 'auto';

            if (str_starts_with($mime, 'video/')) {
                $resourceType = 'video';
            } elseif (str_starts_with($mime, 'image/')) {
                $resourceType = 'image';
            }

            $uploaded = Cloudinary::upload($file->getRealPath(), [
                'folder'        => 'karyaku/' . trim($folder, '/'),
                'resource_type' => $resourceType,
            ]);

            return $uploaded->getSecurePath();
        } catch (\Throwable $e) {
            Log::warning("Cloudinary upload failed, using local storage fallback for " . $file->getClientOriginalName() . ": " . $e->getMessage());
            return $file->store($fallbackFolder, 'public');
        }
    }
}
