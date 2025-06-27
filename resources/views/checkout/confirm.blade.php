<x-app-layout>
        @section('title', '결제 완료 - Hostyle')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">결제 완료</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow border border-gray-300">
            <h1 class="text-2xl font-bold text-center text-green-700 mb-6">결제 영수증</h1>

            <div class="font-mono text-sm text-gray-800 space-y-2">
                <div class="flex justify-between border-b pb-1">
                    <span>플랜명</span>
                    <span>{{ $planName }} 플랜</span>
                </div>
                <div class="flex justify-between border-b pb-1">
                    <span>이용 기간</span>
                    <span>{{ $period }}개월</span>
                </div>
                <div class="flex justify-between border-b pb-1">
                    <span>디스크 용량</span>
                    <span>{{ $disk }}GB</span>
                </div>
                <div class="flex justify-between border-b pb-1">
                    <span>생성 도메인</span>
                    <span><a href="http://{{ $domain }}" class="text-blue-600 underline" target="_blank">{{ $domain }}</a></span>
                </div>
                <div class="flex justify-between border-b pb-1">
                    <span>사용자 이메일</span>
                    <span>{{ $email }}</span>
                </div>
                <div class="flex justify-between border-b pb-1">
                    <span>주문번호</span>
                    <span>{{ $orderId }}</span>
                </div>
                <div class="flex justify-between text-base font-bold pt-3">
                    <span>최종 결제 금액</span>
                    <span>{{ number_format($amount) }}원</span>
                </div>
            </div>

            <div class="border-t border-dashed my-6"></div>

            <p class="text-center text-xs text-gray-500">본 영수증은 전자상거래법에 따라 발급된 문서입니다.</p>

            <div class="mt-6 text-center">
                <a href="{{ route('dashboard') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                    대시보드로 이동
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
