<?php

// app/Models/ServiceExtension.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceExtension extends Model
{
        protected $fillable = ['service_id', 'period', 'amount', 'payment_id', 'paid_at'];

// app/Models/ServiceExtension.php

public function service() { return $this->belongsTo(Service::class); }

}
