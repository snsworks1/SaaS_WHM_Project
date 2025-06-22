<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class AdminStatsController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab');

        // 통계 카드용 기본값
        $newCountsTotal = Payment::where('status', 'paid')
            ->whereDate('created_at', '>=', now()->startOfMonth())
            ->count();

        $extendCountsTotal = Payment::where('status', 'paid')
            ->where('order_id', 'like', 'extend_%')
            ->whereDate('created_at', '>=', now()->startOfMonth())
            ->count();

        $cancelCountsTotal = Payment::where('status', 'canceled')
            ->whereDate('updated_at', '>=', now()->startOfMonth())
            ->count();

        $totalSalesSum = Payment::where('status', 'paid')
            ->whereDate('created_at', '>=', now()->startOfMonth())
            ->sum('amount');

        // 월별 매출 (예: 1월~12월)
        $monthlyLabels = [];
        $monthlySales = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyLabels[] = "{$i}월";
            $monthlySales[] = Payment::whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->where('status', 'paid')
                ->sum('amount');
        }

        // 각 탭에 필요한 데이터 처리
        $extraData = [];

        if ($tab === 'subscribers') {
            $extraData['newUsers'] = User::whereDate('created_at', '>=', now()->startOfMonth())->get();
        } elseif ($tab === 'renewals') {
            $extraData['extendPayments'] = Payment::where('status', 'paid')
            ->where('order_id', 'like', 'extend_%')
            ->whereDate('created_at', '>=', now()->startOfMonth())
            ->with(['user', 'extension.service']) // ← 여기 중요
            ->get();
        } elseif ($tab === 'cancellations') {
            $extraData['cancelPayments'] = Payment::where('status', 'canceled')
                ->whereDate('updated_at', '>=', now()->startOfMonth())
                ->with(['user', 'service'])
                ->get();
        } elseif ($tab === 'longterm') {
            $extraData['longTermUsers'] = Payment::where('status', 'paid')
                ->where('period', '>=', 3)
                ->with(['user', 'service'])
                ->get();
        }
        
        return view('admin.stats.index', array_merge([
            'monthlyLabels' => $monthlyLabels,
            'monthlySales' => $monthlySales,
            'newCountsTotal' => $newCountsTotal,
            'extendCountsTotal' => $extendCountsTotal,
            'cancelCountsTotal' => $cancelCountsTotal,
            'totalSalesSum' => $totalSalesSum,
        ], $extraData));
    }
}
