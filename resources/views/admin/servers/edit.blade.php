@extends('layouts.admin')

@section('title', '서버 수정')

@section('content')
<h2 class="text-2xl font-bold mb-4">서버 수정</h2>

<form method="POST" action="{{ route('admin.servers.update', $server->id) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block font-semibold">서버명</label>
        <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $server->name) }}" required>
    </div>

    <div>
        <label class="block font-semibold">IP 주소</label>
        <input type="text" name="ip_address" class="w-full border rounded p-2" value="{{ old('ip_address', $server->ip_address) }}" required>
    </div>

    <div>
        <label class="block font-semibold">WHM 계정명</label>
        <input type="text" name="whm_user" class="w-full border rounded p-2" value="{{ old('whm_user', $server->whm_user) }}" required>
    </div>

    <div>
        <label class="block font-semibold">WHM API 토큰</label>
        <input type="text" name="whm_token" class="w-full border rounded p-2" value="{{ old('whm_token', $server->whm_token) }}" required>
    </div>

    <div>
        <label class="block font-semibold">상태</label>
        <select name="status" class="w-full border rounded p-2">
            <option value="active" {{ $server->status == 'active' ? 'selected' : '' }}>활성</option>
            <option value="inactive" {{ $server->status == 'inactive' ? 'selected' : '' }}>비활성</option>
        </select>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">수정</button>
    </div>
</form>
@endsection
