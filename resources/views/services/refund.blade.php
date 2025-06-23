<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">환불 요청</h2>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </x-slot>

    <div class="max-w-3xl mx-auto py-10">
        <div class="bg-white p-6 rounded shadow space-y-6">
            <h3 class="text-lg font-bold">🧅 서비스 정보</h3>
            <p><strong>WHM 계정:</strong> {{ $service->whm_username }}</p>
            <p><strong>도메인:</strong> {{ $service->whm_domain }}</p>
            <p><strong>플랜:</strong> {{ $plan->name }} ({{ $calc['durationDays'] }}일)</p>
            <p><strong>주문번호 :</strong> {{ $service->payment->order_id ?? 'N/A' }}</p>
            <p><strong>📆 서비스 기간:</strong> {{ $calc['startDate'] }} ~ {{ \Carbon\Carbon::parse($service->expired_at)->toDateString() }}</p>

            @if ($calc['isEarlyExtensionRefund'])
                <p class="text-blue-600 font-semibold">
                    ⏱ 현재 연장 이전의 서비스 기간을 사용중 입니다.<br>
                    환불시 연장분에 대해 전액 환불됩니다. <br>(기존 서비스 14일 초과로 환불 불가)
                </p>
            @else
                <p><strong>사용일수:</strong> {{ $daysUsed }}일 / <strong>남은일수:</strong> {{ $daysLeft }}일</p>
            @endif

            <hr>

            <h3 class="text-lg font-bold">💰 환불 계산</h3>
            <ul class="space-y-1 text-sm">
                <li>⚡ <strong>사용 금액:</strong> {{ number_format($usedAmount) }}원</li>
                <li>📄 <strong>할인 위약금:</strong> {{ number_format($penalty) }}원</li>
                <li class="text-green-600 font-bold text-lg">💵 환불 가능 금액: {{ number_format($refundable) }}원</li>
            </ul>

            @if ($calc['isEarlyExtensionRefund'])
                <div class="bg-blue-50 text-sm text-blue-700 p-3 rounded mt-2">
                    <strong>※ 안내:</strong> 이번 환불은 기존 만료일 ({{ \Carbon\Carbon::parse($calc['startDate'])->format('Y-m-d') }}) 이전에 결제된 연장 서비스입니다.<br> 환불 시 전체 금액이 반환됩니다.
                </div>
            @elseif (!$calc['isEligible'])
                <div class="bg-yellow-50 text-sm text-yellow-700 p-3 rounded mt-2">
                    <strong>※ 안내:</strong> 사용일이 14일을 초과하여 환불이 불가능합니다.
                </div>
            @endif

            <form id="refundForm" method="POST" action="{{ route('services.processRefund', $service->id) }}">
                @csrf

                <label for="reason" class="block font-semibold mt-4 mb-2">환불 사유</label>
                <select name="reason" class="w-full border rounded p-2">
                    <option value="기본 포털 또는 보통 문제">기본 포털 또는 보통 문제</option>
                    <option value="기능 부족">기능이 부족하거나 기대와 다름</option>
                    <option value="서비스 오류">서비스 오류/버그 발생</option>
                    <option value="타사 이동">다른 서비스로 이동/이전</option>
                    <option value="기타">기타</option>
                </select>

                @if ($refundable <= 0)
                    <button type="button" disabled class="mt-4 w-full py-2 bg-gray-400 text-white rounded cursor-not-allowed">
                        환불 불가
                    </button>
                @else
                    <div class="mt-4">
                        <label class="inline-flex items-center space-x-2 text-sm">
                            <input type="checkbox" id="confirmCheck" class="rounded border-gray-300">
                            <span>
                                환불은 즉시 처리되며, <strong>서비스는 삭제되고 복구가 불가능합니다</strong>.
                            </span>
                        </label>
                    </div>

                    <button type="button" id="submitRefundBtn" disabled class="mt-4 w-full py-2 bg-red-600 text-white rounded hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        환불 요청하기
                    </button>
                @endif
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('confirmCheck');
            const submitBtn = document.getElementById('submitRefundBtn');
            const form = document.getElementById('refundForm');

            checkbox.addEventListener('change', function () {
                submitBtn.disabled = !this.checked;
            });

            submitBtn?.addEventListener('click', function (e) {
                Swal.fire({
                    icon: 'warning',
                    title: '정말 환불하시겠습니까?',
                    html: '환불은 즉시 처리되며<br><strong>데이터 복구가 불가능합니다.</strong>',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: '환불 진행',
                    cancelButtonText: '취소'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '환불 완료!',
                    text: '{{ session('success') }}',
                    confirmButtonText: '메인으로 이동',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('dashboard') }}";
                    }
                });
            });
        </script>
    @endif
</x-app-layout>
