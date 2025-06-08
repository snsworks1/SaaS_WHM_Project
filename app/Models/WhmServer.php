<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhmServer extends Model
{
    protected $fillable = [
    'name',
    'api_url',
    'api_token',
    'username',
    'total_disk_capacity',
    'used_disk_capacity',
];
}
