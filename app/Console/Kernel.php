<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\CollectWhmUptime::class,
        \App\Console\Commands\CheckServiceExpiration::class,
    ];

    // ↓ 아래는 지워도 됩니다.
    // protected function schedule(Schedule $schedule) { ... }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
