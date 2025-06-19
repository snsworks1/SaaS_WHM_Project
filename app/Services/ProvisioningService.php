<?php
namespace App\Services;

use App\Models\Service;
use App\Services\WhmApiService;
use App\Services\CloudflareService;
use Illuminate\Support\Facades\Log;
use App\Models\Plan;


class ProvisioningService
{
    
    public function terminateService(Service $service)
    {
        // 1. WHM 계정 삭제
        try {
            $whmApi = new WhmApiService($service->whmServer);
            $whmApi->deleteAccount($service->whm_username);
            Log::info("🗑️ WHM 계정 삭제 완료", ['username' => $service->whm_username]);
        } catch (\Exception $e) {
            Log::error("❌ WHM 계정 삭제 실패", ['error' => $e->getMessage()]);

            ErrorLog::create([
    'level' => 'error',
    'type' => 'whm_delete',
    'title' => 'WHM 계정 삭제 실패',
    'message' => $e->getMessage(),
    'file_path' => __FILE__ . ':' . __LINE__,
    'occurred_at' => now(),
    'server_id' => $service->whmServer->id ?? null,
    'whm_username' => $service->whm_username,
]);
        }

        // 2. DNS 레코드 삭제
        if ($service->dns_record_id) {
            try {
                app(CloudflareService::class)->deleteDnsRecord(
                    $service->whm_domain,
                    $service->dns_record_id
                );
            } catch (\Exception $e) {
                Log::error("❌ Cloudflare DNS 삭제 실패", ['error' => $e->getMessage()]);

                ErrorLog::create([
                    'level' => 'error',
                    'type' => 'dns_delete',
                    'title' => 'DNS 레코드 삭제 실패',
                    'message' => $e->getMessage(),
                    'file_path' => __FILE__ . ':' . __LINE__,
                    'occurred_at' => now(),
                    'server_id' => $service->whmServer->id ?? null,
                    'whm_username' => $service->whm_username,
                ]);
            }
        }

        // 3. 서비스 레코드 삭제
        try {
            $service->delete();
            Log::info("✅ 서비스 레코드 삭제 완료", ['service_id' => $service->id]);
        } catch (\Exception $e) {
            Log::error("❌ 서비스 레코드 삭제 실패", ['error' => $e->getMessage()]);

            ErrorLog::create([
                'level' => 'error',
                'type' => 'service_delete',
                'title' => '서비스 DB 레코드 삭제 실패',
                'message' => $e->getMessage(),
                'file_path' => __FILE__ . ':' . __LINE__,
                'occurred_at' => now(),
                'server_id' => $service->whmServer->id ?? null,
                'whm_username' => $service->whm_username,
            ]);
        }

                $server = $service->whmServer;
        $plan = $service->plan;

        if ($server && $plan && $plan->disk_size) {
            $server->used_disk_capacity = max(0, $server->used_disk_capacity - $plan->disk_size);
            $server->save();

            Log::info('📉 WHM 서버 디스크 사용량 차감', [
                'server_id' => $server->id,
                'used' => $server->used_disk_capacity,
                'minus' => $plan->disk_size,
            ]);
        }


    }
}
