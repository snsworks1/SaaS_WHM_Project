<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('installedThemes', {})

    fetch(`/user/themes/{{ $service->id }}/installed`)
        .then(res => res.json())
        .then(installedFolders => {
            Alpine.store('installedThemes', installedFolders.reduce((acc, folder) => {
                acc[folder] = true;
                return acc;
            }, {}));
        });
});
</script>

<div class="space-y-4">
    <h3 class="text-lg font-semibold">🧅 사용 가능한 테마</h3>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse (collect($themes)->where('status', 'enabled') as $theme)
            @php
                $isInstalled = $installedThemes[$theme->id] ?? false;
            @endphp

            <div
x-data="themeCard(
    {{ Js::from($theme->id) }},
    {{ Js::from($service->id) }},
    {{ Js::from($theme->name) }},
    {{ Js::from($theme->screenshots ?? []) }}
)"                class="border rounded-xl shadow p-4 bg-white"
                x-init="startSlider()"
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

                <template x-if="isInstalled">
    <button
        class="mt-4 w-full py-2 rounded text-sm bg-green-500 text-white cursor-default opacity-80"
        disabled
    >
        ✅ 설치됨
    </button>
</template>
<template x-if="!isInstalled">
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

<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function themeCard(themeId, serviceId, folderName, screenshots) {
    return {
        active: 0,
        showModal: false,
        interval: null,
        screenshots: screenshots,
        loading: false,

        get isInstalled() {
            return Alpine.store('installedThemes')[folderName] ?? false;
        },

        get buttonText() {
            if (this.loading) return '⏳ 설치중...';
            return this.isInstalled ? '✅ 설치 완료' : '🚀 설치';
        },

        get buttonClass() {
            if (this.isInstalled || this.loading) {
                return 'bg-gray-500 text-white cursor-not-allowed';
            }
            return 'bg-blue-600 text-white hover:bg-blue-700';
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
        },

        installTheme() {
            if (this.isInstalled || this.loading) return;
            this.loading = true;

            fetch(`/user/themes/${serviceId}/${themeId}/install`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                this.loading = false;

                if (data.status === 'success' || data.status === 'exists') {
                    Swal.fire({
                        icon: data.status === 'success' ? 'success' : 'info',
                        title: data.status === 'success' ? '설치 완료' : '이미 설치됨',
                        text: data.message || '테마 설치가 완료되었습니다.',
                        confirmButtonColor: '#3085d6',
                    });

                    // 설치됨 상태 갱신
                    Alpine.store('installedThemes')[folderName] = true;

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '설치 실패',
                        text: data.message || '문제가 발생했습니다.',
                    });
                }
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                Swal.fire({
                    icon: 'error',
                    title: '서버 오류',
                    text: '네트워크 또는 서버 오류가 발생했습니다.',
                });
                this.loading = false;
            });
        }
    };
}
</script>
