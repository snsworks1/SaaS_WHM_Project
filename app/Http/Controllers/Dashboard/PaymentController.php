<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Services\TossPaymentService;

class PaymentController extends Controller
{
    public function index(Request $request)
{
    $query = Payment::with(['plan', 'service']) // 🔄 service 추가됨
        ->where('user_id', Auth::id());

    // 🔍 필터: 상태
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 🔍 필터: 날짜
    if ($request->filled('from')) {
        $query->whereDate('approved_at', '>=', $request->from);
    }
    if ($request->filled('to')) {
        $query->whereDate('approved_at', '<=', $request->to);
    }

    $payments = $query->orderByDesc('approved_at')->paginate(10);

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
