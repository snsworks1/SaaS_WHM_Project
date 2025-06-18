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


class ServiceSettingsController extends Controller
{
    public function settings($id)
    {
        $service = Service::with(['plan', 'user'])->findOrFail($id);
        $wordpressInstalled = $service->wordpress_installed ?? false;

        return view('services.settings', [
            'service' => $service,
            'wordpress_installed' => $wordpressInstalled,
        ]);
    }


public function checkWordPress($id)
{
    $service = Service::with('whmServer')->findOrFail($id);
    $whmServer = $service->whmServer;

    $ip = $whmServer->ip_address;
    $port = env('SSH_PORT', '49999');
    $cpUser = $service->whm_username;
    $path = "/home/{$cpUser}/public_html";
    $command = "wp core version --path={$path} --allow-root";

    $process = new \Symfony\Component\Process\Process([
        '/var/www/run-wp-check.sh',
        $ip,
        $port,
        $cpUser,
        $command
    ]);
    $process->run();

    if ($process->isSuccessful()) {
        $output = trim($process->getOutput());
        Log::info('✅ wp version 확인 성공', ['output' => $output]);
        return response()->json(['installed' => true, 'version' => $output]);
    } else {
        Log::error('❌ wp version 확인 실패', ['error' => $process->getErrorOutput()]);
        return response()->json(['installed' => false]);
    }
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
    ]));
}

public function processRefund(Request $request, $id)
{
        \Log::info('🟠 환불 요청 접수됨', ['id' => $id, 'reason' => $request->reason]);
    $service = Service::with(['plan', 'payment'])->findOrFail($id);
    $calc = RefundCalculator::calculate($service);

    if ($service->plan->price == 0 || !$calc['isEligible']) {
        return back()->with('error', '환불 조건을 만족하지 않습니다.');
    }

    $toss = app(\App\Services\TossPaymentService::class);
    $result = $toss->cancelPayment($service->payment->payment_key, $request->reason ?? '사용자 환불 요청', $calc['refundable']);

    if (isset($result['status']) && $result['status'] === 'CANCELED') {
        $service->payment->update(['status' => 'CANCELED']);
        $service->update(['status' => 'canceled']);
        return back()->with('success', '환불이 완료되었습니다.');
    }

    return back()->with('error', '환불 처리에 실패했습니다.');
}


}
