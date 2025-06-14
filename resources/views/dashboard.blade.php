<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">내 서비스 목록</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($services as $service)
                <div class="bg-white shadow rounded-2xl p-6 border">
                    <h3 class="font-bold text-lg mb-2">{{ $service->plan->name }}</h3>
                    <p class="text-gray-700 mb-1"><strong>도메인:</strong> {{ $service->whm_domain }}</p>
                    <p class="text-gray-700 mb-1"><strong>WHM 계정:</strong> {{ $service->whm_username }}</p>
                    <p class="text-gray-500 text-sm">생성: {{ $service->created_at->format('Y-m-d H:i') }}</p>

                    <!-- ✅ 버튼 영역 추가 -->
                    <div class="flex justify-between items-center mt-4">
                        <!-- cPanel 바로가기 -->
                        <a href="https://panel-admin-01.hostyle.me:2083/login/?user={{ $service->whm_username }}" target="_blank"
    class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
    cPanel 접속 
</a>

    <a href="{{ route('services.settings', $service->id) }}"
   class="inline-flex items-center px-3 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 text-sm">
   설정
</a>
                        <!-- 업/다운그레이드 버튼 -->
                        <a href="{{ route('services.changePlan', $service->id) }}"
                           class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">
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
    </div>
</x-app-layout>
