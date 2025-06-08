<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\User;
use App\Models\WhmServer;
use App\Services\SaasProvisioningService;

use App\Services\WhmApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('plans.index', compact('plans'));
    }

    public function select(Request $request)
{
    $request->validate([
        'plan_id' => ['required', 'exists:plans,id'],
        'whm_username' => ['required', 'alpha_num', 'max:16', 'unique:users,whm_username'],
        'whm_password' => ['required', 'min:8'],
    ]);

    $user = Auth::user();
    $user->plan_id = $request->plan_id;
    $user->save();

    $provisioning = new SaasProvisioningService();
    [$success, $resultMessage] = $provisioning->provision($user, $request->whm_username, $request->whm_password);

    if ($success) {
        return redirect()->route('dashboard')->with('success', '계정 생성 성공: ' . $resultMessage);
    } else {
        return redirect()->back()->withErrors(['계정 생성 실패: ' . $resultMessage]);
    }
}


    public function checkUsername(Request $request)
    {
        $request->validate(['whm_username' => 'required|alpha_num']);

        $servers = WhmServer::all();
        foreach ($servers as $server) {
            $whmApi = new WhmApiService($server);
            if ($whmApi->accountExists($request->whm_username)) {
                return response()->json(['available' => false]);
            }
        }
        return response()->json(['available' => true]);
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|integer|min:0',
            'disk_size' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Plan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', '플랜이 생성되었습니다.');
    }



    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|integer|min:0',
            'disk_size' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', '플랜이 수정되었습니다.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', '플랜이 삭제되었습니다.');
    }
    public function show(Plan $plan)
{
    return view('admin.plans.show', compact('plan'));
}
}
