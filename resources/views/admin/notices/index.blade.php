@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">📢 공지사항 관리</h2>
        <a href="{{ route('admin.notices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ 새 공지사항</a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-6 py-3">중요도</th>
                    <th class="px-6 py-3">종류</th>
                    <th class="px-6 py-3">제목</th>
                    <th class="px-6 py-3">등록일</th>
                    <th class="px-6 py-3">조회수</th>
                    <th class="px-6 py-3">관리</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notices as $notice)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $notice->importance }}</td>
                        <td class="px-6 py-3">{{ $notice->category }}</td>
                        <td class="py-2 px-4">
    <a href="{{ route('admin.notices.show', $notice->id) }}" class="text-blue-600 hover:underline">
        {{ $notice->title }}
    </a>
</td>
                        <td class="px-6 py-3">{{ $notice->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-3">{{ $notice->views }}</td>
                        <td class="px-6 py-3">
                            <a href="{{ route('admin.notices.edit', $notice->id) }}" class="text-blue-600">수정</a> |
                            <form action="{{ route('admin.notices.destroy', $notice->id) }}" method="POST" class="inline" onsubmit="return confirm('삭제하시겠습니까?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500">삭제</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
