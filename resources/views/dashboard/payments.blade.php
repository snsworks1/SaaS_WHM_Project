<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            결제 내역
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto bg-white p-8 shadow rounded-lg">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">💳 결제 내역</h3>

            @if ($payments->isEmpty())
                <p class="text-gray-500 text-sm">결제 내역이 없습니다.</p>
            @else
                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-100 text-xs uppercase tracking-wide text-gray-600 border-b">
                            <tr>
                                <th class="px-4 py-3">플랜</th>
                                <th class="px-4 py-3">결제금액</th>
                                <th class="px-4 py-3">상태</th>
                                <th class="px-4 py-3">결제일</th>
                                <th class="px-4 py-3">주문번호</th>
                                <th class="px-4 py-3">영수증</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr class="border-t hover:bg-gray-50">
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
                                    <td class="px-4 py-2 font-mono text-xs text-gray-500">{{ $payment->order_id }}</td>
                                    <td class="px-4 py-2">
                                        <button
                                            onclick="showReceiptModal('{{ route('dashboard.payments.receipt', ['order_id' => $payment->order_id]) }}')"
                                            class="text-blue-600 underline hover:text-blue-800"
                                        >
                                            보기
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-4xl p-6 rounded shadow relative">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">🧾 영수증 상세</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
            </div>

            <iframe id="receiptFrame" src="" class="w-full h-[600px] border rounded"></iframe>

            <div class="mt-4 flex justify-end space-x-3">
                <button onclick="closeModal()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">닫기</button>
            </div>
        </div>
    </div>

    <!-- JS inline -->
    <script>
        function showReceiptModal(url) {
            const modal = document.getElementById('receiptModal');
            const frame = document.getElementById('receiptFrame');
            frame.src = url;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('receiptModal');
            const frame = document.getElementById('receiptFrame');
            frame.src = '';
            modal.classList.add('hidden');
        }

        function printReceipt() {
            const iframe = document.getElementById('receiptFrame');
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        }
    </script>
</x-app-layout>
