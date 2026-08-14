<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    protected $fillable = ['path', 'disk', 'collection', 'original_name', 'mime_type', 'size'];

    /**
     * ファイルパスを media_files に記録する（firstOrCreate で冪等）。
     * DB::transaction 内から呼ぶことで原子性を保証する。
     */
    public static function track(string $path, string $collection, string $disk = 'public'): self
    {
        return static::firstOrCreate(
            ['path' => $path],
            [
                'disk'          => $disk,
                'collection'    => $collection,
                'original_name' => basename($path),
                'mime_type'     => rescue(fn () => Storage::disk($disk)->mimeType($path), null, false),
                'size'          => rescue(fn () => Storage::disk($disk)->size($path), 0, false),
            ]
        );
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
