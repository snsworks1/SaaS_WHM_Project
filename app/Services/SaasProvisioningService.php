<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\User;
use App\Services\WhmApiService;
use App\Services\WhmServerPoolService;

class SaasProvisioningService
{
    public function provision(User $user, $whmUsername, $plainPassword)
{
    $plan = Plan::findOrFail($user->plan_id);
    $serverPool = new WhmServerPoolService();
    $server = $serverPool->selectAvailableServer($plan->disk_size);

    if (!$server) {
        return [false, '사용 가능한 WHM 서버가 없습니다.'];
    }

    $whmApi = new WhmApiService($server);
    $domain = "{$whmUsername}.cflow.dev";

    // 패키지명: 플랜명 그대로 적용 (ex: Basic, Pro)
    $package = $plan->name;

    $response = $whmApi->createAccount(
        $domain,
        $whmUsername,
        $plainPassword,
        $package,   // ✅ 여기 적용됨
        $user->email
    );

    $result = $response['result'][0] ?? null;
    if ($result && $result['status'] == 1) {
        $user->whm_server_id = $server->id;
        $user->whm_username = $whmUsername;
        $user->whm_domain = $domain;
        $user->save();

        $server->used_disk_capacity += $plan->disk_size;
        $server->save();

        return [true, $result['rawout'] ?? 'WHM 계정 생성 성공'];
    } else {
        return [false, $result['statusmsg'] ?? json_encode($response)];
    }
}

}
