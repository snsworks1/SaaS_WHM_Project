<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhmServer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ip_address',
        'api_url',
        'api_token',
        'username',
        'whm_user',
        'total_disk_capacity',
        'used_disk_capacity',
        'status'
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'whm_server_id');
    }
}
