<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceExtension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Log;

class ServiceExtensionController extends Controller
{
    // 연장 요청 화면 (확인 및 결제 버튼)
    public function request(Request $request, $id)
{
    $service = Service::with('plan')->findOrFail($id);

    $period = (int) $request->input('period');
    $pricePerMonth = $service->plan->price;

    $discountRate = match ($period) {
        3 => 0.98,
        6 => 0.96,
        12 => 0.90,
        default => 1.0,
    };

    $amount = floor($pricePerMonth * $period * $discountRate / 10) * 10;
    $orderId = $orderId = 'extend_' . uniqid();
    $customerKey = auth()->id() . '-' . $service->id;

    return view('services.confirm-extend', [
        'service' => $service,
        'period' => $period,
        'amount' => $amount,
        'orderId' => $orderId,
        'customerKey' => $customerKey,
        'failUrl' => route('services.extend.fail', ['id' => $id]),
    ]);
}

    // 결제 승인 및 연장 처리
public function confirm(Request $request, $id)
{
    $service = Service::with('plan')->findOrFail($id);

    $paymentKey = $request->input('paymentKey');
    $orderId = $request->input('orderId'); // ✅ 이걸로 수정
    $amount     = (int) $request->input('amount');
    $period     = (int) $request->input('period');

    // ✅ 1. 결제 승인 요청 (Toss API)
    $response = Http::withBasicAuth(config('services.toss.secret_key'), '')
        ->post('https://api.tosspayments.com/v1/payments/confirm', [
            'paymentKey' => $paymentKey,
            'orderId'    => $orderId,
            'amount'     => $amount,
        ]);

    if (!$response->ok()) {
        return redirect()
            ->route('services.settings', $service->id)
            ->with('error', '결제 승인 실패: ' . $response->json('message'));
    }

    // ✅ 2. 결제 금액 검증
    $pricePerMonth = $service->plan->price;
    $discountRate = match ($period) {
        3 => 0.98,
        6 => 0.96,
        12 => 0.90,
        default => 1.0,
    };
    $expectedAmount = round($pricePerMonth * $period * $discountRate, -1);

    if (abs($amount - $expectedAmount) > 1) {
        Log::warning('금액 위조 감지', [
    'amount' => $amount,
    'expectedAmount' => $expectedAmount,
    'pricePerMonth' => $pricePerMonth,
    'period' => $period,
    'discountRate' => $discountRate,
    'service_id' => $service->id,
    'orderId' => $orderId,
]);
        abort(403, '결제 금액 위조가 감지되었습니다.');
    }

    // ✅ 3. 서비스 연장 처리
    $paidAt = Carbon::parse($response['approvedAt']);
    $newExpireAt = $service->expired_at && $service->expired_at->gt(now())
        ? $service->expired_at->addMonths($period)
        : now()->addMonths($period);

    $service->update(['expired_at' => $newExpireAt]);

    // ✅ 4. 연장 기록 저장
    ServiceExtension::create([
        'service_id' => $service->id,
        'period'     => $period,
        'amount'     => $amount,
        'payment_id' => $paymentKey,
        'paid_at'    => $paidAt,
    ]);

    // ✅ 5. 결제 내역 저장 (optional)
    $service->payments()->create([
    'user_id'     => $service->user_id,
    'service_id'  => $service->id,
    'plan_id'     => $service->plan_id, // ← 이 줄 추가
    'amount'      => $amount,
    'order_id'    => $orderId,
    'payment_key' => $paymentKey,
    'status'      => 'paid',
    'paid_at'     => $paidAt,
]);

    // ✅ 6. 뷰 렌더링
    return redirect()->route('services.extend.complete', ['id' => $service->id]);
}

public function complete($id)
{
    $service = Service::findOrFail($id);

    return view('services.extend-complete', [
        'service' => $service,
    ]);
}
}
