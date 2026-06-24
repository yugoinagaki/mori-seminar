<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    protected $table = 'professor';

    protected $fillable = [
        'name', 'name_en', 'title', 'bio',
        'profile_image_url', 'career', 'awards', 'research_themes',
    ];

    protected $casts = [
        'career'           => 'array',
        'awards'           => 'array',
        'research_themes'  => 'array',
    ];
}
