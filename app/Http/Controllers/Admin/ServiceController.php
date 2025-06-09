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
        $server = WhmServer::findOrFail($id);
    
        if ($server->services()->count() > 0) {
            return redirect()->back()->with('error', '이 서버에 연결된 서비스가 있어 삭제할 수 없습니다.');
        }
    
        $server->delete();
        return redirect()->route('admin.servers.index')->with('success', '서버가 정상적으로 삭제되었습니다.');
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
