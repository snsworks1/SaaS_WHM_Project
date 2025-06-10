<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleTossWebhook(Request $request)
    {
        \Log::info('📦 Toss Webhook 수신됨:', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        // ✅ 시그니처 검증 (테스트 환경 대응 포함)
        $signature = $request->header('TossPayments-Webhook-Signature');

        if (!$signature) {
            \Log::warning('❌ Toss Webhook 시그니처 없음 - 테스트 환경으로 간주하고 우선 통과');
        } else {
            $expected = hash_hmac(
                'sha256',
                $request->header('TossPayments-Webhook-Transmission-Time') . '.' . $request->getContent(),
                config('services.toss.webhook_secret') // config/services.php 에 설정 필요
            );

            if (!hash_equals($expected, $signature)) {
                \Log::warning('🚫 Toss Webhook Signature 불일치', [
                    'expected' => $expected,
                    'actual' => $signature,
                ]);
                return response('Invalid Signature', 400);
            }
        }

        // ✅ 실제 처리 로직
        if ($request->input('eventType') === 'PAYMENT_STATUS_CHANGED') {
            $paymentKey = $request->input('data.paymentKey');
            $orderId = $request->input('data.orderId');
            \Log::info("✅ 결제 성공 웹훅: $paymentKey ($orderId)");

            // TODO: 서비스 생성 또는 갱신 처리
        }

        return response('OK');
    }
}
