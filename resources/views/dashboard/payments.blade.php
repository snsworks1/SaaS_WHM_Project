<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            결제내역
        </h2>
        <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
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
        <a href="#" onclick="showReceiptModal('{{ $payment->receipt_url }}')" class="text-blue-600 underline">영수증</a>
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

    @push('scripts')
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

    function downloadScreenshot() {
        const iframe = document.getElementById('receiptFrame');
        const iframeWindow = iframe.contentWindow;
        html2canvas(iframeWindow.document.body).then(canvas => {
            const link = document.createElement('a');
            link.download = 'receipt.png';
            link.href = canvas.toDataURL();
            link.click();
        });
    }
</script>
@endpush

</x-app-layout>


<!-- Receipt Modal -->
<div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-3xl p-6 rounded shadow relative">
        <h2 class="text-xl font-bold mb-4">🧾 영수증 상세</h2>
        <iframe id="receiptFrame" src="" class="w-full h-[600px] border rounded"></iframe>

        <div class="mt-4 flex justify-end space-x-3">
            <button onclick="printReceipt()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">🖨️ 인쇄</button>
            <button onclick="downloadScreenshot()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">📸 이미지 저장</button>
            <button onclick="closeModal()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">닫기</button>
        </div>
    </div>
</div>