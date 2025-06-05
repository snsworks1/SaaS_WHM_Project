@extends('layouts.admin')

@section('title', '회원 관리')

@section('content')
<div class="flex justify-between mb-4">
    <h2 class="text-2xl font-bold">회원 목록</h2>
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex space-x-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="이름, 이메일 검색"
            class="border rounded p-2 w-64">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">검색</button>
    </form>
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
            <th class="py-2 px-4">이메일</th>
            <th class="py-2 px-4">전화번호</th>
            <th class="py-2 px-4">플랜</th>
            <th class="py-2 px-4">관리</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr class="border-b">
            <td class="py-2 px-4">{{ $user->id }}</td>
            <td class="py-2 px-4">{{ $user->name }}</td>
            <td class="py-2 px-4">{{ $user->email }}</td>
            <td class="py-2 px-4">{{ $user->phone }}</td>
            <td class="py-2 px-4">{{ optional($user->plan)->name ?? '선택안함' }}</td>
            <td class="py-2 px-4">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">수정</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
