<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['hero_image_url', 'transition_image_urls'];

    protected $casts = [
        'transition_image_urls' => 'array',
    ];

    public static function instance(): self
    {
        return self::firstOrCreate(['id' => 1]);
    }
}
