<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhmUptimeLog extends Model
{
protected $fillable = [
    'whm_server_id',
    'collected_at',
    'status',
    'response_time_ms',
];
    public function server()
    {
        return $this->belongsTo(WhmServer::class);
    }
}
