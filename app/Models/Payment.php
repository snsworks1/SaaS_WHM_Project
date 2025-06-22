<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'order_id',
        'payment_key',
        'amount',
        'status',
        'approved_at',
        'service_id',
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