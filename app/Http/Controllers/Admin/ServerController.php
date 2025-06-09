<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhmServer;   // ✅ 요거 추가
use App\Models\Service;     // (서비스 관계 확인시 필요하면 추가)

class ServerController extends Controller
{
    public function index()
{
    $servers = WhmServer::withCount('services')->get();

    return view('admin.servers.index', compact('servers'));
}

   public function services()
{
    return $this->hasMany(Service::class, 'whm_server_id');
}

public function destroy($id)
{
    $server = WhmServer::findOrFail($id);

    if ($server->services()->count() > 0) {
        return redirect()->back()->with('error', '이 서버에 연결된 서비스가 있어 삭제할 수 없습니다.');
    }

    $server->delete();
    return redirect()->route('admin.servers.index')->with('success', '서버가 정상적으로 삭제되었습니다.');
}
public function extend($id)
{
    $service = Service::findOrFail($id);

    // 기본 1개월 연장 (필요시 months 파라미터로 변경 가능)
    $service->expired_at = \Carbon\Carbon::parse($service->expired_at)->addMonth();

    // status 복구는 여기서는 제외 (결제엔진 연동시 처리할 예정)
    $service->save();

    return redirect()->route('admin.services.index')->with('success', '서비스가 1개월 연장되었습니다.');
}


}
