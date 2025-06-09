<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Service;
use App\Services\WhmApiService;
use Carbon\Carbon;

class ProcessServiceExpiration extends Command
{
    protected $signature = 'services:process-expiration';
    protected $description = '서비스 만료 상태 처리';

    public function handle()
    {
        $now = Carbon::now();

        // 전체 서비스 불러오기
        $services = Service::with('whmServer')->get();

        foreach ($services as $service) {
            $expiredAt = Carbon::parse($service->expired_at);
            $daysAfterExpired = $now->diffInDays($expiredAt, false);  // 음수 가능

            $server = $service->whmServer;
            $whmApi = new WhmApiService($server);

            if ($daysAfterExpired < -3) {
                // 3일 초과 → WHM 계정 삭제
                $this->info("Deleting account for {$service->whm_username}");
                $whmApi->deleteAccount($service->whm_username);
                $service->delete();
            } 
            elseif ($daysAfterExpired < -2) {
                // 2일 초과 → WHM 계정 일시정지
                if ($service->status != 'suspended') {
                    $this->info("Suspending account for {$service->whm_username}");
                    $whmApi->suspendAccount($service->whm_username);
                    $service->status = 'suspended';
                    $service->save();
                }
            } 
            elseif ($daysAfterExpired >= -2) {
                // 아직 만료전 또는 정지 해제 필요
                if ($service->status == 'suspended') {
                    $this->info("Unsuspending account for {$service->whm_username}");
                    $whmApi->unsuspendAccount($service->whm_username);
                    $service->status = 'active';
                    $service->save();
                }
            }
        }
    }
}
