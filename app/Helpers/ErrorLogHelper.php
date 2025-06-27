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
        try {
        ErrorLog::create([
            'level'        => $level,
            'type'         => $type,
            'title'        => $title,
            'file_path'    => $filePath,
            'occurred_at'  => Carbon::now(),
            'server_id'    => $serverId,
            'whm_username' => $whmUsername,
            'resolved'     => 0, // 👈 수정
            'resolved_at'  => null,
        ]);
    } catch (\Throwable $e) {
        \Log::error('❌ [ErrorLogHelper::log] DB insert 실패: ' . $e->getMessage());
    }
    }
}
