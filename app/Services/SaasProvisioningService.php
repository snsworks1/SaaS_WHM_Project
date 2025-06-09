<?php

namespace App\Services;

use App\Models\User;
use App\Models\Plan;
use App\Models\Service; // <-- 새 서비스 모델
use App\Services\WhmApiService;
use App\Services\WhmServerPoolService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class SaasProvisioningService
{
    public function provision(User $user, Plan $plan, $whmUsername, $plainPassword)
{
    Log::info('SaaSProvisioningService 시작', [
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'whmUsername' => $whmUsername,
    ]);

    

    $serverPool = new WhmServerPoolService();
    $server = $serverPool->selectAvailableServer($plan->disk_size);

    if (!$server) {
        Log::error('서버풀에서 가용 서버 없음');
        return [false, '사용 가능한 WHM 서버가 없습니다.'];
    }

    Log::info('WHM 서버 선택됨', [
        'server_id' => $server->id
    ]);
    

    $whmApi = new WhmApiService($server);
    $domain = "{$whmUsername}.cflow.dev";

    Log::info('WHM 계정 생성 요청', [
        'domain' => $domain,
        'package' => $plan->name,
        'server_id' => $server->id
    ]);

    $response = $whmApi->createAccount(
        $domain,
        $whmUsername,
        $plainPassword,
        $plan->name,
        $user->email
    );

    Log::info('WHM API 응답', [
        'response' => json_encode($response)
    ]);

    $result = $response['result'][0] ?? null;

    if ($result && $result['status'] == 1) {
        Log::info('WHM 계정 생성 성공');

        Service::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'whm_username' => $whmUsername,
            'whm_domain' => $domain,
            'whm_server_id' => $server->id,
            'expired_at' => Carbon::now()->addMonth(),  // 예: 초기 1개월 부여
            'status' => 'active'
        ]);
        
        

        $server->used_disk_capacity += $plan->disk_size;
        $server->save();

        return [true, $result['rawout'] ?? 'WHM 계정 생성 성공'];
    } else {
        Log::error('WHM 계정 생성 실패', [
            'result' => $result
        ]);
        return [false, $result['statusmsg'] ?? json_encode($response)];
    }
}

}
