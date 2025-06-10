<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">결제 완료</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
            <h1 class="text-2xl font-bold text-green-600 mb-4">✅ 결제가 완료되었습니다!</h1>

            <div class="space-y-3 text-gray-700">
                <p><span class="font-semibold">📦 플랜명:</span> {{ $planName }}</p>
                <p><span class="font-semibold">💳 결제 금액:</span> {{ number_format($amount) }}원</p>
                <p><span class="font-semibold">🧾 주문번호:</span> {{ $orderId }}</p>
                <p><span class="font-semibold">🌐 생성된 도메인:</span> <a href="http://{{ $domain }}" class="text-blue-600 underline" target="_blank">{{ $domain }}</a></p>
                <p><span class="font-semibold">📧 사용자 이메일:</span> {{ $email }}</p>
            </div>

            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded">
                    대시보드로 이동
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
