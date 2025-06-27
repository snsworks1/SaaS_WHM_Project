<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function report(Throwable $exception): void
    {
        try {
            if (app()->environment('production')) {
                \App\Models\ErrorLog::create([
                    'level'        => 'high',
                    'type'         => 'server',
                    'title'        => $exception->getMessage(),
                    'file_path'    => $exception->getFile(),
                    'occurred_at'  => now(),
                    'server_id'    => optional(auth()->user())->server_id,
                    'whm_username' => optional(auth()->user())->whm_username,
                    'resolved'     => false,
                    'resolved_at'  => null,
                ]);
            }
        } catch (\Throwable $e) {
            \Log::error('[ErrorLog] 저장 실패: ' . $e->getMessage());
        }

        parent::report($exception);
    }

    public function register(): void
    {
        // 아무것도 작성하지 않아도 됨
    }
}
