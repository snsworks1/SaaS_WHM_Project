
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">💳 서비스 연장 결제</h2>
    </x-slot>

    <div class="max-w-xl mx-auto py-12">
        <div class="bg-white p-6 rounded shadow space-y-4">
            <p class="text-gray-700">
                <strong>{{ $service->whm_domain }}</strong> 서비스에 대해<br>
                <strong>{{ $period }}개월</strong> 연장을 진행합니다.
            </p>

            <p class="text-gray-700">결제 금액: <strong class="text-blue-600 text-lg">₩{{ number_format($amount) }}</strong></p>

            <div class="mt-6">
                <button onclick="startExtendPayment()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
                    💳 결제하기
                </button>
            </div>
        </div>
    </div>

<script src="https://js.tosspayments.com/v1"></script>
<script>
    const clientKey = '{{ config('services.toss.client_key') }}';
    const tossPayments = TossPayments(clientKey);

    function startExtendPayment() {
        tossPayments.requestPayment('카드', {
    amount: {{ $amount }},
    orderId: '{{ $orderId }}',
    orderName: '{{ $service->domain }} 서비스 연장',
    customerName: '{{ Auth::user()->name }}',
    successUrl: '{{ route("services.extend.confirm", ["id" => $service->id]) }}'
        + '?orderId={{ $orderId }}'
        + '&amount={{ $amount }}'
        + '&period={{ $period }}',  // ✅ 꼭 포함!
    failUrl: '{{ route("services.extend.fail", ["id" => $service->id]) }}'
});
    }
</script>

</x-app-layout>