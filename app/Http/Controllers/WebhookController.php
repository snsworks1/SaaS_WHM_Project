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
    $payload = $request->all();
    $eventType = $payload['eventType'] ?? '';
    $data = $payload['data'] ?? [];
    $orderId = $data['orderId'] ?? null;
    $status = $data['status'] ?? null;

    \Log::info('📦 Toss Webhook 수신됨', [
        'eventType' => $eventType,
        'status' => $status,
        'order_id' => $orderId,
        'headers' => $request->headers->all(),
        'body' => $payload,
    ]);

    if ($eventType === 'PAYMENT_STATUS_CHANGED' && in_array($status, ['CANCELED', 'PARTIAL_CANCELED'])) {
        \Log::info("🚨 환불 상태 감지됨: $orderId ($status)");

        $service = Service::where('order_id', $orderId)->first();

        if (!$service) {
            \Log::warning("❗ 일치하는 서비스(order_id: {$orderId})를 찾을 수 없음.");
            return response()->json(['status' => 'service_not_found'], 404);
        }

        // 상태 업데이트
        $service->payment()->update([
            'status' => $status,
        ]);
        $service->update([
            'status' => 'canceled',
        ]);

        \Log::info("✅ 서비스 환불 처리됨", [
            'service_id' => $service->id,
            'user_id' => $service->user_id,
            'status' => $status,
        ]);

        // 서버 종료 처리
        app(\App\Services\ProvisioningService::class)->terminateService($service);

        return response()->json(['status' => 'success'], 200);
    }

    \Log::info("ℹ️ 처리 대상 아님: {$eventType} / 상태: {$status}");
    return response()->json(['status' => 'ignored'], 200);
}
}
