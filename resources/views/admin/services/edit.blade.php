@extends('layouts.admin')

@section('title', '서버 수정')

@section('content')
<h2 class="text-2xl font-bold mb-4">서버 수정</h2>

<form method="POST" action="{{ route('admin.services.update', $service->id) }}" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium">플랜</label>
            <select name="plan_id" class="w-full border p-2 rounded">
                @foreach ($plans as $plan)
                    <option value="{{ $plan->id }}" {{ $service->plan_id == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }} ({{ $plan->disk_size }}GB)
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">만료일</label>
            <input type="date" name="expired_at" class="w-full border p-2 rounded" value="{{ \Carbon\Carbon::parse($service->expired_at)->format('Y-m-d') }}">
        </div>

        <div>
            <label class="block font-medium">상태</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="active" {{ $service->status == 'active' ? 'selected' : '' }}>사용중</option>
                <option value="suspended" {{ $service->status == 'suspended' ? 'selected' : '' }}>정지</option>
                <option value="deleted" {{ $service->status == 'deleted' ? 'selected' : '' }}>삭제됨</option>
            </select>
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">수정하기</button>
            <a href="{{ route('admin.services.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded">취소</a>
        </div>
    </form>
@endsection
