<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
{
    $year = now()->year;

    $newCounts = Payment::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->whereYear('created_at', $year)
        ->where('status', 'paid')
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('payments as p2')
                ->whereColumn('p2.service_id', 'payments.service_id')
                ->whereRaw('p2.created_at < payments.created_at');
        })
        ->groupByRaw('MONTH(created_at)')
        ->pluck('count', 'month');

    $extendCounts = Payment::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->whereYear('created_at', $year)
        ->where('status', 'paid')
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('payments as p2')
                ->whereColumn('p2.service_id', 'payments.service_id')
                ->whereRaw('p2.created_at < payments.created_at');
        })
        ->groupByRaw('MONTH(created_at)')
        ->pluck('count', 'month');

    $cancelCounts = Payment::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->whereYear('created_at', $year)
        ->where('status', 'paid')
        ->whereNotNull('refund_reason')
        ->groupByRaw('MONTH(created_at)')
        ->pluck('count', 'month');

    $totalSales = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
        ->whereYear('created_at', $year)
        ->where('status', 'paid')
        ->groupByRaw('MONTH(created_at)')
        ->pluck('total', 'month');


        $monthlySalesRaw = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
    ->whereYear('created_at', $year)
    ->where('status', 'paid')
    ->groupByRaw('MONTH(created_at)')
    ->pluck('total', 'month');

$monthlyLabels = [];
$monthlySales = [];
for ($i = 1; $i <= 12; $i++) {
    $monthlyLabels[] = $i . '월';
    $monthlySales[] = $monthlySalesRaw[$i] ?? 0;
}


    return view('admin.stats.index', [
        'newCounts' => $newCounts,
        'extendCounts' => $extendCounts,
        'cancelCounts' => $cancelCounts,
        'totalSales' => $totalSales,
        'newCountsTotal' => $newCounts->sum(),
        'extendCountsTotal' => $extendCounts->sum(),
        'cancelCountsTotal' => $cancelCounts->sum(),
        'totalSalesSum' => $totalSales->sum(),
        
    ]);
}

}
