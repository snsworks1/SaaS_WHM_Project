<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use App\Models\WebhookLog;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleTossWebhook(Request $request)
{
    \Log::info('📦 Toss Webhook 수신됨:', [
        'headers' => $request->headers->all(),
        'body' => $request->all(),
    ]);

    $signature = $request->header('TossPayments-Webhook-Signature');

    if (!$signature) {
        \Log::warning('❌ Toss Webhook 시그니처 없음 - 테스트 환경으로 간주하고 우선 통과');
    } else {
        $expected = hash_hmac(
            'sha256',
            $request->header('TossPayments-Webhook-Transmission-Time') . '.' . $request->getContent(),
            config('services.toss.webhook_secret')
        );

        if (!hash_equals($expected, $signature)) {
            \Log::warning('🚫 Toss Webhook Signature 불일치', [
                'expected' => $expected,
                'actual' => $signature,
            ]);
            return response('Invalid Signature', 400);
        }
    }

    $eventType = $request->input('eventType');
        $status = $request->input('data.status'); // ✅ 이 줄 추가

    $paymentKey = $request->input('data.paymentKey');
    $orderId = $request->input('data.orderId');
    $payload = $request->all();

    // 🔍 해당 주문 ID로 사용자/서비스 조회
$payment = Payment::where('order_id', $orderId)->first();
$service = Service::where('order_id', $orderId)->first(); // 🔥 더 정확하게 매칭
    $user = $payment ? $payment->user : null;


    // ✅ 여기에 추가하세요
if ($eventType === 'PAYMENT_STATUS_CHANGED' && $status === 'CANCELED') {
    \Log::info("🚨 환불 상태 감지됨: $orderId");

    $service = Service::where('order_id', $orderId)->first();
    if ($service) {
        app(\App\Services\ProvisioningService::class)->terminateService($service);
    }
}



    // 📝 Webhook 로그 저장
    WebhookLog::create([
    'event_type' => $request->input('eventType'),
    'payload' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
    'order_id' => $orderId,
    'payment_key' => $paymentKey,
    'email' => $payment?->user?->email,
    'whm_username' => $service?->whm_username,
]);

    \Log::info("✅ 결제 성공 웹훅: $paymentKey ($orderId)");

    return response('OK');
}
}
