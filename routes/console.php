<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ⬇ 추가된 스케줄 등록
Schedule::command('collect:whm-uptime')->everyFiveMinutes();
Schedule::command('services:check-expiration')->dailyAt('00:00');

Schedule::call(function () {
    \App\Models\WhmUptimeLog::where('collected_at', '<', now()->subDays(40))->delete();
    \Log::info('[CRON] 40일 초과 업타임 로그 삭제');
})->dailyAt('01:00');