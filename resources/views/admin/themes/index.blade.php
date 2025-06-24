@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">테마 리스트</h1>

    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.themes.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + 테마 등록
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($themes as $theme)
            <div class="bg-white rounded-2xl shadow p-4 relative overflow-hidden">

                {{-- 수정 & 삭제 버튼 --}}
                <div class="absolute top-2 right-2 flex space-x-2 z-10">
                    <a href="{{ route('admin.themes.edit', $theme->id) }}"
                       class="text-blue-600 text-sm hover:underline">✏️</a>

                    <form action="{{ route('admin.themes.destroy', $theme->id) }}" method="POST"
                          onsubmit="return confirm('정말 삭제하시겠습니까?');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 text-sm hover:underline">🗑</button>
                    </form>
                </div>

                {{-- 테마 이름 --}}
                <h2 class="text-lg font-semibold mb-2">{{ $theme->name }}</h2>

                {{-- 이미지 슬라이드 --}}
                <div x-data="{
                        active: 0,
                        images: {{ json_encode($theme->screenshots ?? []) }},
                        init() {
                            if (this.images.length > 1) {
                                setInterval(() => {
                                    this.active = (this.active + 1) % this.images.length
                                }, 3000)
                            }
                        }
                    }"
                    class="relative overflow-hidden rounded-xl h-48 bg-gray-100">

                    <template x-for="(src, index) in images" :key="index">
                        <img
                            x-show="active === index"
                            :src="'{{ asset('storage') }}/' + src"
                            alt=""
                            class="absolute top-0 left-0 w-full h-full object-cover transition-opacity duration-500"
                            x-transition:enter="ease-out duration-300"
                            x-transition:leave="ease-in duration-200"
                        >
                    </template>

                    {{-- 슬라이드 화살표 (2개 이상일 때만 표시) --}}
                    <button x-show="images.length > 1"
                            @click="active = (active - 1 + images.length) % images.length"
                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white px-2 py-1 rounded-full text-sm">
                        ‹
                    </button>
                    <button x-show="images.length > 1"
                            @click="active = (active + 1) % images.length"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white px-2 py-1 rounded-full text-sm">
                        ›
                    </button>
                </div>

                {{-- 플랜 유형 --}}
                <div class="mt-4 text-sm text-gray-600">
                    <strong>대상:</strong>
                    @if ($theme->plan_type === 'basic')
                        Basic 전용
                    @elseif ($theme->plan_type === 'pro')
                        Pro 전용
                    @else
                        Basic / Pro 공용
                    @endif
                </div>

                <div class="mt-1 text-sm">
    <strong>상태:</strong>
    <span class="{{ $theme->status === 'enabled' ? 'text-green-600' : 'text-red-600' }}">
        {{ $theme->status === 'enabled' ? '배포중' : '배포중지' }}
    </span>
</div>
            </div>
        @endforeach
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
