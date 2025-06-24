<div class="space-y-4">
    <h3 class="text-lg font-semibold">🧅 사용 가능한 테마</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse (collect($themes)->where('status', 'enabled') as $theme)
           <div
    x-data="{
        active: 0,
        showModal: false,
        interval: null,
        screenshots: {{ Js::from($theme->screenshots ?? []) }},
        startSlider() {
            if (this.interval) clearInterval(this.interval);
            if (this.screenshots.length > 1) {
                this.interval = setInterval(() => {
                    this.active = (this.active + 1) % this.screenshots.length;
                }, 3000);
            }
        },
        stopSlider() {
            if (this.interval) clearInterval(this.interval);
        }
    }"
    class="border rounded-xl shadow p-4 bg-white"
    x-init="startSlider()"
>
    <p class="text-base font-semibold mb-2">테마 명 : {{ $theme->name }}</p>
<div class="border-b border-gray-200 mb-2"></div>

    <!-- 미리보기 이미지 크기 조정 (30% 증가) -->
    @if (!empty($theme->screenshots))
        <div class="rounded-xl overflow-hidden cursor-pointer" @click="active = 0; showModal = true">
            <img :src="'/storage/' + screenshots[active]" class="w-full object-cover rounded-lg h-64" />
        </div>
    @else
        <div class="bg-gray-100 rounded-lg h-64 flex items-center justify-center text-sm text-gray-400">이미지 없음</div>
    @endif

    <p class="text-sm text-gray-500 mt-2">대상: {{ $theme->plan_type === 'both' ? 'Basic / Pro 공용' : ucfirst($theme->plan_type) }}</p>

    <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 text-sm">
        🚀 설치
    </button>

    <!-- 모달 -->
    <template x-if="showModal">
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50" @keydown.window.escape="showModal = false; stopSlider()">
            <div class="relative bg-white p-4 rounded-xl shadow-xl max-w-4xl w-full space-y-4" @mouseenter="stopSlider()" @mouseleave="startSlider()">
                <!-- 확대 이미지 -->
                <img :src="'/storage/' + screenshots[active]" class="mx-auto rounded max-h-[60vh] object-contain" />

                <!-- 슬라이드 버튼 -->
                <template x-if="screenshots.length > 1">
                    <div>
                        <button @click="active = (active - 1 + screenshots.length) % screenshots.length"
                                class="absolute top-1/2 left-4 -translate-y-1/2 bg-white px-3 py-1 rounded-full shadow hover:bg-gray-200">
                            ‹
                        </button>
                        <button @click="active = (active + 1) % screenshots.length"
                                class="absolute top-1/2 right-4 -translate-y-1/2 bg-white px-3 py-1 rounded-full shadow hover:bg-gray-200">
                            ›
                        </button>
                    </div>
                </template>

                <!-- 썸네일 -->
                <div class="flex space-x-2 justify-center mt-2">
                    <template x-for="(img, i) in screenshots" :key="i">
                        <img :src="'/storage/' + img"
                             @click="active = i"
                             class="w-28 h-20 object-cover rounded cursor-pointer border"
                             :class="{ 'ring-2 ring-blue-500': i === active }"
                        />
                    </template>
                </div>

                <!-- 닫기 버튼 -->
                <button @click="showModal = false; stopSlider()"
                        class="absolute top-3 right-3 text-gray-700 hover:text-black bg-white px-2 py-1 rounded-full shadow">
                    ✕
                </button>
            </div>
        </div>
    </template>
</div>
        @empty
            <p class="text-gray-500 col-span-full">사용 가능한 테마가 없습니다.</p>
        @endforelse
    </div>
</div>


<script src="//unpkg.com/alpinejs" defer></script>
