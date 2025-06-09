
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">플랜 변경 완료</h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

            {{-- ✅ Progress Indicator --}}
            @include('components.upgrade-progress', ['step' => 3])

            <h3 class="text-xl font-bold mb-4 text-center text-green-600">업그레이드가 성공적으로 완료되었습니다!</h3>

            <div class="text-center text-gray-700 mb-6">
                선택한 플랜: <strong>{{ $newPlan->name }}</strong><br>
                사용중인 도메인: <strong>{{ $service->whm_domain }}</strong>
            </div>

            <div class="flex justify-center">
                <a href="{{ route('dashboard') }}" 
                   class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                   대시보드로 돌아가기
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
