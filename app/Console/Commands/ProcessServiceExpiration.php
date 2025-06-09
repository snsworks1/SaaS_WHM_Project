<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Service;
use App\Services\WhmApiService;
use Carbon\Carbon;
use App\Services\CloudflareService;

class ProcessServiceExpiration extends Command
{
    protected $signature = 'services:process-expiration';
    protected $description = '서비스 만료 상태 처리';

    public function handle()
    {
        $now = Carbon::now();

        $services = Service::with('whmServer')->get();

        foreach ($services as $service) {
            $expiredAt = Carbon::parse($service->expired_at);
            $daysAfterExpired = $now->diffInDays($expiredAt, false);

            $server = $service->whmServer;
            $whmApi = new WhmApiService($server);

            if ($daysAfterExpired < -3) {
                $this->info("Deleting account for {$service->whm_username}");
                $whmApi->deleteAccount($service->whm_username);

                // ✅ Cloudflare DNS 삭제 추가
                if ($service->dns_record_id) {
                    try {
                        $cloudflare = new CloudflareService();
                        $cloudflare->deleteDnsRecord($service->dns_record_id);
                    } catch (\Exception $e) {
                        \Log::error('Cloudflare DNS 삭제 실패', ['error' => $e->getMessage()]);
                    }
                }

                $service->delete();
            } 
            elseif ($daysAfterExpired < -2) {
                if ($service->status != 'suspended') {
                    $this->info("Suspending account for {$service->whm_username}");
                    $whmApi->suspendAccount($service->whm_username);
                    $service->status = 'suspended';
                    $service->save();
                }
            } 
            elseif ($daysAfterExpired >= -2) {
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
