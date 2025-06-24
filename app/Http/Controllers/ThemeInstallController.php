<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Theme;

class ThemeInstallController extends Controller
{
    public function install(Service $service, Theme $theme)
    {
        // 설치 로직 예시 (폴더 복사, DB 업데이트 등)
        // ex) 서비스의 워드프레스 경로에 해당 테마 복사
        // 또는 사용자 설치 목록 업데이트 등

        return back()->with('success', '테마가 설치되었습니다.');
    }
}
