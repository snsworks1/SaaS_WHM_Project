<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan; // 이거 추가!

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
    ]);

    $user = Auth::user();
    $user->plan_id = $request->plan_id;
    $user->save();

    return redirect()->route('dashboard')->with('success', '플랜이 성공적으로 선택되었습니다.');
}




}
