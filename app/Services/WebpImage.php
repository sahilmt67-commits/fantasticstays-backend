<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

class WebpImage
{
    public const MAX_MB = 200;

    public const MAX_EDGE = 2560;

    public const QUALITY = 85;

    public static function tooLargeMessage(): string
    {
        return 'This image is too large. You can upload images up to '.self::MAX_MB.' MB.';
    }

    public static function validate(Request $request): void
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:'.(self::MAX_MB * 1024),
        ], [
            'image.max' => self::tooLargeMessage(),
            'image.image' => 'Please choose an image file (JPG, PNG, or WebP).',
            'image.mimes' => 'Please choose an image file (JPG, PNG, or WebP).',
        ]);
    }

    public static function failed(): JsonResponse
    {
        return response()->json([
            'message' => 'This image could not be saved. Please try a smaller JPG or PNG.',
        ], 422);
    }

    public static function store(UploadedFile $file, string $directory): string
    {
        if (! function_exists('imagewebp')) {
            throw new RuntimeException('WebP support is not available.');
        }

        $bytes = file_get_contents($file->getRealPath());
        $source = $bytes !== false ? @imagecreatefromstring($bytes) : false;
        if ($source === false) {
            throw new RuntimeException('The image could not be read.');
        }

        $source = self::applyJpegOrientation($source, $file);
        if (! imageistruecolor($source)) {
            imagepalettetotruecolor($source);
        }
        imagealphablending($source, true);
        imagesavealpha($source, true);

        $width = imagesx($source);
        $height = imagesy($source);
        if ($width > self::MAX_EDGE || $height > self::MAX_EDGE) {
            $ratio = min(self::MAX_EDGE / $width, self::MAX_EDGE / $height);
            $newWidth = max(1, (int) round($width * $ratio));
            $newHeight = max(1, (int) round($height * $ratio));
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
            imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($source);
            $source = $resized;
        }

        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            imagedestroy($source);
            throw new RuntimeException('The image folder could not be created.');
        }

        $filename = self::filename($file, $directory);
        $saved = imagewebp($source, $directory.DIRECTORY_SEPARATOR.$filename, self::QUALITY);
        imagedestroy($source);

        if (! $saved) {
            throw new RuntimeException('The image could not be saved.');
        }

        return $filename;
    }

    public static function stats(UploadedFile $file, string $directory, string $filename): array
    {
        $savedPath = $directory.DIRECTORY_SEPARATOR.$filename;
        $saved = is_file($savedPath) ? (int) filesize($savedPath) : 0;
        $original = (int) $file->getSize();
        $percent = ($original > 0 && $saved > 0 && $saved < $original)
            ? (int) round((($original - $saved) / $original) * 100)
            : 0;

        return [
            'original_bytes' => $original,
            'saved_bytes' => $saved,
            'optimized_percent' => $percent,
        ];
    }

    public static function response(string $url, UploadedFile $file, string $directory, string $filename, array $extra = []): JsonResponse
    {
        return response()->json(array_merge(
            ['url' => $url],
            $extra,
            self::stats($file, $directory, $filename)
        ));
    }

    private static function filename(UploadedFile $file, string $directory): string
    {
        $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '-');
        $base = trim(Str::limit($base, 80, ''), '-');
        if ($base === '') {
            $base = 'image';
        }

        $name = $base.'.webp';
        $suffix = 2;
        while (is_file($directory.DIRECTORY_SEPARATOR.$name)) {
            $name = $base.'-'.$suffix.'.webp';
            $suffix++;
        }

        return $name;
    }

    private static function applyJpegOrientation(\GdImage $image, UploadedFile $file): \GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $mime = $file->getMimeType();
        if (! in_array($mime, ['image/jpeg', 'image/jpg'], true)) {
            return $image;
        }

        $exif = @exif_read_data($file->getRealPath());
        $orientation = (int) ($exif['Orientation'] ?? 1);
        $angle = match ($orientation) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => null,
        };

        if ($angle === null) {
            return $image;
        }

        $rotated = imagerotate($image, $angle, 0);
        if ($rotated === false) {
            return $image;
        }

        imagedestroy($image);

        return $rotated;
    }
}
