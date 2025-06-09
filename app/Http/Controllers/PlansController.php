<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\User;
use App\Services\SaasProvisioningService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class PlansController extends Controller
{
 

    protected $provisioning;

    public function __construct(SaasProvisioningService $provisioning)
    {
        $this->provisioning = $provisioning;
    }

    public function index()
    {
        $plans = Plan::all();
        return view('plans.index', compact('plans'));
    }
    public function select(Request $request)
    {
        Log::info('🔍 select() 컨트롤러 진입됨', $request->all());


        $reservedUsernames = ['root', 'admin', 'mysql', 'cpanel', 'whm', 'dns', 'ftp', 'test', 'test1', 'test11122', 'email'];

        $validated = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
            'whm_username' => [
                'required', 'alpha_num', 'max:16',
                function ($attribute, $value, $fail) use ($reservedUsernames) {
                    if (in_array(strtolower($value), $reservedUsernames)) {
                        $fail('해당 아이디는 사용이 불가능한 예약어입니다.');
                    }
                }
            ],
            'whm_password' => ['required', 'min:8'],
        ]);

  

        $user = Auth::user();
        $plan = Plan::findOrFail($validated['plan_id']);

        [$success, $resultMessage] = $this->provisioning->provision(
            $user,
            $plan,
            $validated['whm_username'],
            $validated['whm_password']
        );

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
