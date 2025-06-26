<?php

namespace App\Http\Controllers;

use App\Models\Service;

use App\Models\WhmServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process as SymfonyProcess;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Services\RefundCalculator;
use App\Services\WhmApiService;
use App\Notifications\CpanelPasswordChanged;
use App\Http\Controllers\Controller;
use App\Models\WhmUptimeLog;




class ServiceSettingsController extends Controller
{
public function settings($id)
{
    $service = Service::with(['plan', 'user'])->findOrFail($id);
    $wordpressInstalled = $service->wordpress_installed ?? false;

    $serverId = $service->whm_server_id;

    // ✅ 실시간 상태
    $latestLog = WhmUptimeLog::where('whm_server_id', $serverId)
        ->latest('collected_at')
        ->first();

    $latestStatus = $latestLog?->status ?? 'unknown';
    $latestCollectedAt = $latestLog?->collected_at;

    // ✅ 30일간 로그 그룹핑
    $rawLogs = WhmUptimeLog::where('whm_server_id', $serverId)
        ->where('collected_at', '>=', now()->subDays(30))
        ->get()
        ->groupBy(function ($log) {
            return \Carbon\Carbon::parse($log->collected_at)->format('Y-m-d');
        });

    $uptimeData = [];

    foreach ($rawLogs as $date => $logs) {
        $total = $logs->count();
        $up = $logs->where('status', 'up')->count();
        $uptimePercent = $total > 0 ? round(($up / $total) * 100, 1) : 0;
        $uptimeData[] = [
            'date' => $date,
            'percent' => $uptimePercent,
        ];
    }

    return view('services.settings', [
        'service' => $service,
        'wordpress_installed' => $wordpressInstalled,
        'uptimeData' => collect($uptimeData)->sortBy('date')->values(),

        // ✅ 새로 추가한 항목들
        'latestStatus' => $latestStatus,
        'latestCollectedAt' => $latestCollectedAt,
    ]);
}






public function checkWordPress($id)
{
    $service = Service::findOrFail($id);

    return response()->json([
        'installed' => $service->wordpress_installed,
        'version' => $service->wordpress_version,
    ]);
}

public function installWordPress(Request $request, $id)
{
    Log::info('🟡 installWordPress() 진입', ['id' => $id]);

    $request->validate([
        'wp_version' => 'required|string',
    ]);

    $service = Service::with('whmServer')->findOrFail($id);
    $whmServer = $service->whmServer;

    $ip = $whmServer->ip_address;
    $port = env('SSH_PORT', '49999');

$cpUser = $service->whm_username;
$path = "/home/{$cpUser}/public_html";
$versions = config('wordpress.versions');
$zipUrl = $versions[$request->wp_version] ?? null;

Log::info('💡 선택된 버전', ['version' => $request->wp_version]);
Log::info('📦 다운로드할 워드프레스 ZIP URL', ['url' => $zipUrl]);

if (!$zipUrl) {
    return back()->with('error', '워드프레스 버전 zip URL이 존재하지 않습니다.');
}
    

    $process = new Process([
    '/var/www/run-wp-install.sh',
    $ip,
    $port,
    $cpUser,
    $zipUrl
]);
    $process->run();

    Log::info('🧾 설치 명령 실행 결과', [
    'output' => $process->getOutput(),
    'error' => $process->getErrorOutput()
]);

    if ($process->isSuccessful()) {
        Log::info('✅ 워드프레스 설치 완료', ['output' => $process->getOutput()]);
            $service->update([
        'wordpress_installed' => true,
        'wordpress_version' => $request->wp_version,
    ]);
        return back()->with('success', '워드프레스가 성공적으로 설치되었습니다.');
    } else {
        Log::error('❌ 워드프레스 설치 실패', ['error' => $process->getErrorOutput()]);
        return back()->with('error', '설치 중 오류가 발생했습니다.');
    }
}


public function refundForm($id)
{
    $service = Service::with(['plan', 'payment'])->findOrFail($id);
    $calc = RefundCalculator::calculate($service);

    return view('services.refund', array_merge($calc, [
    'service' => $service,
    'plan'    => $service->plan,
    'isEligible' => $calc['isEligible'], // ✅ 여기가 핵심!
    'calc' => $calc, // ✅ 요거 추가해야 에러 안남!
    
    
]));
}

public function processRefund(Request $request, $id)
{
    \Log::info('🟠 환불 요청 접수됨', ['id' => $id, 'reason' => $request->reason]);

    $service = Service::with(['plan', 'payment'])->findOrFail($id);
    \Log::info('🧾 서비스 로드 완료', ['service_id' => $service->id]);

    $calc = RefundCalculator::calculate($service);
    \Log::info('🧮 환불 계산 완료', ['calc' => $calc]);

if ($service->plan->price == 0 || (!$calc['isEligible'] && $calc['durationDays'] <= 31)) {
        \Log::warning('⛔️ 환불 불가 조건');
        return back()->with('error', '환불 조건을 만족하지 않습니다.');
    }

    \Log::info('🚀 Toss 환불 시작 시도');

    $toss = app(\App\Services\TossPaymentService::class);
    $result = $toss->cancelPayment(
        $service->payment->payment_key,
        $request->reason ?? '사용자 환불 요청',
        $calc['refundable']
    );

    \Log::info('📩 Toss 환불 응답 수신', ['result' => $result]);

  

   if (isset($result['status']) && in_array($result['status'], ['CANCELED', 'PARTIAL_CANCELED'])) {
    $service->payment->update([
        'status' => $result['status'],
        'refund_reason' => $request->reason ?? '사용자 환불 요청',
            'refunded_amount' => $calc['refundable'], // ← 이 줄 추가

    ]);

    $service->update(['status' => 'canceled']);

    return back()->with('success', '환불이 완료되었습니다.');
}

    return back()->with('error', '환불 처리에 실패했습니다.');
}

public function updatePassword(Request $request, $id)
{
    // ✅ 1. 서버 측 유효성 검사
    $request->validate([
        'new_password' => [
            'required',
            'string',
            'min:8',
            'max:32',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
        ],
    ], [
        'new_password.regex' => '비밀번호는 대문자, 소문자, 숫자, 특수문자를 포함해야 합니다.',
    ]);

            // ✅ 2. 서비스 불러오기
    $service = Service::with('whmServer')->findOrFail($id);
    $whm = new WhmApiService($service->whmServer);
   
    $result = $whm->changeCpanelPassword($service->whm_username, $request->new_password);

    if ($result['success']) {


    // ✅ 3. WHM 서버에 비밀번호 변경
    


        // ✅ 4. DB에도 암호화하여 저장
        $service->whm_password = Crypt::encryptString($request->new_password);
        $service->save();

        // ✅ 5. 사용자에게 알림 메일 발송
        $service->user->notify(new CpanelPasswordChanged($service->whm_domain));

        return back()->with('success', '✅ 비밀번호가 변경되었습니다. 이메일로도 안내되었습니다.');
    }else {
        Log::error('WHM 비밀번호 변경 실패', [
            'user_id' => auth()->id(),
            'whm_username' => $service->whm_username,
            'response' => $result,
        ]);

        return back()->with('error', '❌ 비밀번호 변경에 실패했습니다. 다시 시도해주세요.');
    }

}

}
