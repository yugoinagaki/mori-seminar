<?php

namespace App\Observers;

use App\Models\AnnualTheme;
use App\Models\MediaFile;
use Illuminate\Support\Facades\DB;

class AnnualThemeObserver
{
    public function saved(AnnualTheme $theme): void
    {
        DB::transaction(function () use ($theme) {
            if ($theme->wasChanged('photo_url') && $theme->photo_url) {
                MediaFile::track($theme->photo_url, 'theme-photo');
            }

            if ($theme->wasChanged('slideshow_photo_urls')) {
                foreach ((array) ($theme->slideshow_photo_urls ?? []) as $path) {
                    if ($path) MediaFile::track($path, 'slideshow');
                }
            }
        });
    }
}
