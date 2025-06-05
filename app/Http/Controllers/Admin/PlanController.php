<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

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
