<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    // 만약 테이블명이 `plans` 그대로라면 이 부분은 생략 가능
    protected $table = 'plans';

    // 수정 가능한 컬럼 지정 (mass assignment)
protected $fillable = [
    'name',
    'price',
    'disk_size',
    'description',
    'ftp_accounts',
    'email_accounts',
    'sql_databases',
    'mailing_lists',
    'max_email_per_hour',
    'email_quota',
    'bandwidth', // ✅ 추가 필요
        'addon_domains', // ✅
    'subdomains',    // ✅
];

    public function services()
{
    return $this->hasMany(Service::class);
}

}
