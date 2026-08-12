<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    protected $fillable = ['user_id', 'model_type', 'model_id', 'field', 'value'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
