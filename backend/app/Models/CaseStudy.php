<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CaseStudy extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'content',
        'thumbnail_url', 'author_id', 'status', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (CaseStudy $cs) {
            if (empty($cs->slug)) {
                $cs->slug = Str::slug($cs->title);
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(Member::class, 'author_id');
    }
}
