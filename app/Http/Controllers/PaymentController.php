<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\Payment;
use App\Services\WhmApiService;
use App\Services\WhmServerPoolService;
use App\Services\CloudflareService;
use Illuminate\Support\Facades\Crypt;

class PaymentController extends Controller
{
    public function confirmGet(Request $request)
{
    $planId      = $request->plan_id;
    $period      = (int) $request->get('period', 1);
    $discountRate = match ($period) {
        3 => 0.02,
        6 => 0.04,
        12 => 0.10,
        24 => 0.20,
        default => 0,
    };

    $username    = $request->username;
    $password    = $request->password;
    $orderId     = $request->order_id;
    $paymentKey  = $request->paymentKey;

    // ✅ 플랜 정보와 금액 먼저 구하기
    $plan         = Plan::findOrFail($planId);
    $totalAmount  = round($plan->price * $period * (1 - $discountRate));

    // 1. 결제 중복 방지
    if (Payment::where('order_id', $orderId)->exists()) {
        return view('checkout.confirm');
    }

    // 2. 결제 정보 기록
    Payment::create([
        'user_id'     => auth()->id(),
        'plan_id'     => $planId,
        'order_id'    => $orderId,
        'payment_key' => $paymentKey,
        'amount'      => $totalAmount,
        'status'      => 'PAID',
        'approved_at' => now(),
    ]);

    // 3. 사용자 플랜 적용
    $user = auth()->user();
    $user->update(['plan_id' => $planId]);

    // 4. WHM 서버 선택
    \Log::info('💡 WHM 계정 생성에 사용될 플랜', ['plan' => $plan]);
    $server = app(WhmServerPoolService::class)->selectAvailableServer($plan->disk_size);
    if (!$server) {
        \Log::error('⚠️ WHM 서버 풀에서 사용 가능한 서버를 찾을 수 없음');
        abort(500, '서버 용량이 부족합니다.');
    }

    // 5. WHM 계정 생성
    $domain = "{$username}.hostyle.me";
    $email  = $user->email;
    $whm    = new WhmApiService($server);
    $result = $whm->createAccount($domain, $username, $password, $plan->name, $email);
    \Log::info('✅ WHM 계정 생성 결과', ['response' => $result]);

    // 6. DNS 레코드 생성
  $cloudflare = new CloudflareService();
    $dnsRecordId = $cloudflare->createDnsRecord($domain, $server->ip_address);

    if (!$dnsRecordId) {
        return [false, 'Cloudflare DNS 생성 실패'];
    }
    // 7. 서비스 기록
    \App\Models\Service::create([
        'user_id'        => $user->id,
        'plan_id'        => $plan->id,
        'whm_username'   => $username,
        'whm_domain'     => $domain,
        'whm_server_id'  => $server->id,
        'expired_at'     => now()->addMonths($period),
        'status'         => 'active',
            'dns_record_id'  => $dnsRecordId, // ✅ 이 값이 실제로 들어가야 함
            'whm_password'   => Crypt::encryptString($password), // ✅ 추가
    ]);

    // 8. 완료 페이지로
    return view('checkout.confirm', [
        'orderId'  => $orderId,
        'amount'   => $totalAmount,
        'planName' => $plan->name,
        'domain'   => $domain,
        'email'    => $user->email,
        'period'   => $period,
        'disk'     => $plan->disk_size,
    ]);
}

}
