<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhmServer;
use Illuminate\Http\Request;
use App\Services\WhmApiService; // 서비스 불러오기

class WhmServerController extends Controller
{
public function index()
{
    $servers = WhmServer::all();

    $servers = $servers->map(function ($server) {
        $service = new \App\Services\WhmApiService($server);
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
            'api_url' => 'required',
            'api_token' => 'required',
            'username' => 'required',
        ]);

        WhmServer::create($request->all());
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
            'api_url' => 'required',
            'api_token' => 'required',
            'username' => 'required',
        ]);

        $whmServer->update($request->all());
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
}
