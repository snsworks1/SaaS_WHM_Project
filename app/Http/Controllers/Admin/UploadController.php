<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function editorjs(Request $request)
{
    \Log::info('업로드 요청 도착');
    \Log::info($request->allFiles());

if ($request->hasFile('image')) {
    $file = $request->file('image');
    $filename = uniqid() . '.' . $file->getClientOriginalExtension();
    $targetPath = storage_path('app/public/uploads/' . $filename);

    // 직접 이동
    $file->move(storage_path('app/public/uploads'), $filename);

    \Log::info("📦 move()로 저장됨: " . $targetPath);
    \Log::info("📁 존재여부: " . (file_exists($targetPath) ? 'YES' : 'NO'));

    $url = '/storage/uploads/' . $filename;

    return response()->json([
        'success' => 1,
        'file' => [
            'url' => $url
        ]
    ]);
}


    return response()->json(['success' => 0, 'message' => '업로드 실패'], 400);
}


}
