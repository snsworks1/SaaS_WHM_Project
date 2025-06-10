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

    public function cancelPayment($paymentKey, $reason = 'User Requested')
    {
        return Http::withBasicAuth(config('services.toss.secret_key'), '')
            ->post("{$this->baseUrl}/payments/{$paymentKey}/cancel", [
                'cancelReason' => $reason,
            ])->json();
    }
}
