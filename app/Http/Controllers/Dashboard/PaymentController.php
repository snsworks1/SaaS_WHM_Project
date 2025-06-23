<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Services\TossPaymentService;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('plan')
            ->where('user_id', Auth::id())
            ->orderByDesc('approved_at')
            ->get();

        return view('dashboard.payments', compact('payments'));
    }

    public function showReceipt($order_id)
{
    $payment = Payment::where('order_id', $order_id)
        ->where('user_id', auth()->id()) // 보안 체크
        ->firstOrFail();

    $toss = app(\App\Services\TossPaymentService::class);
    $response = $toss->getReceiptByOrderId($order_id);
    $url = $response['receipt']['url'] ?? null;

    if (!$url) {
        abort(404, '영수증을 찾을 수 없습니다.');
    }

    return redirect()->away($url); // iframe에서 열립니다
}
}
