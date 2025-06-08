<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Server;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::all();
        return view('admin.servers.index', compact('servers'));
    }

    public function create()
    {
        return view('admin.servers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'ip_address' => 'required|ip',
            'whm_user' => 'required|string',
            'whm_token' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        Server::create($validated);

        return redirect()->route('admin.servers.index')->with('success', '서버가 등록되었습니다.');
    }

    public function edit(Server $server)
    {
        return view('admin.servers.edit', compact('server'));
    }

    public function update(Request $request, Server $server)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'ip_address' => 'required|ip',
            'whm_user' => 'required|string',
            'whm_token' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $server->update($validated);

        return redirect()->route('admin.servers.index')->with('success', '서버가 수정되었습니다.');
    }

    public function destroy(Server $server)
    {
        $server->delete();

        return redirect()->route('admin.servers.index')->with('success', '서버가 삭제되었습니다.');
    }
}
