{{-- resources/views/theme/index.blade.php --}}

<div class="text-sm text-gray-700">
    <h3 class="text-lg font-bold text-gray-800 mb-4">🎨 사용 가능한 테마</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
@forelse (collect($themes)->where('status', 'enabled') as $theme)
            @php
                $isInstalled = in_array($theme->folder_name, $installedThemes ?? []);
                $screenshots = is_array($theme->screenshots) ? $theme->screenshots : json_decode($theme->screenshots, true);
            @endphp

            <div class="bg-white rounded-xl shadow p-4 relative">
                <h4 class="text-base font-semibold mb-2 text-gray-800">{{ $theme->name }}</h4>

                {{-- 이미지 슬라이드 --}}
                <div x-data="{ active: 0 }" class="relative overflow-hidden rounded-xl h-40 bg-gray-100">
                    @if (!empty($screenshots))
                        @foreach ($screenshots as $index => $src)
                            <img
                                x-show="active === {{ $index }}"
                                src="{{ asset('storage/' . $src) }}"
                                alt="테마 이미지"
                                class="absolute top-0 left-0 w-full h-full object-cover transition-opacity duration-500"
                                x-transition:enter="ease-out duration-300"
                                x-transition:leave="ease-in duration-200"
                            >
                        @endforeach

                        {{-- 슬라이드 버튼 --}}
                        <button @click="active = (active - 1 + {{ count($screenshots) }}) % {{ count($screenshots) }}"
                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white px-2 py-1 rounded-full text-sm">‹</button>
                        <button @click="active = (active + 1) % {{ count($screenshots) }}"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white px-2 py-1 rounded-full text-sm">›</button>
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400 text-sm">이미지 없음</div>
                    @endif
                </div>

                {{-- 플랜 표시 --}}
                <p class="mt-3 text-xs text-gray-600">
                    대상: 
                    @if ($theme->plan_type === 'basic') Basic 전용
                    @elseif ($theme->plan_type === 'pro') Pro 전용
                    @else Basic / Pro 공용
                    @endif
                </p>

                {{-- 설치 여부/버튼 --}}
                @if ($isInstalled)
                    <div class="mt-3 inline-block px-3 py-1 text-sm bg-green-100 text-green-700 rounded">✅ 설치됨</div>
                @else
                    <form action="{{ route('user.themes.install', [$service->id, $theme->id]) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 rounded">
                            🚀 설치
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-500">사용 가능한 테마가 없습니다.</p>
        @endforelse
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
