<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
    'user_id',
    'service_id',
    'plan_id',
    'order_id',
    'payment_key',
        'method',             // ✅ 추가

    'amount',
    'status',
    'refund_reason',
    'refunded_amount', // ✅ 이 줄 추가
    'receipt_url',
    'approved_at',
    'start_at',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    public function service()
{
    return $this->belongsTo(Service::class);
}
public function extension()
{
    return $this->hasOne(\App\Models\ServiceExtension::class, 'payment_id', 'payment_key');    

}

}