<?php

// app/Services/RefundCalculator.php

namespace App\Services;

use App\Models\Service;
use Carbon\Carbon;

class RefundCalculator
{
    public static function calculate(Service $service): array
    {
        $plan = $service->plan;
        $payment = $service->payment;

        $startDate = Carbon::parse($payment->approved_at ?? $service->created_at)->startOfDay();
        $endDate   = Carbon::parse($service->expired_at)->startOfDay();
        $now       = Carbon::now()->startOfDay();

        $totalDays = max(1, $startDate->diffInDays($endDate));
        $daysUsed  = max(0, $startDate->diffInDays($now));
        $daysLeft  = max(0, $totalDays - $daysUsed);

        $totalDays = (int) round($totalDays);
        $daysUsed = (int) round($daysUsed);
        $daysLeft = (int) round($daysLeft);

        $totalAmount = $payment->amount;
        $dailyRate = $totalAmount / $totalDays;
        $usedAmount = round($dailyRate * $daysUsed);

        $months = $startDate->diffInMonths($endDate);
        $discountRate = match ($months) {
            3 => 0.02,
            6 => 0.04,
            12 => 0.10,
            24 => 0.20,
            default => 0,
        };

        $penalty = 0;
        if ($discountRate > 0) {
            $originalPrice = $totalAmount / (1 - $discountRate);
            $discountAmount = $originalPrice - $totalAmount;
            $penalty = round($discountAmount * ($daysLeft / $totalDays));
        }

        $isEligible = $daysUsed <= 14;
        $refundAmount = $isEligible ? max(0, $totalAmount - $usedAmount - $penalty) : 0;

        return [
            'daysUsed'     => $daysUsed,
            'daysLeft'     => $daysLeft,
            'usedAmount'   => $usedAmount,
            'penalty'      => $penalty,
            'refundable'   => $refundAmount,
            'isEligible'   => $isEligible,
            'durationDays' => $totalDays, // ✅ 이거 추가됨
        ];
    }
}
