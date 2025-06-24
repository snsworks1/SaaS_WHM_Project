<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Theme;
use Illuminate\Support\Facades\Storage;



class ThemeController extends Controller
{
    public function index()
{
    $themes = Theme::latest()->get();
    return view('admin.themes.index', compact('themes'));
}

    public function create()
{
    return view('admin.themes.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'zip_file' => 'required|file|mimes:zip',
        'screenshots.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'plan_type' => 'required|in:basic,pro,both',
    ]);

    $zipPath = $request->file('zip_file')->store('themes/zips', 'public');

    $screenshotPaths = [];

if ($request->hasFile('screenshots')) {
    foreach ($request->file('screenshots') as $screenshot) {
        $path = $screenshot->store('themes/screenshots', 'public');
        $screenshotPaths[] = $path;
    }
}

    Theme::create([
    'name' => $validated['name'],
    'zip_path' => $zipPath,
    'screenshots' => $screenshotPaths,
    'plan_type' => $validated['plan_type'],
    'status' => $request->status ?? 'enabled',
]);

    return redirect()->route('admin.themes.index')->with('success', '테마가 등록되었습니다.');
}

public function edit($id)
{
    $theme = Theme::findOrFail($id);
    return view('admin.themes.edit', compact('theme'));
}


public function update(Request $request, Theme $theme)
{
    \Log::info('ThemeController::update called');

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'zip_file' => 'nullable|file|mimes:zip',
        'screenshots.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'plan_type' => 'required|in:basic,pro,both',
        'status' => 'nullable|in:enabled,disabled',
    ]);

    if ($request->hasFile('zip_file')) {
        if ($theme->zip_path && \Storage::disk('public')->exists($theme->zip_path)) {
            \Storage::disk('public')->delete($theme->zip_path);
        }
        $theme->zip_path = $request->file('zip_file')->store('themes/zips', 'public');
    }

    if ($request->hasFile('screenshots')) {
        // 기존 이미지 삭제
        if (is_array($theme->screenshots)) {
            foreach ($theme->screenshots as $oldPath) {
                if (\Storage::disk('public')->exists($oldPath)) {
                    \Storage::disk('public')->delete($oldPath);
                }
            }
        }

        $screenshotPaths = [];
        foreach ($request->file('screenshots') as $screenshot) {
            $screenshotPaths[] = $screenshot->store('themes/screenshots', 'public');
        }

        $theme->screenshots = $screenshotPaths;
    }

    $theme->name = $validated['name'];
    $theme->plan_type = $validated['plan_type'];
    $theme->status = $validated['status'] ?? $theme->status;

    $theme->save();

    return redirect()->route('admin.themes.index')->with('success', '테마가 수정되었습니다.');
}


public function destroy(Theme $theme)
{
    // 1. ZIP 파일 삭제
    if ($theme->zip_path && Storage::disk('public')->exists($theme->zip_path)) {
        Storage::disk('public')->delete($theme->zip_path);
    }

    // 2. 스크린샷 파일들 삭제
    if (is_array($theme->screenshots)) {
        foreach ($theme->screenshots as $screenshotPath) {
            if ($screenshotPath && Storage::disk('public')->exists($screenshotPath)) {
                Storage::disk('public')->delete($screenshotPath);
            }
        }
    }

    // 3. DB 삭제
    $theme->delete();

    return redirect()->route('admin.themes.index')->with('success', '테마가 삭제되었습니다.');
}

    public function deleteScreenshot(Request $request, Theme $theme, $index)
{
    \Log::info('[삭제 요청 도착]', ['theme_id' => $theme->id, 'index' => $index]);

    $screenshots = $theme->screenshots ?? [];

    if (!isset($screenshots[$index])) {
        return response()->json(['error' => '스크린샷이 존재하지 않습니다.'], 404);
    }

    $path = storage_path('app/public/' . $screenshots[$index]);

    if (file_exists($path)) {
        unlink($path);
    }

    unset($screenshots[$index]);
    $theme->screenshots = array_values($screenshots); // 인덱스 재정렬
    $theme->save();

    return response()->json(['success' => true]);
}


}
