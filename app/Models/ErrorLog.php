<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = [
        'level',
        'type',
        'title',
        'file_path',
        'occurred_at',
        'server_id',
        'whm_username',
        'resolved',
        'resolved_at',
    ];

    protected $dates = ['occurred_at', 'resolved_at'];

        protected $table = 'error_logs';

    protected $casts = [
        'occurred_at' => 'datetime',
        'resolved' => 'boolean',
    ];
}
