@extends('layouts.admin')

@section('title', '서버 관리')

@section('content')
<div class="flex justify-between mb-4">
    <h2 class="text-2xl font-bold">서버 목록</h2>
    <a href="{{ route('admin.servers.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">서버 추가</a>
</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

<table class="min-w-full bg-white shadow rounded-lg">
    <thead class="bg-gray-100">
        <tr>
            <th class="py-2 px-4">ID</th>
            <th class="py-2 px-4">서버명</th>
            <th class="py-2 px-4">IP주소</th>
            <th class="py-2 px-4">WHM 계정</th>
            <th class="py-2 px-4">상태</th>
            <th class="py-2 px-4">관리</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($servers as $server)
        <tr class="border-b">
            <td class="py-2 px-4">{{ $server->id }}</td>
            <td class="py-2 px-4">{{ $server->name }}</td>
            <td class="py-2 px-4">{{ $server->ip_address }}</td>
            <td class="py-2 px-4">{{ $server->whm_user }}</td>
            <td class="py-2 px-4">
                <span class="px-2 py-1 rounded {{ $server->status == 'active' ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }}">
                    {{ $server->status }}
                </span>
            </td>
            <td class="py-2 px-4 flex space-x-2">
                <a href="{{ route('admin.servers.edit', $server->id) }}" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">수정</a>
                <form method="POST" action="{{ route('admin.servers.destroy', $server->id) }}" onsubmit="return confirm('삭제하시겠습니까?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">삭제</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
