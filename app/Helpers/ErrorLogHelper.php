<?php

namespace App\Helpers;

use App\Models\ErrorLog;
use Carbon\Carbon;

class ErrorLogHelper
{
    public static function log(
        string $level,
        string $type,
        string $title,
        string $filePath,
        ?int $serverId = null,
        ?string $whmUsername = null
    ): void {
        ErrorLog::create([
            'level'        => $level,
            'type'         => $type,
            'title'        => $title,
            'file_path'    => $filePath,
            'occurred_at'  => Carbon::now(),
            'server_id'    => $serverId,
            'whm_username' => $whmUsername,
            'resolved'     => false,
            'resolved_at'  => null,
        ]);
    }
}
