<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeDisplayController extends Controller
{
public function index(Service $service)
{
    \Log::info('🚀 ThemeDisplayController@index 진입', ['service_id' => $service->id]);

    $themes = Theme::where('status', 'enabled')->get();
    $themeService = app(\App\Services\ThemeService::class);
    $installedFolders = $themeService->getInstalledThemes($service); // ⬅️ 여기 있어야 함

    \Log::info('🔍 installedFolders', $installedFolders); // 디버깅용

    $installedThemes = [];

    foreach ($themes as $theme) {
    $installedThemes[$theme->id] = in_array($theme->folder_name, $installedFolders);
}

    return view('theme.index', compact('themes', 'service', 'installedThemes'));
}

    

}
