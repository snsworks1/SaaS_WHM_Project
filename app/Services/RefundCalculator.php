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

        $totalAmount = $payment->amount;
        $isOneMonth = $totalDays <= 31;
$isEligible = !$isOneMonth || ($isOneMonth && $daysUsed <= 14); // 안내용

        // 할인율 판단 (플랜 기간 기준)
        if ($totalDays >= 730) {
            $discountRate = 0.20;
        } elseif ($totalDays >= 365) {
            $discountRate = 0.10;
        } elseif ($totalDays >= 180) {
            $discountRate = 0.04;
        } elseif ($totalDays >= 90) {
            $discountRate = 0.02;
        } else {
            $discountRate = 0;
        }

        // 월 정가 기준 (할인 적용 전 원래 가격)
        $originalPrice = $discountRate > 0 ? round($totalAmount / (1 - $discountRate)) : $totalAmount;
        $monthlyPrice = round($originalPrice / ($totalDays / 30));

        // 사용 금액 계산
        if ($daysUsed <= 14) {
            $usedAmount = round($monthlyPrice * ($daysUsed / 30));
        } else {
            // 14일 초과 → 월 단위 계산
            $fullMonthsUsed = floor($daysUsed / 30);
            $partialDays = $daysUsed % 30;

            $usedAmount = $monthlyPrice * $fullMonthsUsed;

            if ($partialDays <= 14) {
                $usedAmount += round($monthlyPrice * ($partialDays / 30));
            } else {
                $usedAmount += $monthlyPrice;
            }
        }

        // 할인 위약금 계산
        $penalty = 0;
        if ($discountRate > 0) {
            $discountAmount = $originalPrice - $totalAmount;
            $penalty = round($discountAmount * ($daysLeft / $totalDays));
        }

        // 환불 가능 조건
        $isEligible = $daysUsed <= 14;              // 안내용: 14일 이내 여부
        $canRefund = !$isOneMonth || $isEligible;   // 실제 환불 가능 조건

        $refundAmount = $canRefund
            ? max(0, $totalAmount - $usedAmount - $penalty)
            : 0;

        
            $chargedDays = 0;

if ($daysUsed <= 14) {
    $chargedDays = round($daysUsed); // 그대로 일할 계산
} else {
    $fullMonthsUsed = floor($daysUsed / 30);
    $partialDays = $daysUsed % 30;

    if ($partialDays <= 14) {
        $chargedDays = ($fullMonthsUsed * 30) + round($partialDays);
    } else {
        $chargedDays = ($fullMonthsUsed + 1) * 30;
    }
}

        return [
            'daysUsed'     => $daysUsed,
            'daysLeft'     => $daysLeft,
            'usedAmount'   => $usedAmount,
            'penalty'      => $penalty,
            'refundable'   => $refundAmount,
            'isEligible'   => $isEligible,  // 안내용 조건
            'canRefund'    => $canRefund,   // 실 계산 조건
            'durationDays' => $totalDays,
            'chargedDays'  => $chargedDays,
        ];
    }
}
