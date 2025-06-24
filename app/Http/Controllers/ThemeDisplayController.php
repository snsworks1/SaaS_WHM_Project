<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeDisplayController extends Controller
{
   public function index(Service $service)
{
    $themes = Theme::where('status', 'enabled')->get(); // ✅ 수정됨

    $installedThemes = app(ThemeService::class)->getInstalledThemes($service);

    return view('theme.index', compact('themes', 'service', 'installedThemes'));
}
}
