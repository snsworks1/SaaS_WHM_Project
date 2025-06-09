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
        'description',  // 만약 설명 컬럼이 있으면
        // 다른 컬럼 추가 가능
    ];

    public function services()
{
    return $this->hasMany(Service::class);
}

}
