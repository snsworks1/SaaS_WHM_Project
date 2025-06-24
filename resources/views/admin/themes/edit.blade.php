@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">테마 수정</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.themes.update', $theme->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- 테마 이름 --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">테마 이름</label>
            <input type="text" name="name" id="name" value="{{ old('name', $theme->name) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200" required>
        </div>

        {{-- 플랜 타입 --}}
        <div>
            <label for="plan_type" class="block text-sm font-medium text-gray-700 mb-1">대상 플랜</label>
            <select name="plan_type" id="plan_type"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200" required>
                <option value="basic" @selected($theme->plan_type === 'basic')>Basic 전용</option>
                <option value="pro" @selected($theme->plan_type === 'pro')>Pro 전용</option>
                <option value="both" @selected($theme->plan_type === 'both')>Basic / Pro 공용</option>
            </select>
        </div>

        {{-- zip 파일 --}}
        <div>
            <label for="zip_file" class="block text-sm font-medium text-gray-700 mb-1">ZIP 파일 (선택)</label>
            <input type="file" name="zip_file" id="zip_file"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200">
            @if ($theme->zip_path)
                <p class="text-sm text-gray-500 mt-1">현재 파일: <code>{{ $theme->zip_path }}</code></p>
            @endif
        </div>

        {{-- 기존 스크린샷 리스트 + 삭제 --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">기존 스크린샷</label>
            <div class="grid grid-cols-3 gap-4 mb-4">
                @foreach ($theme->screenshots ?? [] as $index => $screenshot)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $screenshot) }}" class="rounded shadow w-full h-32 object-cover">
                        <form method="POST" action="{{ route('admin.themes.deleteScreenshot', [$theme->id, $index]) }}"
                              onsubmit="return confirm('이미지를 삭제하시겠습니까?')"
                              class="absolute top-1 right-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-600 text-white text-xs rounded-full px-2 py-1 opacity-80 hover:opacity-100">✕</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 새로운 스크린샷 업로드 (드래그앤드롭) --}}
        <div x-data="{
            files: [],
            handleDrop(e) {
                e.preventDefault()
                this.files = [...e.dataTransfer.files]
                document.getElementById('screenshotInput').files = e.dataTransfer.files
            },
            handleBrowse(e) {
                this.files = [...e.target.files]
            }
        }"
        @dragover.prevent
        @drop="handleDrop"
        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-all">

            <label for="screenshotInput" class="cursor-pointer">
                <div class="text-gray-600 font-medium">📁 스크린샷 드래그 또는 클릭하여 업로드</div>
                <div class="text-sm text-gray-400 mt-1">최대 2MB / jpg, png, gif, webp 가능</div>
            </label>
            <input type="file" name="screenshots[]" id="screenshotInput" multiple
                   class="hidden" @change="handleBrowse">

            <template x-if="files.length > 0">
                <ul class="mt-4 text-left text-sm text-gray-600 space-y-1">
                    <template x-for="file in files" :key="file.name">
                        <li>📎 <span x-text="file.name"></span></li>
                    </template>
                </ul>
            </template>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                💾 저장
            </button>
        </div>
    </form>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
