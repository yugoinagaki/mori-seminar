<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnualTheme extends Model
{
    protected $fillable = ['year', 'title', 'content', 'photo_url'];
}
