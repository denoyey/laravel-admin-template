<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ImageUploadService
{
    /**
     * Upload an image, convert to WebP, and compress it based on given quality.
     * Smart handling for existing WebP files under 100 KB.
     *
     * @param  UploadedFile  $file  The uploaded image file.
     * @param  string  $folderPath  The target directory inside public storage.
     * @param  int  $quality  The compression quality (10 - 100). Default is 80.
     * @return string The stored file path relative to the disk root.
     */
    public function uploadAndConvertToWebp(UploadedFile $file, string $folderPath, int $quality = 80): string
    {
        $quality = max(10, min(100, $quality));

        $extension = strtolower($file->getClientOriginalExtension());
        $fileSize = $file->getSize();

        if ($extension === 'webp' && $fileSize < 102400) {
            $filename = Str::uuid().'.webp';

            return $file->storeAs($folderPath, $filename, 'public');
        }

        $manager = new ImageManager(new Driver);

        $image = $manager->decodePath($file->getRealPath());

        $encodedImage = $image->encode(new WebpEncoder(quality: $quality));

        $filename = Str::uuid().'.webp';
        $fullPath = rtrim($folderPath, '/').'/'.$filename;

        Storage::disk('public')->put($fullPath, (string) $encodedImage);

        return $fullPath;
    }
}
