<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\Plan;
use App\Models\WhmServer;
use Carbon\Carbon;
use App\Services\WhmApiService;
use App\Services\CloudflareService; // ✅ 추가




class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['user', 'plan', 'whmServer'])->get();

        return view('admin.services.index', compact('services'));
    }

    
       public function services()
    {
        return $this->hasMany(Service::class, 'whm_server_id');
    }
    
    public function destroy($id)
{
    $service = Service::findOrFail($id);

    // WHM 삭제
    $whmServer = $service->whmServer;
    $whmApi = new WhmApiService($whmServer);
    $whmApi->deleteAccount($service->whm_username);

    // ✅ 이 부분에서 CloudflareService 호출
    if ($service->dns_record_id) {
        try {
            $cloudflare = new CloudflareService();
            $cloudflare->deleteDnsRecord($service->dns_record_id);
        } catch (\Exception $e) {
            \Log::error('Cloudflare DNS 삭제 실패', ['error' => $e->getMessage()]);
        }
    }

        // ✅ 사용량 차감 로직
        $plan = $service->plan;
        $whmServer->used_disk_capacity -= $plan->disk_size;
        if ($whmServer->used_disk_capacity < 0) {
            $whmServer->used_disk_capacity = 0; // 음수 방지
        }
        $whmServer->save();

    $service->delete();

    return redirect()->route('admin.services.index')->with('success', '서비스가 삭제되었습니다.');
}


    public function extend($id)
{
    $service = Service::with('whmServer')->findOrFail($id);

    // 만료일 1달 연장
    $service->expired_at = now()->addMonth();
    $service->status = 'active';
    $service->save();

    // WHM 계정 suspend 해제 시도
    $whmApi = new WhmApiService($service->whmServer);
    $response = $whmApi->unsuspendAccount($service->whm_username);


    return redirect()->back()->with('success', '서비스가 성공적으로 연장되었습니다.');
}


public function edit($id)
{
    $service = Service::with('plan', 'user')->findOrFail($id);
    $plans = Plan::all();
    return view('admin.services.edit', compact('service', 'plans'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'plan_id' => 'required|exists:plans,id',
        'expired_at' => 'required|date',
        'status' => 'required|in:active,suspended,deleted',
    ]);

    $service = Service::findOrFail($id);
    $service->plan_id = $request->plan_id;
    $service->expired_at = $request->expired_at;
    $service->status = $request->status;
    $service->save();

    return redirect()->route('admin.services.index')->with('success', '서비스가 수정되었습니다.');
}


    
}
