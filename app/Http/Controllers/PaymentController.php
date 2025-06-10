<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\Payment;
use App\Services\WhmApiService;
use App\Services\WhmServerPoolService;
use App\Services\CloudflareService;


class PaymentController extends Controller
{
    public function confirmGet(Request $request)
    {
        $planId = $request->plan_id;
        $username = $request->username;
        $password = $request->password;
        $orderId = $request->order_id;
        $paymentKey = $request->paymentKey;

        // 1. 결제 중복 방지
        if (Payment::where('order_id', $orderId)->exists()) {
            return view('checkout.confirm'); // 중복되면 그냥 완료 페이지로
        }

        // 2. 결제 정보 기록
        Payment::create([
            'user_id'     => auth()->id(),
            'plan_id'     => $planId,
            'order_id'    => $orderId,
            'payment_key' => $paymentKey,
            'amount'      => Plan::find($planId)->price,
            'status'      => 'PAID',
            'approved_at' => now(),
        ]);

        // 3. 사용자 플랜 적용
        $user = auth()->user();
        $user->update(['plan_id' => $planId]);

        // 4. WHM 서버 선택
        $plan = Plan::find($planId);
        \Log::info('💡 WHM 계정 생성에 사용될 플랜', ['plan' => $plan]); // ← 로그 확인용 (추가해도 되고 안해도 됨)

        $server = app(WhmServerPoolService::class)->selectAvailableServer($plan->disk_size);
        if (!$server) {
            \Log::error('⚠️ WHM 서버 풀에서 사용 가능한 서버를 찾을 수 없음');
            abort(500, '서버 용량이 부족합니다.');
        }

        // 5. WHM 계정 생성
        $domain = "{$username}.cflow.dev";
        $email = $user->email;

        $whm = new WhmApiService($server);
        $result = $whm->createAccount($domain, $username, $password, $plan->name, $email);
        

        \Log::info('✅ WHM 계정 생성 결과', ['response' => $result]);


            // ✅ DNS 레코드 생성
app(CloudflareService::class)->createDnsRecord($domain, $server->ip_address);

        // 6. 서비스 기록 (services 테이블에)
        \App\Models\Service::create([
            'user_id'        => $user->id,
            'plan_id'        => $plan->id,
            'whm_username'   => $username,
            'whm_domain'     => $domain,
            'whm_server_id'  => $server->id,
            'expired_at'     => now()->addMonth(),
            'status'         => 'active',
        ]);

return view('checkout.confirm', [
    'orderId' => $orderId,
    'amount' => Plan::find($planId)->price,
    'planName' => Plan::find($planId)->name,
    'domain' => "{$username}.cflow.dev",
    'email' => $user->email,
]);
 }
}
