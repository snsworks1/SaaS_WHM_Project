{{-- resources/views/admin/themes/create.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <h1 class="text-xl font-semibold mb-4">테마 등록</h1>

    <form action="{{ route('admin.themes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-medium">테마 이름</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">테마 ZIP 파일</label>
            <input type="file" name="zip_file" accept=".zip" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">스크린샷 이미지</label>
            <input type="file" name="screenshots[]" multiple>

        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">제공 대상</label>
            <select name="plan_type" class="w-full border rounded px-3 py-2">
                <option value="both">Basic / Pro 공용</option>
                <option value="pro">Pro 전용</option>
                <option value="basic">Basic 전용</option>
            </select>
        </div>

        <div class="mb-4">
    <label class="block font-medium mb-1">배포 상태</label>
    <select name="status" class="w-full border-gray-300 rounded">
        <option value="enabled" {{ old('status', $theme->status ?? 'enabled') === 'enabled' ? 'selected' : '' }}>배포중</option>
        <option value="disabled" {{ old('status', $theme->status ?? '') === 'disabled' ? 'selected' : '' }}>배포중지</option>
    </select>
</div>

        <div class="text-right">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                등록하기
            </button>
        </div>
    </form>
</div>
@endsection
