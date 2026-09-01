<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    protected $table = 'professor';

    protected $fillable = [
        'name', 'name_en', 'title', 'bio', 'bio_blocks',
        'profile_image_url', 'career', 'awards',
        'research_themes', 'research_themes_body',
        'books', 'papers', 'gallery_photo_urls',
        'achievements_pdf_url', 'achievements_pdf_note',
    ];

    protected $casts = [
        'career'             => 'array',
        'awards'             => 'array',
        'research_themes'    => 'array',
        'books'              => 'array',
        'papers'             => 'array',
        'gallery_photo_urls' => 'array',
        'bio_blocks'         => 'array',
    ];
}
