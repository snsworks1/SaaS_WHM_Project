<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Service;
use App\Services\WhmApiService;
use App\Models\WhmServer;
use Carbon\Carbon;
use Log;

class CheckServiceExpiration extends Command
{
    protected $signature = 'services:check-expiration';
    protected $description = '만료된 서비스 자동 정지/삭제 처리';

    public function handle()
    {
        $now = Carbon::now();

        // 전체 서비스 검사
        Service::with(['plan', 'user', 'whmServer'])->chunk(100, function ($services) use ($now) {
            foreach ($services as $service) {
                $daysPassed = $now->diffInDays($service->expired_at, false);

                $whmApi = new WhmApiService($service->whmServer);

                if ($daysPassed == -2 && $service->status === 'active') {
                    // 자동정지
                    $whmApi->suspendAccount($service->whm_username);
                    $service->status = 'suspended';
                    $service->save();
                    Log::info("서비스 정지됨: {$service->id}");
                }

                if ($daysPassed <= -3 && $service->status !== 'terminated') {
                    // 자동삭제
                    $whmApi->terminateAccount($service->whm_username);
                    $service->status = 'terminated';
                    $service->save();
                    Log::info("서비스 삭제됨: {$service->id}");
                }
            }
        });

        $this->info('서비스 만료검사 완료');
    }
}
