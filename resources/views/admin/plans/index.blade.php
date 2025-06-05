@extends('layouts.admin')

@section('title', '플랜 관리')

@section('content')
<div class="flex justify-between mb-4">
    <h2 class="text-2xl font-bold">플랜 목록</h2>
    <a href="{{ route('admin.plans.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">플랜 추가</a>
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
            <th class="py-2 px-4">이름</th>
            <th class="py-2 px-4">가격</th>
            <th class="py-2 px-4">디스크</th>
            <th class="py-2 px-4">설명</th>
            <th class="py-2 px-4">관리</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($plans as $plan)
        <tr class="border-b">
            <td class="py-2 px-4">{{ $plan->id }}</td>
            <td class="py-2 px-4">{{ $plan->name }}</td>
            <td class="py-2 px-4">{{ $plan->price }} 원</td>
            <td class="py-2 px-4">{{ $plan->disk_size }} GB</td>
            <td class="py-2 px-4">{{ $plan->description }}</td>
            <td class="py-2 px-4 flex space-x-2">
                <a href="{{ route('admin.plans.edit', $plan->id) }}" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">수정</a>
                <form method="POST" action="{{ route('admin.plans.destroy', $plan->id) }}" onsubmit="return confirm('삭제하시겠습니까?');">
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
