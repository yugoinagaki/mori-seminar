<?php

namespace App\Console\Commands;

use App\Models\AnnualTheme;
use App\Models\MediaFile;
use App\Models\SiteSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class MediaOptimizeCommand extends Command
{
    protected $signature = 'media:optimize';
    protected $description = '既存の画像ファイルを一括最適化（PNG→JPEG変換、最大1920px・品質85）';

    private ImageManager $manager;

    public function handle(): void
    {
        $images = MediaFile::where('mime_type', 'like', 'image/%')->get();
        $this->info("対象: {$images->count()} 件");

        $this->manager = new ImageManager(new GdDriver());
        $bar   = $this->output->createProgressBar($images->count());
        $saved = 0; $missing = 0; $failed = 0;

        foreach ($images as $record) {
            $fullPath = Storage::disk($record->disk)->path($record->path);

            if (!file_exists($fullPath)) {
                $missing++;
                $bar->advance();
                continue;
            }

            try {
                $before  = filesize($fullPath);
                $isPng   = str_ends_with(strtolower($record->path), '.png');
                $image   = $this->manager->decodePath($fullPath);

                if ($image->width() > 1920) {
                    $image->scaleDown(width: 1920);
                }

                if ($isPng) {
                    // PNG → JPEG で大幅削減
                    $newPath     = preg_replace('/\.png$/i', '.jpg', $record->path);
                    $newFullPath = Storage::disk($record->disk)->path($newPath);
                    $image->save($newFullPath, quality: 85);
                    unlink($fullPath);

                    $after = filesize($newFullPath);
                    $record->update([
                        'path'      => $newPath,
                        'mime_type' => 'image/jpeg',
                        'size'      => $after,
                    ]);
                    $this->updateReferences($record->path, $newPath);
                } else {
                    $image->save($fullPath, quality: 85);
                    $after = filesize($fullPath);
                    $record->update(['size' => $after]);
                }

                $saved += max(0, $before - $after);
            } catch (\Throwable $e) {
                $this->newLine();
                $this->warn("失敗: {$record->path} ({$e->getMessage()})");
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $savedMb = round($saved / 1048576, 1);
        $this->info("完了。削減: {$savedMb} MB | ファイル不在: {$missing} | 失敗: {$failed}");
    }

    private function updateReferences(string $oldPath, string $newPath): void
    {
        $setting = SiteSetting::instance();

        if ($setting->hero_image_url === $oldPath) {
            $setting->update(['hero_image_url' => $newPath]);
        }

        $transitions = $setting->transition_image_urls ?? [];
        if (in_array($oldPath, $transitions, true)) {
            $setting->update([
                'transition_image_urls' => array_map(
                    fn ($p) => $p === $oldPath ? $newPath : $p,
                    $transitions
                ),
            ]);
        }

        AnnualTheme::all()->each(function ($theme) use ($oldPath, $newPath) {
            if ($theme->photo_url === $oldPath) {
                $theme->update(['photo_url' => $newPath]);
            }

            $urls = $theme->slideshow_photo_urls ?? [];
            if (in_array($oldPath, $urls, true)) {
                $theme->update([
                    'slideshow_photo_urls' => array_map(
                        fn ($p) => $p === $oldPath ? $newPath : $p,
                        $urls
                    ),
                ]);
            }
        });
    }
}
