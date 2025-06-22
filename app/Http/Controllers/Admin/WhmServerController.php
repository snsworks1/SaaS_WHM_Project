<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhmServer;
use Illuminate\Http\Request;
use App\Services\WhmApiService;
use Illuminate\Support\Str;

class WhmServerController extends Controller
{
    public function index()
    {
        $servers = WhmServer::all();

        $servers = $servers->map(function ($server) {
            $service = new WhmApiService($server);

            // ✅ SSH 연결 상태 확인
            $port = $server->port ?? 49999;
            $server->ssh_status = $this->checkSshConnection($server->ip_address, $port) ? 'reachable' : 'unreachable';

            // ✅ WHM API 연결 상태 확인
            $server->connection_status = $service->checkConnection() ? 'connected' : 'disconnected';

            if ($server->connection_status === 'connected') {
                $server->account_count = $service->getAccountCount();
                $server->server_load = $service->getServerLoad();
                $server->disk_usage = $service->getDiskUsage();
            } else {
                $server->account_count = '-';
                $server->server_load = '-';
                $server->disk_usage = '-';
            }

            return $server;
        });

        return view('admin.whm_servers.index', compact('servers'));
    }

    public function create()
    {
        return view('admin.whm_servers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
    'name' => 'required',
    'ip_address' => 'required|ip',
    'api_url' => 'required|url',
    'api_token' => 'required',
    'username' => 'required',
    'total_disk_capacity' => 'required|numeric|min:1',
]);

$server = WhmServer::create([
    'name' => $request->name,
    'ip_address' => $request->ip_address,
    'api_url' => $request->api_url,
    'api_token' => $request->api_token,
    'username' => $request->username,
    'total_disk_capacity' => $request->total_disk_capacity,
    'status' => 'active',
    'active' => $request->active ?? 1,
]);

$plans = \App\Models\Plan::all();
$whmApi = new WhmApiService($server);

foreach ($plans as $plan) {
    $packageName = $plan->name;
    $diskSize = max(1, $plan->disk_size) ?? 1000;

    $whmApi->createPackage($packageName, $diskSize, $plan);
}


        return redirect()->route('admin.whm-servers.index')->with('success', 'WHM 서버 추가됨.');
    }

    public function edit(WhmServer $whmServer)
    {
        return view('admin.whm_servers.edit', compact('whmServer'));
    }

    public function update(Request $request, WhmServer $whmServer)
    {
        $request->validate([
    'name' => 'required',
    'ip_address' => 'required|ip',
    'api_url' => 'required|url',
    'api_token' => 'required',
    'username' => 'required',
    'total_disk_capacity' => 'required|numeric|min:1',
]);

        $whmServer->update([
    'name' => $request->name,
    'ip_address' => $request->ip_address,
    'api_url' => $request->api_url,
    'api_token' => $request->api_token,
    'username' => $request->username,
    'total_disk_capacity' => $request->total_disk_capacity,
    'active' => $request->active ?? 1,
]);

        return redirect()->route('admin.whm-servers.index')->with('success', '수정 완료.');
    }

    public function destroy(WhmServer $whmServer)
    {
        $whmServer->delete();
        return redirect()->route('admin.whm-servers.index')->with('success', '삭제 완료.');
    }

    public function monitor($id)
    {
        $server = WhmServer::findOrFail($id);
        $service = new WhmApiService($server);

        $accountCount = $service->getAccountCount();
        $serverLoad = $service->getServerLoad();

        return view('admin.whm_servers.monitor', compact('server', 'accountCount', 'serverLoad'));
    }

    // ✅ SSH 연결 체크 함수 (static 아님)
    private function checkSshConnection($ip, $port = 49999): bool
    {
        $connection = @fsockopen($ip, $port, $errno, $errstr, 3);
        if ($connection) {
            fclose($connection);
            return true;
        }
        return false;
    }


}
