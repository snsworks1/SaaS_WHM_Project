<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">내 서비스 목록</h2>
    </x-slot>

    <!-- 패널 캐릭터 -->
    <div class="max-w-6xl mx-auto mt-6 mb-8 grid grid-cols-1 md:grid-cols-3 gap-4 px-4 sm:px-6 lg:px-0">
        <div class="bg-white border shadow-sm p-6 rounded-xl">
            <h4 class="text-gray-500 text-sm mb-1">웹 서버 사용중</h4>
            <p class="text-2xl font-bold text-blue-600">{{ $activeServiceCount }}개</p>
        </div>
        <div class="bg-white border shadow-sm p-6 rounded-xl">
            <h4 class="text-gray-500 text-sm mb-1">D-3 이내 만료 예정</h4>
            <p class="text-2xl font-bold text-red-500">{{ $expiringSoonCount }}개</p>
        </div>
        <div class="bg-white border shadow-sm p-6 rounded-xl">
            <h4 class="text-gray-500 text-sm mb-1">청 월 이용 금액</h4>
            <p class="text-2xl font-bold text-green-600">{{ number_format($monthlyTotal) }}원</p>
        </div>
    </div>

    <!-- 서비스 캐릭 목록 -->
    <div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-6 lg:px-0">
        @forelse ($services as $service)
            @php
                $expiredAt = \Carbon\Carbon::parse($service->expired_at);
                $daysLeft = (int) now()->diffInDays($expiredAt, false);
                $dColor = $daysLeft <= 3 ? 'bg-red-100 text-red-600' : ($daysLeft <= 7 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700');
                $dText = $daysLeft < 0 ? 'D+' . abs($daysLeft) : 'D-' . $daysLeft;
            @endphp

            <div class="bg-white border shadow-sm rounded-2xl p-6 flex flex-col justify-between hover:shadow-md transition group">

                <!-- 상단 플랜명 + D-Day -->
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">플랜</p>
                        <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition">{{ $service->plan->name }}</h3>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full font-semibold {{ $dColor }}">
                        {{ $dText }} @if ($daysLeft <= 7) (연장 필요) @endif
                    </span>
                </div>

                <!-- 정보 영역 -->
                <div class="text-sm space-y-2 text-gray-700 mb-6">
                    <div class="flex items-center justify-between flex-wrap">
                        <p class="w-full sm:w-auto">
                            <span class="font-semibold text-gray-900">도메인:</span>
                            {{ $service->whm_domain }}
                        </p>
                        <a href="http://{{ $service->whm_domain }}" target="_blank"
                        class="text-xs text-blue-600 hover:underline whitespace-nowrap">
                        🔗 바로가기
                        </a>
                    </div>
                    <p><span class="font-semibold text-gray-900">WHM 계정:</span> {{ $service->whm_username }}</p>
                    <p><span class="font-semibold text-gray-900">만료일:</span> {{ $service->expired_at->format('Y년 m월 d일') }}</p>
                    <p class="text-gray-400 text-xs">생성일: {{ $service->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <!-- 버튼 영역 -->
                <div class="flex flex-col sm:flex-row gap-2 mt-auto">
                    <button onclick="openCpanel({{ $service->id }})"
                        class="w-full sm:w-1/3 text-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                        cPanel 이동
                    </button>
                    <a href="{{ route('services.settings', $service->id) }}"
                        class="w-full sm:w-1/3 text-center px-3 py-2 bg-gray-700 text-white text-sm rounded-lg hover:bg-gray-800 transition">
                        설정
                    </a>
                    <a href="{{ route('services.changePlan', $service->id) }}"
                        class="w-full sm:w-1/3 text-center px-3 py-2 bg-yellow-500 text-white text-sm rounded-lg hover:bg-yellow-600 transition">
                        플랜변경
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-600">생성된 서비스가 없습니다.</div>
        @endforelse
    </div>

    <script>
    function openCpanel(id) {
        fetch(`/services/${id}/cpanel-url`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.open(data.url, '_blank');
                } else {
                    alert('cPanel URL을 가져오지 못했습니다.');
                }
            });
    }
    </script>

    @include('components.dashboard-notice-patchnote')
</x-app-layout>
