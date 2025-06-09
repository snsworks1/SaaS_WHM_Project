

<x-app-layout>
    <x-slot name="header">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">결제 확인</h2>
    </x-slot>

    <div class="py-12">
    @include('components.upgrade-progress', ['step' => 2])

        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
            <h3 class="font-bold text-lg mb-4">결제 정보</h3>

            <p>현재 플랜: <strong>{{ $service->plan->name }}</strong></p>
            <p>변경할 플랜: <strong>{{ $newPlan->name }}</strong></p>

            <!-- ✅ 정산 룰 안내 -->
            <div class="bg-yellow-100 text-yellow-700 text-sm rounded p-3 my-4">
                ※ 요금 정산 안내: 업그레이드 요금은 사용일수를 기준으로 일할 계산되며,<br>
                가입 당일 변경 시 최소 1일 이용으로 계산됩니다.
            </div>

            <!-- ✅ 계산내역 추가 -->
            <div class="mb-4">
                <p>사용일수: <strong>{{ $effectiveDays }}일</strong></p>
                <p>일할 사용금액: <strong>{{ number_format($currentUsedAmount) }}원</strong></p>
            </div>

            <p class="mt-4 text-xl font-bold text-blue-600">
                결제할 금액: {{ number_format($finalAmount) }}원
            </p>

            <form method="POST" action="{{ route('services.processUpgrade', $service->id) }}" class="mt-6">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $newPlan->id }}">
                <div class="flex justify-between mt-8 space-x-4">
    <a href="{{ route('services.changePlan', $service->id) }}" 
       class="px-6 py-3 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
        ← 이전
    </a>

    <button type="submit" class="user-button w-48">
        결제하기 (임시완료)
    </button>
</div>


            </form>
        </div>
    </div>
</x-app-layout>
