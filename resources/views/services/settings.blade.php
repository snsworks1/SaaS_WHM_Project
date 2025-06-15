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

            <!-- 설치 상태 -->
            <div id="wp-status" class="flex items-center gap-2 text-sm text-gray-700">
                <svg id="loadingSpinner" class="animate-spin h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                </svg>
                <span>설치 여부 확인 중...</span>
            </div>

            <!-- 설치 폼 -->
            <div id="installForm" class="hidden mt-4">
                <form id="installWordPressForm" method="POST" action="{{ route('services.installWordPress', $service->id) }}">
                    @csrf
                    <label for="wp_version" class="block mb-2 text-sm font-medium text-gray-700">설치할 워드프레스 버전 선택</label>
                    <select name="wp_version" id="wp_version" class="w-full border rounded p-2 mb-4">
                        @foreach (config('wordpress.versions') as $version => $url)
                            <option value="{{ $version }}" {{ $version === config('wordpress.default') ? 'selected' : '' }}>
                                WordPress {{ $version }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" id="installBtn" class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        🚀 워드프레스 설치
                    </button>
                </form>

                <!-- 진행 표시 -->
                <div id="progressArea" class="hidden mt-4">
                    <div id="progressText" class="text-sm text-gray-700">대기 중...</div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                        <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full w-0 transition-all duration-500"></div>
                    </div>
                </div>

                <!-- 결과 메시지 -->
                <div id="installResult" class="hidden mt-4 text-sm p-3 rounded"></div>
            </div>

            <hr>
            <h3 class="text-lg font-bold">테마 선택</h3>
            {{-- 향후 테마 UI --}}
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusEl = document.getElementById('wp-status');
            const spinner = document.getElementById('loadingSpinner');
            const installForm = document.getElementById('installForm');
            const formEl = document.getElementById('installWordPressForm');
            const installBtn = document.getElementById('installBtn');
            const progressArea = document.getElementById('progressArea');
            const progressText = document.getElementById('progressText');
            const progressBar = document.getElementById('progressBar');
            const installResult = document.getElementById('installResult');

            fetch('{{ route("services.checkWp", $service->id) }}', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                spinner.remove();
                if (data.installed) {
                    statusEl.innerHTML = `<span class="text-green-600">✅ 워드프레스 설치됨 (버전: <strong>${data.version}</strong>)</span>`;
                } else {
                    statusEl.innerHTML = `<span class="text-red-500">❌ 설치되지 않음</span>`;
                    installForm.classList.remove('hidden');
                }
            })
            .catch(err => {
                spinner.remove();
                statusEl.innerHTML = `<span class="text-red-500">⚠️ 상태 확인 실패</span>`;
                console.error(err);
            });

            formEl.addEventListener('submit', function (e) {
                e.preventDefault();

                installBtn.disabled = true;
                installBtn.innerText = '설치 중...';
                progressText.innerText = '서버에 요청 중...';
                progressBar.style.width = '10%';
                progressArea.classList.remove('hidden');
                installResult.classList.add('hidden');

                fetch(formEl.action, {
                    method: 'POST',
                    body: new FormData(formEl),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.text())
                .then(() => {
                    progressText.innerText = '압축 해제 중...';
                    progressBar.style.width = '90%';

                    setTimeout(() => {
                        installBtn.disabled = false;
                        installBtn.innerText = '🚀 워드프레스 설치';
                        progressBar.style.width = '100%';
                        progressText.innerText = '설치 완료 ✅';

                        installResult.className = 'bg-green-100 text-green-800';
                        installResult.innerText = '워드프레스 설치가 완료되었습니다!';
                        installResult.classList.remove('hidden');
                        statusEl.innerHTML = `<span class="text-green-600">✅ 설치 완료됨</span>`;
                    }, 2000);
                })
                .catch(err => {
                    installBtn.disabled = false;
                    installBtn.innerText = '🚀 워드프레스 설치';
                    progressBar.style.width = '0%';
                    progressText.innerText = '오류 발생';

                    installResult.className = 'bg-red-100 text-red-800';
                    installResult.innerText = '설치 중 오류가 발생했습니다.';
                    installResult.classList.remove('hidden');
                    console.error(err);
                });
            });
        });
    </script>
</x-app-layout>
