<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Services\WhmApiService;
use App\Services\WhmServerPoolService;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        $plan = Plan::create($validated);

        // 서버풀에서 가용 서버 선택
        $serverPool = new WhmServerPoolService();
        $server = $serverPool->selectAvailableServer($plan->disk_size);

        if (!$server) {
            return redirect()->back()->with('error', '사용 가능한 WHM 서버가 없습니다.');
        }

        $whmService = new WhmApiService($server);
        $result = $whmService->createPackage($plan->name, $plan->disk_size, $plan);

        Log::info('WHM 패키지 생성 결과', ['result' => $result]);

        return redirect()->route('admin.plans.index')->with('success', '플랜이 생성되었습니다.');
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateRequest($request);

        $plan = Plan::findOrFail($id);
        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', '플랜이 수정되었습니다.');
    }

    private function validateRequest($request)
    {
    return $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'disk_size' => 'required|integer',
        'description' => 'nullable',
        'ftp_accounts' => 'required|integer',
        'email_accounts' => 'required|integer',
        'sql_databases' => 'required|integer',
        'mailing_lists' => 'required|integer',
        'max_email_per_hour' => 'required|integer',
        'email_quota' => 'required|integer',
        'bandwidth' => 'required|integer', // 추가
        'addon_domains' => 'required|integer',
'subdomains' => 'required|integer',
    ]);
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $serviceCount = $plan->services()->count();
    
        if ($serviceCount > 0) {
            return redirect()->back()->with('error', '현재 이 플랜을 사용하는 서비스가 있어 삭제할 수 없습니다.');
        }
    
        $plan->delete();
    
        return redirect()->route('admin.plans.index')->with('success', '플랜이 삭제되었습니다.');
    }
    
public function users()
{
    return $this->hasMany(User::class);
}


}
