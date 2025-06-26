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
            'api_hostname', // ✅ 새 필드 추가
        'api_token',
        'username',
        'whm_user',
        'total_disk_capacity',
        'used_disk_capacity',
        'status',
         'root_auth_token', // 있으면 여기!
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'whm_server_id');
    }

    public function uptimeLogs()
{
    return $this->hasMany(WhmUptimeLog::class);
}

}
