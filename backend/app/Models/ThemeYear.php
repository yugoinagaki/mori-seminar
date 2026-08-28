<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeYear extends Model
{
    protected $fillable = ['year', 'photo_url', 'slideshow_photo_urls'];

    protected $casts = [
        'slideshow_photo_urls' => 'array',
    ];

    public function themes()
    {
        return $this->hasMany(AnnualTheme::class, 'year', 'year');
    }
}
