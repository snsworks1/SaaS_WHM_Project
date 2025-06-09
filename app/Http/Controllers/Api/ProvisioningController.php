<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WhmApiService;
use App\Services\WhmServerPoolService;

class ProvisioningController extends Controller
{
    public function checkWhmUsername(Request $request)
    {
        $username = $request->input('username');

        $serverPool = new WhmServerPoolService();
        $server = $serverPool->selectAvailableServer(0); // 용량 상관없이 서버 선택

        if (!$server) {
            return response()->json(['available' => false, 'message' => 'WHM 서버를 찾을 수 없습니다.']);
        }

        $whmService = new WhmApiService($server);

        $exists = $whmService->accountExists($username);

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? '이미 사용 중인 아이디입니다.' : '사용 가능한 아이디입니다.'
        ]);
    }
}
