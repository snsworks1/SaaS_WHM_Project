@extends('layouts.admin')


@section('content')
<div class="container">
    <h1>WHM 서버 수정</h1>

    <form action="{{ route('admin.whm-servers.update', $whmServer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">서버 이름</label>
            <input type="text" name="name" class="form-control" value="{{ $whmServer->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">API URL</label>
            <input type="text" name="api_url" class="form-control" value="{{ $whmServer->api_url }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">API Token</label>
            <input type="text" name="api_token" class="form-control" value="{{ $whmServer->api_token }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">WHM Username</label>
            <input type="text" name="username" class="form-control" value="{{ $whmServer->username }}" required>
        </div>

        <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">서버 IP</label>
    <input type="text" name="ip_address" class="mt-1 block w-full" value="{{ $server->ip_address }}" required>
</div>

        <div class="mb-4">
    <label class="block font-medium mb-1">전체 디스크 용량 (GB)</label>
    <input type="number" name="total_disk_capacity" value="{{ old('total_disk_capacity', $whmServer->total_disk_capacity ?? '') }}" class="form-input w-full" required>
</div>

        <div class="mb-3">
            <label class="form-label">활성 여부</label>
            <select name="active" class="form-control">
                <option value="1" {{ $whmServer->active ? 'selected' : '' }}>활성</option>
                <option value="0" {{ !$whmServer->active ? 'selected' : '' }}>비활성</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">수정 완료</button>
        <a href="{{ route('admin.whm-servers.index') }}" class="btn btn-secondary">취소</a>
    </form>
</div>
@endsection
