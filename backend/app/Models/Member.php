<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name', 'name_kana', 'generation', 'university_year',
        'major', 'bio', 'profile_image_url', 'status',
        'graduated_year', 'twitter_url', 'instagram_url', 'order_index',
    ];

    public function caseStudies()
    {
        return $this->hasMany(CaseStudy::class, 'author_id');
    }
}
