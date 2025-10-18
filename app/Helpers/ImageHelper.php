<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Process and save an image with security checks
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @param int $width
     * @param int $height
     * @return string
     */
    public static function processImage($file, $directory = 'images', $width = 800, $height = 600)
    {
        try {
            // Validate file type
            $allowedMimes = ['jpeg', 'png', 'jpg', 'gif', 'webp'];
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $allowedMimes)) {
                throw new \InvalidArgumentException('Invalid image format. Allowed formats: ' . implode(', ', $allowedMimes));
            }

            // Validate file size (max 2MB)
            if ($file->getSize() > 2048 * 1024) {
                throw new \InvalidArgumentException('Image size exceeds 2MB limit');
            }

            // Generate unique filename
            $filename = time() . '_' . Str::random(10) . '.' . $extension;

            // Create directory if it doesn't exist
            $path = public_path($directory);
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            // Save original file without processing for now
            $filePath = $path . '/' . $filename;
            $file->move($path, $filename);

            // Optionally, add image processing if Intervention Image is available
            if (class_exists('Intervention\Image\Facades\Image')) {
                try {
                    $image = \Intervention\Image\Facades\Image::make($filePath)->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    $image->save($filePath);
                } catch (\Exception $e) {
                    \Log::warning('Image processing failed, saving original: ' . $e->getMessage());
                    // Continue with original file if processing fails
                }
            }

            return $directory . '/' . $filename;
        } catch (\Exception $e) {
            \Log::error('Image processing failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an image file
     *
     * @param string $path
     * @return bool
     */
    public static function deleteImage($path)
    {
        if (file_exists(public_path($path))) {
            return unlink(public_path($path));
        }
        return false;
    }
}