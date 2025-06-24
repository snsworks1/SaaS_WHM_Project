<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = ['name', 'zip_path', 'screenshots', 'plan_type', 'status'];


    protected $casts = [
        'screenshots' => 'array',
    ];
}
