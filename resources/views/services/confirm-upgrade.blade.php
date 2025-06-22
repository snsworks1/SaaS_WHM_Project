

<x-app-layout>
    <x-slot name="header">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">


        <h2 class="font-semibold text-xl text-gray-800 leading-tight">결제 확인</h2>
    </x-slot>

    <div class="py-12">
    @include('components.upgrade-progress', ['step' => 2])

        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
                    <!-- 🔍 플랜 요금 비교 -->
<div class="bg-gray-50 border border-gray-200 rounded p-4 mt-6 text-sm">
    <h4 class="font-semibold text-gray-800 mb-2">📊 플랜 요금 비교</h4>
    <ul class="space-y-1 text-gray-700">
        <li>ㆍ기존 플랜 월 요금 (할인 적용): <strong>{{ number_format($currentPriceWithDiscount) }}원</strong></li>
        <li>ㆍ새 플랜 월 요금 (할인 적용): <strong>{{ number_format($newPriceWithDiscount) }}원</strong></li>
        <li>ㆍ계약 기간: <strong>{{ $period }}개월</strong></li>
        <li>ㆍ사용일수: <strong>{{ number_format($usedDays) }}일</strong></li>
        <li>ㆍ기존 플랜에서 차감될 금액: <strong>{{ number_format($usedAmount) }}원</strong></li>
<li>ㆍ서비스 만료일: <strong>{{ \Carbon\Carbon::parse($service->expired_at)->format('Y년 n월 j일') }}</strong></li>    </ul>

    <p class="mt-4 text-xl font-bold text-blue-600">
        결제할 금액: {{ number_format($finalAmount) }}원
    </p>   
</div>

<!-- 📘 결제 금액 산정 설명 -->
<div class="bg-blue-50 border border-blue-200 rounded p-4 mt-4 text-sm text-blue-800 leading-relaxed">
    <h4 class="font-semibold text-blue-900 mb-1">💡 요금 산정 방식</h4>
    <p>
        업그레이드 시 기존 플랜의 <strong>남은 가치</strong>는 자동 차감되며,<br>
        새 플랜의 <strong>잔여 기간에 해당하는 요금</strong>만 결제됩니다.
    </p>
    <p class="mt-2 text-xs text-blue-600">
        공식: <br>
        <code>
            업그레이드 금액 = (새 플랜 할인가 / 전체일수 × 잔여일수)<br>
            차감 금액 = (기존 플랜 할인가 / 전체일수 × 잔여일수)<br>
            최종 결제 금액 = 업그레이드 금액 - 차감 금액
        </code>
    </p>
</div>

            

           
                <input type="hidden" name="plan_id" value="{{ $newPlan->id }}">
                <div class="flex justify-between mt-8 space-x-4">
    <a href="{{ route('services.changePlan', $service->id) }}" 
       class="px-6 py-3 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
        ← 이전
    </a>

    <!-- Toss SDK 로드 -->
<script src="https://js.tosspayments.com/v1"></script>
<script>
    const clientKey = '{{ config('services.toss.client_key') }}'; // .env 설정
    const tossPayments = TossPayments(clientKey);

    function requestPayment() {
        tossPayments.requestPayment('카드', {
            amount: {{ $finalAmount }},
            orderId: '{{ uniqid("upgrade_") }}',
            orderName: '{{ $newPlan->name }} 플랜 업그레이드',
            customerName: '{{ Auth::user()->name }}',
            successUrl: '{{ route("upgrade.payment.success", ["id" => $service->id]) }}',
            failUrl: '{{ route("upgrade.payment.fail", ["id" => $service->id]) }}'
        });
    }
</script>

    <button onclick="requestPayment()" class="user-button w-48">결제하기</button>
</div>


        </div>
    </div>
</x-app-layout>
