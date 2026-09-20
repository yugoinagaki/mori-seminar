<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{
    protected $fillable = ['generation'];

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
