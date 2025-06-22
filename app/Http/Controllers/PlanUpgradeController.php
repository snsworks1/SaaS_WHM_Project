<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Plan;
use App\Services\WhmApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PlanUpgradeController extends Controller
{
    public function showChangePlan($id)
    {
        $service = Service::findOrFail($id);
        $plans = Plan::where('disk_size', '>', $service->plan->disk_size)->get();

        return view('services.change-plan', compact('service', 'plans'));
    }

    public function confirmUpgrade(Request $request, $id)
    {
        $service = Service::with('plan')->findOrFail($id);
        $newPlan = Plan::findOrFail($request->plan_id);
        $currentPlan = $service->plan;

        $discountRates = [
            1 => 0.00,
            3 => 0.02,
            6 => 0.04,
            12 => 0.10,
            24 => 0.20,
        ];

        $payment = $service->payments()->where('status', 'paid')->latest()->first();
        $period = $payment?->period ?? 1;
        $discountRate = $discountRates[$period] ?? 0.00;

        $totalDays = $period * 30;
        $createdAt = Carbon::parse($service->started_at ?? $service->created_at);
        $now = Carbon::now();
        $usedDays = max(1, $createdAt->diffInDays($now));
        $remainingDays = max(0, $totalDays - $usedDays);

        $currentPriceWithDiscount = $currentPlan->price * (1 - $discountRate);
        $newPriceWithDiscount = $newPlan->price * (1 - $discountRate);

        $usedAmount = round($currentPriceWithDiscount / $totalDays * $usedDays);
        $remainingValue = round($currentPriceWithDiscount - $usedAmount);
        $upgradeAmount = round($newPriceWithDiscount / $totalDays * $remainingDays);

        $finalAmount = max($upgradeAmount - $remainingValue, 0);
        $currentUsedAmount = $usedAmount;

        // ✅ 세션에 plan_id 저장
        session([
            'upgrade_plan_id' => $newPlan->id,
        ]);

        return view('services.confirm-upgrade', compact(
            'service', 'newPlan', 'finalAmount', 'usedDays', 'remainingDays',
            'currentPriceWithDiscount', 'newPriceWithDiscount', 'usedAmount',
            'remainingValue', 'upgradeAmount', 'currentUsedAmount', 'period'
        ));
    }

    public function processUpgrade(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $newPlanId = session('upgrade_plan_id');

        if (!$newPlanId) {
            return redirect()->route('upgrade.payment.fail', ['id' => $id])
                ->with('error', '세션에서 플랜 정보를 찾을 수 없습니다.');
        }

        $newPlan = Plan::findOrFail($newPlanId);
        $oldPlan = $service->plan;

        // WHM 패키지 변경
        $whmApi = new WhmApiService($service->whmServer);
        $whmApi->changePackage($service->whm_username, $newPlan->name);

        // used_disk_capacity 조정
        $diff = $newPlan->disk_size - $oldPlan->disk_size;
        $server = $service->whmServer;
        $server->used_disk_capacity = max(0, $server->used_disk_capacity + $diff);
        $server->save();

        // DB 플랜 변경
        $service->plan_id = $newPlan->id;
        $service->save();

        // ✅ 세션 정리
        session()->forget('upgrade_plan_id');

        return redirect()->route('services.upgradeComplete', $service->id);
    }

    public function upgradeComplete($id)
    {
        $service = Service::with('plan')->findOrFail($id);

        return view('services.upgrade-complete', [
            'service' => $service,
            'newPlan' => $service->plan
        ]);
    }

    public function confirmTossPayment(Request $request, $id)
    {
        $paymentKey = $request->query('paymentKey');
        $orderId = $request->query('orderId');
        $amount = $request->query('amount');

        $response = Http::withBasicAuth(config('services.toss.secret_key'), '')
            ->post('https://api.tosspayments.com/v1/payments/confirm', [
                'paymentKey' => $paymentKey,
                'orderId' => $orderId,
                'amount' => $amount,
            ]);

        if ($response->successful()) {
            return $this->processUpgrade($request, $id);
        }

        return redirect()->route('upgrade.payment.fail', ['id' => $id])
            ->with('error', '결제 인증 실패');
    }
}
