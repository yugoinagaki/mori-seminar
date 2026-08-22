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
}
