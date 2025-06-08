@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">플랜 관리</h1>

    <div class="mb-4">
        <a href="{{ route('admin.plans.create') }}" class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">플랜 추가</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="w-full text-left table-auto">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3">ID</th>
                    <th class="p-3">플랜명</th>
                    <th class="p-3">가격</th>
                    <th class="p-3">디스크 용량</th>
                    <th class="p-3">설명</th>
                    <th class="p-3">액션</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plans as $plan)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">{{ $plan->id }}</td>
                    <td class="p-3">{{ $plan->name }}</td>
                    <td class="p-3">{{ number_format($plan->price) }}원</td>
                    <td class="p-3">{{ $plan->disk_size }} GB</td>
                    <td class="p-3">{{ $plan->description }}</td>
                    <td class="p-3 flex gap-2">
                        <a href="{{ route('admin.plans.edit', $plan) }}" class="text-blue-600 hover:underline">수정</a>
                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('삭제할까요?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">삭제</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
