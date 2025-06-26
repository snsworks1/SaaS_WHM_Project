<div class="space-y-4">
    <h3 class="text-lg font-semibold">🧅 사용 가능한 테마</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse (collect($themes)->where('status', 'enabled') as $theme)
            <div
                x-data="themeCard(
                    {{ Js::from($theme->id) }},
                    {{ Js::from($service->id) }},
  {{ Js::from($theme->name) }},
                       {{ Js::from($theme->name) }},
                    {{ Js::from($theme->screenshots ?? []) }}
                )"
                x-init="init(); startSlider();"
                class="border rounded-xl shadow p-4 bg-white"
            >
                <p class="text-base font-semibold mb-2">테마 명 : {{ $theme->name }}</p>
                <div class="border-b border-gray-200 mb-2"></div>

                <template x-if="screenshots.length > 0">
                    <div class="rounded-xl overflow-hidden cursor-pointer" @click="active = 0; showModal = true">
                        <img :src="'/storage/' + screenshots[active]" class="w-full object-cover rounded-lg h-64" />
                    </div>
                </template>
                <template x-if="screenshots.length === 0">
                    <div class="bg-gray-100 rounded-lg h-64 flex items-center justify-center text-sm text-gray-400">이미지 없음</div>
                </template>

                <p class="text-sm text-gray-500 mt-2">대상: {{ $theme->plan_type === 'both' ? 'Basic / Pro 공용' : ucfirst($theme->plan_type) }}</p>

                <template x-if="installed">
                    <button class="mt-4 w-full py-2 rounded text-sm bg-gray-300 text-gray-700 cursor-not-allowed" disabled>
                        ✅ 설치됨
                    </button>
                </template>
                <template x-if="!installed">
                    <button
                        :disabled="loading"
                        :class="buttonClass"
                        class="mt-4 w-full py-2 rounded text-sm"
                        x-text="buttonText"
                        @click="installTheme"
                    ></button>
                </template>

                <!-- 모달 -->
                <template x-if="showModal">
                    <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50" @keydown.window.escape="closeModal">
                        <div class="relative bg-white p-4 rounded-xl shadow-xl max-w-4xl w-full space-y-4" @mouseenter="stopSlider()" @mouseleave="startSlider()">
                            <img :src="'/storage/' + screenshots[active]" class="mx-auto rounded max-h-[60vh] object-contain" />

                            <template x-if="screenshots.length > 1">
                                <div>
                                    <button @click="prevSlide" class="absolute top-1/2 left-4 -translate-y-1/2 bg-white px-3 py-1 rounded-full shadow hover:bg-gray-200">‹</button>
                                    <button @click="nextSlide" class="absolute top-1/2 right-4 -translate-y-1/2 bg-white px-3 py-1 rounded-full shadow hover:bg-gray-200">›</button>
                                </div>
                            </template>

                            <div class="flex space-x-2 justify-center mt-2">
                                <template x-for="(img, i) in screenshots" :key="i">
                                    <img :src="'/storage/' + img"
                                        @click="active = i"
                                        class="w-28 h-20 object-cover rounded cursor-pointer border"
                                        :class="{ 'ring-2 ring-blue-500': i === active }" />
                                </template>
                            </div>

                            <button @click="closeModal" class="absolute top-3 right-3 text-gray-700 hover:text-black bg-white px-2 py-1 rounded-full shadow">✕</button>
                        </div>
                    </div>
                </template>
            </div>
        @empty
            <p class="text-gray-500 col-span-full">사용 가능한 테마가 없습니다.</p>
        @endforelse
    </div>
</div>

<script>
function themeCard(themeId, serviceId, folderName, themeName, screenshots = []) {
    return {
        installed: false,
        loading: false,
        buttonText: '🚀 설치',
        buttonClass: 'bg-blue-600 text-white hover:bg-blue-700',
        screenshots: screenshots,
        active: 0,
        showModal: false,
        interval: null,

        init() {
    const waitForMap = () => {
        if (window.themeInstalledMap && Object.keys(window.themeInstalledMap).length > 0) {
            const map = window.themeInstalledMap;
            this.installed = map[folderName] === true;
            this.buttonText = this.installed ? '✅ 설치됨' : '🚀 설치';
            this.buttonClass = this.installed
                ? 'bg-gray-300 text-gray-700 cursor-not-allowed'
                : 'bg-blue-600 text-white hover:bg-blue-700';
        } else {
            setTimeout(waitForMap, 100); // 설치 목록이 준비될 때까지 대기
        }
    };
    waitForMap();
},

        installTheme() {
            if (this.installed || this.loading) return;
            this.loading = true;
            this.buttonText = '⏳ 설치중...';

            fetch(`/themes/${themeId}/install`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ service_id: serviceId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.installed = true;
                    this.buttonText = '✅ 설치됨';
                    this.buttonClass = 'bg-gray-300 text-gray-700 cursor-not-allowed';
                } else {
                    this.buttonText = '❌ 실패';
                    this.buttonClass = 'bg-red-500 text-white';
                }
            })
            .catch(() => {
                this.buttonText = '❌ 실패';
                this.buttonClass = 'bg-red-500 text-white';
            })
            .finally(() => {
                this.loading = false;
            });
        },

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
        },

        prevSlide() {
            this.active = (this.active - 1 + this.screenshots.length) % this.screenshots.length;
        },

        nextSlide() {
            this.active = (this.active + 1) % this.screenshots.length;
        },

        closeModal() {
            this.showModal = false;
            this.stopSlider();
        }
    };
}
</script>

<script>
document.addEventListener('alpine:init', () => {
    window.themeInstalledMap = {};

    fetch(`/user/themes/{{ $service->id }}/installed`)
        .then(res => res.json())
        .then(installedFolders => {
            installedFolders.forEach(name => {
                themeInstalledMap[name.trim()] = true;
            });

            console.log('✅ 설치된 테마 목록:', themeInstalledMap);

            // 여기서 수동으로 모든 Alpine 컴포넌트를 refresh (강제 초기화)
            document.querySelectorAll('[x-data]').forEach(el => {
                el.__x && el.__x.updateElements(el);
            });
        });
});
</script>

<script src="//unpkg.com/alpinejs" defer></script>
