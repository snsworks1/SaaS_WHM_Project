@extends('layouts.admin')

@section('title', '서버 추가')

@section('content')
<h2 class="text-2xl font-bold mb-4">서버 추가</h2>

<form method="POST" action="{{ route('admin.servers.store') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block font-semibold">서버명</label>
        <input type="text" name="name" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block font-semibold">IP 주소</label>
        <input type="text" name="ip_address" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block font-semibold">WHM 계정명</label>
        <input type="text" name="whm_user" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block font-semibold">WHM API 토큰</label>
        <input type="text" name="whm_token" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block font-semibold">상태</label>
        <select name="status" class="w-full border rounded p-2">
            <option value="active">활성</option>
            <option value="inactive">비활성</option>
        </select>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">저장</button>
    </div>
</form>
@endsection
