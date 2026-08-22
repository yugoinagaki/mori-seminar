<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name', 'cohort_id', 'position', 'bio',
        'profile_image_url', 'order_index',
    ];

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }

    public function caseStudies()
    {
        return $this->hasMany(CaseStudy::class, 'author_id');
    }
}
