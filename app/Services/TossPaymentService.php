<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TossPaymentService
{
    protected $baseUrl = 'https://api.tosspayments.com/v1';

    public function confirmPayment($paymentKey, $orderId, $amount)
    {
        $response = Http::withBasicAuth(config('services.toss.secret_key'), '')
            ->post("{$this->baseUrl}/payments/{$paymentKey}", [
                'orderId' => $orderId,
                'amount' => $amount,
            ]);

        return $response->json();
    }

    public function cancelPayment($paymentKey, string $reason = 'User Requested', int $amount)
{
 \Log::info('📤 Toss 환불 함수 호출됨', [
        'paymentKey' => $paymentKey,
        'reason' => $reason,
        'amount' => $amount,
    ]);

    
    $response = Http::withBasicAuth(config('services.toss.secret_key'), '')
        ->post("{$this->baseUrl}/payments/{$paymentKey}/cancel", [
            'cancelReason' => $reason,
            'cancelAmount' => $amount,
        ]);

    if ($response->successful()) {
        \Log::info('✅ Toss 환불 성공', ['response' => $response->json()]);
        return $response->json();
    } else {
        \Log::error('❌ Toss 환불 실패', [
            'status' => $response->status(),
            'body' => $response->body(),
            'paymentKey' => $paymentKey,
            'amount' => $amount,
        ]);
        return ['error' => 'cancel_failed'];
    }
}

        public function getReceiptByOrderId(string $orderId): ?array
{
    $response = Http::withBasicAuth(config('services.toss.secret_key'), '')
        ->get("https://api.tosspayments.com/v1/payments/orders/{$orderId}");

    \Log::info('Toss API Response', [
        'orderId' => $orderId,
        'status' => $response->status(),
        'body' => $response->body(),
    ]);

    if ($response->successful()) {
        return $response->json();
    }

    return null;
}
}
