<?php

namespace App\Models;

use App\Observers\AnnualThemeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(AnnualThemeObserver::class)]

class AnnualTheme extends Model
{
    protected $fillable = ['year', 'title', 'content', 'photo_url', 'slideshow_photo_urls'];

    protected $casts = [
        'slideshow_photo_urls' => 'array',
    ];
}
