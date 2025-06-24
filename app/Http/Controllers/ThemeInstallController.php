<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Theme;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;


class ThemeInstallController extends Controller
{
    public function install(Request $request, $serviceId, $themeId)
    {
       

        $service = Service::with('whmServer')->findOrFail($serviceId);
        $theme = Theme::findOrFail($themeId);
        $whmServer = $service->whmServer;

        $ip = $whmServer->ip_address;
        $port = env('SSH_PORT', '49999');
        $cpUser = $service->whm_username;


             // 1️⃣ 이미 설치 여부 확인
    $check = new Process([
        '/var/www/check-theme-installed.sh',
        $ip,
        $port,
        $cpUser,
        $theme->name
    ]);
    $check->run();

    $alreadyInstalled = trim($check->getOutput()) === 'installed';

    if ($alreadyInstalled) {
        return response()->json([
            'status' => 'exists',
            'message' => '이미 설치된 테마입니다.',
        ]);
    }

        // 테마 폴더명 (themes 테이블의 name 컬럼을 폴더명으로 사용)
        $themeFolder = $theme->name;

        // 외부 접근 가능한 ZIP 다운로드 링크
        $zipUrl = asset('storage/' . $theme->zip_path); // 예: /storage/themes/theme1.zip

        if (!$theme->zip_path) {
            Log::error('[zip_path가 비어있음]', ['theme_id' => $theme->id]);
            return response()->json(['status' => 'error', 'message' => '테마 zip 경로가 비어 있습니다.'], 400);
        }

        Log::info('🛠 설치 명령 실행', [
            'ip' => $ip,
            'cpUser' => $cpUser,
            'themeFolder' => $themeFolder,
            'zipUrl' => $zipUrl,
        ]);

        $process = new Process([
    '/var/www/run-theme-install.sh',
    $ip,
    $port,
    $cpUser,
    $themeFolder,
    $zipUrl,
]);


        $process->run();

        Log::info('🧾 설치 명령 실행 결과', [
            'output' => $process->getOutput(),
            'error' => $process->getErrorOutput()
        ]);

        if ($process->isSuccessful()) {
            Log::info('✅ 테마 설치 완료');
            return response()->json(['status' => 'success']);
        } else {
            Log::error('[테마 설치 실패]', [
                'service_id' => $serviceId,
                'theme_id' => $themeId,
                'error' => $process->getErrorOutput(),
            ]);
            return response()->json(['status' => 'error', 'message' => '설치 실패'], 500);
        }
    }


    
}
