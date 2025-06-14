<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">서비스 설정 - {{ $service->whm_domain }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white rounded-xl shadow p-6 space-y-6">
            <p><strong>WHM 계정:</strong> {{ $service->whm_username }}</p>
            <p><strong>도메인:</strong> {{ $service->whm_domain }}</p>

            <hr>

            <h3 class="text-lg font-bold">워드프레스 자동 설치</h3>

            <!-- ✅ 상태 영역 -->
            <div id="wp-status" class="text-sm text-gray-700 flex items-center gap-2">
                <svg id="loadingSpinner" class="animate-spin h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>
                </svg>
                <span>설치 여부 확인 중...</span>
            </div>

            <!-- ✅ 설치 버튼 (초기엔 숨김) -->
            <div id="installForm" class="hidden">
    <button id="installBtn" type="button"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        🚀 워드프레스 자동 설치
    </button>

    <!-- ✅ 진행률 표시 -->
    <div class="mt-3 text-sm text-gray-700">
        <div id="progressText">대기 중...</div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
            <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full w-0 transition-all duration-500"></div>
        </div>
    </div>
</div>

            <hr>

            <h3 class="text-lg font-bold">테마 선택</h3>
            {{-- 템플릿 목록 출력 예정 --}}
        </div>
    </div>

    <!-- CSRF 토큰 -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusEl = document.getElementById('wp-status');
            const spinner = document.getElementById('loadingSpinner');
            const installForm = document.getElementById('installForm');

            fetch('{{ route("services.checkWp", $service->id) }}', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                // 스피너 제거
                spinner.remove();

                if (data.installed) {
                    statusEl.innerHTML = `
                        <span class="text-green-600">✅ 워드프레스가 설치되어 있습니다 (버전: <strong>${data.version}</strong>)</span>
                    `;
                } else {
                    statusEl.innerHTML = `<span class="text-red-500">❌ 워드프레스가 설치되어 있지 않습니다.</span>`;
                    installForm.classList.remove('hidden');
                }
            })
            .catch(err => {
                console.error(err);
                spinner.remove();
                statusEl.innerHTML = `<span class="text-red-500">⚠️ 상태 확인 실패</span>`;
            });
        });


    </script>
</x-app-layout>
