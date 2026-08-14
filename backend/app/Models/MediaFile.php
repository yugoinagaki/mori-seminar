<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class MediaFile extends Model
{
    protected $fillable = ['path', 'disk', 'collection', 'original_name', 'mime_type', 'size'];

    /**
     * ファイルパスを media_files に記録する（firstOrCreate で冪等）。
     * DB::transaction 内から呼ぶことで原子性を保証する。
     */
    public static function track(string $path, string $collection, string $disk = 'public'): self
    {
        $mime = rescue(fn () => Storage::disk($disk)->mimeType($path), null, false);

        $record = static::firstOrCreate(
            ['path' => $path],
            [
                'disk'          => $disk,
                'collection'    => $collection,
                'original_name' => basename($path),
                'mime_type'     => $mime,
                'size'          => rescue(fn () => Storage::disk($disk)->size($path), 0, false),
            ]
        );

        if ($record->wasRecentlyCreated && str_starts_with($mime ?? '', 'image/')) {
            static::optimizeImage($disk, $path, $record);
        }

        return $record;
    }

    private static function optimizeImage(string $disk, string $path, self $record): void
    {
        try {
            $fullPath = Storage::disk($disk)->path($path);
            $image = (new ImageManager(new GdDriver()))->decodePath($fullPath);
            if ($image->width() > 1920) {
                $image->scaleDown(width: 1920);
            }
            $image->save($fullPath, quality: 85);
            $record->update(['size' => Storage::disk($disk)->size($path)]);
        } catch (\Throwable) {
            // 圧縮失敗時はオリジナルをそのまま使用
        }
    }

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function humanSize(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
