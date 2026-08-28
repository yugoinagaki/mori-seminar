<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    public const PAGE_KEYS = [
        'theme'        => 'Theme',
        'news'         => 'News',
        'professor'    => 'Professor',
        'blog'         => 'Blog',
        'members'      => 'Members',
        'case_studies' => 'Case Studies',
        'faq'          => 'FAQ',
        'contact'      => 'Contact',
    ];

    protected $fillable = ['hero_image_url', 'transition_image_urls', 'page_visibilities'];

    protected $casts = [
        'transition_image_urls' => 'array',
        'page_visibilities'     => 'array',
    ];

    public static function instance(): self
    {
        return self::firstOrCreate(['id' => 1]);
    }

    public function isPageVisible(string $key): bool
    {
        $visibilities = $this->page_visibilities ?? [];

        // Default: visible when key is missing (backwards-compatible)
        return $visibilities[$key] ?? true;
    }
}
