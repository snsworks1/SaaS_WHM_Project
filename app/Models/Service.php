<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
    'user_id',
    'plan_id',
    'whm_username',
    'whm_domain',
    'whm_server_id',
    'expired_at',
    'status',
    'dns_record_id',       // 이미 추가된 필드
    'whm_password'         // ✅ 여기에 추가해야 저장됨
];
    protected $dates = [
        'started_at',
        'expired_at'
    ];

    protected $casts = [
        'started_at' => 'date',
        'expired_at' => 'date',
    ];
    

    // 서비스 → 유저 연결
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 서비스 → 플랜 연결
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // 서비스 → 서버 연결
    public function whmServer()
    {
        return $this->belongsTo(WhmServer::class, 'whm_server_id');
    }

    // 상태 자동 계산 (만료처리)
    public function getStatusAttribute()
    {
        if (is_null($this->expired_at)) {
            return 'unknown';
        }

        $daysLeft = now()->diffInDays($this->expired_at, false);

        if ($daysLeft >= 3) {
            return 'active';
        } elseif ($daysLeft >= 0) {
            return 'warning';
        } else {
            return 'expired';
        }
    }
    public function getDaysLeftAttribute()
{
    if (!$this->expired_at) {
        return null;
    }

    return now()->diffInDays($this->expired_at, false);
}

}
