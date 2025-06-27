<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            결제 내역
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 shadow rounded-lg">
            <h3 class="text-lg sm:text-2xl font-bold text-gray-800 mb-6">💳 결제 내역</h3>

            <!-- 🔍 필터 -->
            <form method="GET" class="mb-6 flex flex-wrap gap-4">
                <div class="w-full sm:w-auto">
                    <label for="status" class="block text-sm font-medium text-gray-700">상태</label>
                    <select name="status" id="status" class="border rounded px-3 py-2 w-full sm:w-40">
                        <option value="">전체</option>
                        <option value="PAID" {{ request('status') === 'PAID' ? 'selected' : '' }}>성공</option>
                        <option value="CANCELED" {{ request('status') === 'CANCELED' ? 'selected' : '' }}>환불됨</option>
                        <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>실패</option>
                    </select>
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-sm font-medium text-gray-700">시작일</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="border rounded px-3 py-2 w-full sm:w-40">
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-sm font-medium text-gray-700">종료일</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="border rounded px-3 py-2 w-full sm:w-40">
                </div>
                <div class="flex items-end w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">적용</button>
                </div>
            </form>

            @if ($payments->isEmpty())
                <p class="text-gray-500 text-sm">결제 내역이 없습니다.</p>
            @else
                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-100 text-xs uppercase tracking-wide text-gray-600 border-b">
                            <tr>
                                <th class="px-4 py-3">유형</th>
                                <th class="px-4 py-3">플랜</th>
                                <th class="px-4 py-3">도메인</th>
                                <th class="px-4 py-3">금액</th>
                                <th class="px-4 py-3">상태</th>
                                <th class="px-4 py-3">결제일</th>
                                <th class="px-4 py-3">주문번호</th>
                                <th class="px-4 py-3">영수증</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                @php
                                    $typePrefix = explode('_', $payment->order_id)[0];
                                    [$typeLabel, $badgeClass] = match ($typePrefix) {
                                        'order'   => ['신규결제', 'bg-blue-100 text-blue-700'],
                                        'extend'  => ['연장결제', 'bg-green-100 text-green-700'],
                                        'upgrade' => ['플랜변경', 'bg-purple-100 text-purple-700'],
                                        default   => ['기타', 'bg-gray-100 text-gray-700'],
                                    };
                                @endphp
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded {{ $badgeClass }}">
                                            {{ $typeLabel }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">{{ $payment->plan->name ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $payment->service->whm_domain ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ number_format($payment->amount) }}원</td>
                                    <td class="px-4 py-2">
                                        @if ($payment->status === 'PAID' || $payment->status === 'paid')
                                            <span class="text-green-600 font-semibold">결제완료</span>
                                        @elseif ($payment->status === 'CANCELED')
                                            <span class="text-gray-600 font-semibold">환불</span>
                                        @else
                                            <span class="text-red-500 font-semibold">실패</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($payment->approved_at)->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2 font-mono text-xs text-gray-500">{{ $payment->order_id }}</td>
                                    <td class="px-4 py-2">
                                        <button
                                            onclick="showReceiptModal('{{ route('dashboard.payments.receipt', ['order_id' => $payment->order_id]) }}')"
                                            class="text-blue-600 underline hover:text-blue-800 text-sm"
                                        >
                                            보기
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ✅ 페이징 -->
                <div class="mt-6">
                    {{ $payments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-4xl p-4 sm:p-6 rounded shadow relative">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg sm:text-xl font-bold">🧾 영수증 상세</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
            </div>
            <iframe id="receiptFrame" src="" class="w-full h-[60vh] sm:h-[600px] border rounded"></iframe>
            <div class="mt-4 flex justify-end">
                <button onclick="closeModal()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">닫기</button>
            </div>
        </div>
    </div>

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
    </script>
</x-app-layout>
