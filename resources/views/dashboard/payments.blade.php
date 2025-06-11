<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            결제내역
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto bg-white p-6 shadow rounded">
            <h3 class="text-2xl font-bold mb-4">💳 결제 내역 조회</h3>

            @if ($payments->isEmpty())
                <p class="text-gray-500">결제 내역이 없습니다.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
                                <th class="px-4 py-2">플랜</th>
                                <th class="px-4 py-2">결제금액</th>
                                <th class="px-4 py-2">상태</th>
                                <th class="px-4 py-2">결제일</th>
                                <th class="px-4 py-2">주문번호</th>
                                <th class="px-4 py-2">영수증</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr class="border-t text-sm">
                                    <td class="px-4 py-2">{{ $payment->plan->name ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ number_format($payment->amount) }}원</td>
                                    <td class="px-4 py-2">
                                        @if ($payment->status === 'PAID')
                                            <span class="text-green-600 font-semibold">성공</span>
                                        @else
                                            <span class="text-red-500 font-semibold">실패</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($payment->approved_at)->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2">{{ $payment->order_id }}</td>
                                    <td class="px-4 py-2">
    @if ($payment->receipt_url)
        <a href="{{ $payment->receipt_url }}" target="_blank" class="text-blue-600 underline">영수증</a>
    @else
        <span class="text-gray-400">-</span>
    @endif
</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
