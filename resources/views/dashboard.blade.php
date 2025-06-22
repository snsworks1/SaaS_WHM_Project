<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">내 서비스 목록</h2>
    </x-slot>


    <!-- 📊 대시보드 요약 카드 -->
<div class="max-w-6xl mx-auto mt-6 mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white border shadow-sm p-6 rounded-xl">
        <h4 class="text-gray-500 text-sm mb-1">웹 서버 사용중</h4>
        <p class="text-2xl font-bold text-blue-600">{{ $activeServiceCount }}개</p>
    </div>
    <div class="bg-white border shadow-sm p-6 rounded-xl">
        <h4 class="text-gray-500 text-sm mb-1">D-3 이내 만료 예정</h4>
        <p class="text-2xl font-bold text-red-500">{{ $expiringSoonCount }}개</p>
    </div>
    <div class="bg-white border shadow-sm p-6 rounded-xl">
        <h4 class="text-gray-500 text-sm mb-1">총 월 이용 금액</h4>
        <p class="text-2xl font-bold text-green-600">{{ number_format($monthlyTotal) }}원</p>
    </div>
</div>


<div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">           
     @forelse ($services as $service)
        <div class="bg-white border shadow-sm rounded-xl p-5">
                                <h3 class="font-bold text-lg mb-2">{{ $service->plan->name }}</h3>
                    <p class="text-gray-700 mb-1"><strong>도메인:</strong> {{ $service->whm_domain }}</p>
                    <p class="text-gray-700 mb-1"><strong>WHM 계정:</strong> {{ $service->whm_username }}</p>
                    <div class="mt-2 text-sm text-gray-600">
    만료일: <span class="font-medium text-gray-800">{{ $service->expired_at->format('Y년 m월 d일') }}</span>
</div>
                    <p class="text-gray-500 text-sm">생성: {{ $service->created_at->format('Y-m-d H:i') }}</p>

                    <!-- ✅ 버튼 영역 추가 -->
                    <div class="flex justify-between items-center mt-4">
                        <!-- cPanel 바로가기 -->
                        <button onclick="openCpanel({{ $service->id }})"
                    class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    cPanel 바로가기
                </button>




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

    <a href="{{ route('services.settings', $service->id) }}"
                   class="px-3 py-1 bg-gray-700 text-white text-sm rounded hover:bg-gray-800">
                    설정
                </a>
                        <!-- 업/다운그레이드 버튼 -->
                        <a href="{{ route('services.changePlan', $service->id) }}"
                   class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                    플랜변경
                </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-600">
                    생성된 서비스가 없습니다.
                </div>
            @endforelse
        </div>
 


        @include('components.dashboard-notice-patchnote')

</x-app-layout>
