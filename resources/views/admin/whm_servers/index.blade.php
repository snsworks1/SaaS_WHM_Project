@extends('layouts.admin')

@section('title', 'WHM 서버 관리')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">WHM 서버 관리</h2>
    <a href="{{ route('admin.whm-servers.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        신규 서버 추가
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($servers as $server)
        <div class="bg-white shadow rounded-lg p-6 border border-gray-200 hover:shadow-lg transition duration-300">
            <h3 class="text-xl font-bold mb-2">{{ $server->name }}</h3>
            <p class="text-sm text-gray-500 mb-2">API URL: {{ $server->api_url }}</p>
            <p class="text-sm text-gray-500 mb-4">서버 IP: <span class="font-semibold">{{ $server->ip_address ?? '-' }}</span></p>

            <div class="mb-3">
                <span class="font-semibold">상태: </span>
                @if($server->connection_status === 'connected')
                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">연결됨</span>
                @else
                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">끊어짐</span>
                @endif
            </div>

            @if($server->connection_status === 'connected')
                <div class="text-sm text-gray-700">
                    <p>계정 수: <span class="font-semibold">{{ $server->account_count }}</span></p>
                    <p>디스크 사용량: <span class="font-semibold">{{ $server->disk_usage }}</span></p>
                    <p>서버 부하 (Load): <span class="font-semibold">{{ $server->server_load }}</span></p>
                    <p>메모리 사용량: <span class="font-semibold">{{ $server->memory_usage ?? 'N/A' }}</span></p>
                </div>
            @else
                <div class="text-sm text-gray-500 italic">모니터링 불가</div>
            @endif

            <div class="mt-4 flex justify-end space-x-2">
                <a href="{{ route('admin.whm-servers.edit', $server->id) }}" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">수정</a>
                <form action="{{ route('admin.whm-servers.destroy', $server->id) }}" method="POST" onsubmit="return confirm('삭제하시겠습니까?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">삭제</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
