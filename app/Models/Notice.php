<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
    'importance',
    'category',
    'title',
    'content',
    'is_pinned',
];

}
