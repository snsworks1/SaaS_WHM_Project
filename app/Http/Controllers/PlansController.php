<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\User;
use App\Services\SaasProvisioningService;
use Illuminate\Support\Facades\Auth;

class PlansController extends Controller
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

}
