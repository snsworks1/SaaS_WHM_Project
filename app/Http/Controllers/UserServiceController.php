<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Plan;
use App\Services\WhmApiService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserServiceController extends Controller
{
    // 플랜 선택 페이지
    public function showChangePlan($id)
    {
        $service = Service::findOrFail($id);
        $plans = Plan::where('disk_size', '>', $service->plan->disk_size)->get(); // 다운그레이드 금지

        return view('services.change-plan', compact('service', 'plans'));
    }

    // 요금 계산 후 결제 화면
    public function confirmUpgrade(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $newPlan = Plan::findOrFail($request->plan_id);
        $currentPlan = $service->plan;
    
        // 정확한 일할 계산 (초단위 → 일수 변환 → 최소 1일 보장)
        $createdAt = Carbon::parse($service->created_at);
        $now = Carbon::now();
    
        $totalSeconds = $now->diffInSeconds($createdAt);
        $totalDays = $totalSeconds / 86400;
        $effectiveDays = max(1, round($totalDays, 2));
    
        $dailyRate = $currentPlan->price / 30;
        $currentUsedAmount = round($dailyRate * $effectiveDays, 0);
        $priceDiff = $newPlan->price - $currentUsedAmount;
        $finalAmount = max(round($priceDiff, 0), 0);
    
        return view('services.confirm-upgrade', compact('service', 'newPlan', 'finalAmount', 'effectiveDays', 'currentUsedAmount'));
    }
    

    // 실제 결제 완료 처리 (PGX 연동전 가상 완료)
    public function processUpgrade(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $newPlan = Plan::findOrFail($request->plan_id);
        $oldPlan = $service->plan;

        // WHM 패키지 변경
        $whmApi = new WhmApiService($service->whmServer);
        $whmApi->changePackage($service->whm_username, $newPlan->name);

        // used_disk_capacity 조정
        $diff = $newPlan->disk_size - $oldPlan->disk_size;
        $server = $service->whmServer;
        $server->used_disk_capacity += $diff;
        if ($server->used_disk_capacity < 0) {
            $server->used_disk_capacity = 0;
        }
        $server->save();

        // DB 변경
        $service->plan_id = $newPlan->id;
        $service->save();

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


}
