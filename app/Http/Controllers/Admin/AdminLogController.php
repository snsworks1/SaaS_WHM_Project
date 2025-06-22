<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ErrorLog; // 사용하는 모델 등 필요에 따라 추가

class AdminLogController extends Controller
{
    // 뷰 렌더링 (admin/logs/error-logs.blade.php)
    public function index()
    {
        return view('admin.logs.error-logs');
    }

    // 에러 로그를 JSON으로 반환 (AJAX용)
    public function json()
    {
        $logs = ErrorLog::orderBy('occurred_at', 'desc')
            ->take(50)
            ->get();

        return response()->json($logs);
    }
    public function toggle($id)
{
    $log = \App\Models\ErrorLog::findOrFail($id);
    $log->resolved = !$log->resolved;
    $log->resolved_at = $log->resolved ? now() : null;
    $log->save();

    return response()->json(['success' => true]);
}

    public function export()
{
    $logs = ErrorLog::latest()->limit(1000)->get();

    $csv = "레벨,타입,제목,발생시각,경로,WHM 유저\n";
    foreach ($logs as $log) {
        $csv .= "{$log->level},{$log->type},\"{$log->title}\",{$log->occurred_at},{$log->file_path},{$log->whm_username}\n";
    }

    return response($csv)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="error_logs.csv"');
}

}
