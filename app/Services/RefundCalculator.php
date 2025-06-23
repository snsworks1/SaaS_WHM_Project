<?php

namespace App\Services;

use App\Models\Service;
use Carbon\Carbon;

class RefundCalculator
{
    public static function calculate(Service $service): array
    {
        $plan = $service->plan;
        $payment = $service->payment;

        // 기준일: start_at → approved_at → created_at 순
        $startDate = Carbon::parse($payment->start_at ?? $payment->approved_at ?? $service->created_at)->startOfDay();
        $approvedAt = Carbon::parse($payment->approved_at ?? $startDate)->startOfDay();
        $endDate = Carbon::parse($service->expired_at)->startOfDay();
        $now = Carbon::now()->startOfDay();

        $isEarlyExtensionRefund = false;
        $totalDays = 0;
        $daysUsed = 0;
        $daysLeft = 0;
        $usedAmount = 0;
        $penalty = 0;
        $refundAmount = 0;
        $isEligible = false;

        if ($now->lt($startDate)) {
            // ⏱ 연장 시작 전
            $isEarlyExtensionRefund = true;

            $totalDays = $startDate->diffInDays($endDate);
            $daysUsed = 0;
            $daysLeft = $totalDays;
            $usedAmount = 0;
            $penalty = 0;
            $refundAmount = $payment->amount;
            $isEligible = true;

        } else {
            // 일반 사용 기간 계산
            $totalDays = max(1, $startDate->diffInDays($endDate));
            $daysUsed = max(0, $startDate->diffInDays($now));
            $daysLeft = max(0, $totalDays - $daysUsed);

            $totalAmount = $payment->amount;
            $dailyRate = $totalAmount / $totalDays;
            $usedAmount = round($dailyRate * $daysUsed);

            $months = $startDate->diffInMonths($endDate);
            $discountRate = match ($months) {
                3  => 0.02,
                6  => 0.04,
                12 => 0.10,
                24 => 0.20,
                default => 0,
            };

            if ($discountRate > 0) {
                $originalPrice = $totalAmount / (1 - $discountRate);
                $discountAmount = $originalPrice - $totalAmount;
                $penalty = round($discountAmount * ($daysLeft / $totalDays));
            }

            $isEligible = $daysUsed <= 14;
            $refundAmount = $isEligible ? max(0, $totalAmount - $usedAmount - $penalty) : 0;
        }

        return [
            'daysUsed'     => (int) round($daysUsed),
            'daysLeft'     => (int) round($daysLeft),
            'usedAmount'   => (int) round($usedAmount),
            'penalty'      => (int) round($penalty),
            'refundable'   => (int) round($refundAmount),
            'isEligible'   => $isEligible,
            'durationDays' => (int) round($totalDays),
            'isEarlyExtensionRefund' => $isEarlyExtensionRefund,
            'startDate'    => $startDate->toDateString(),
            'approvedDate' => $approvedAt->toDateString(),
        ];
    }
}
