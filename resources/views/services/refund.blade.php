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
            <p><strong>플랜:</strong> {{ $plan->name }} ({{ $durationDays }}일)</p>
            <p><strong>사용일수:</strong> {{ $daysUsed }}일 / <strong>남은일수:</strong> {{ $daysLeft }}일</p>

            <hr>
<h3 class="text-lg font-bold">💰 환불 상세 계산</h3>

<table class="w-full text-sm text-left border border-gray-300">
    <tbody>
        <tr class="border-b">
            <td class="font-semibold p-2">총 결제금액</td>
            <td class="p-2">{{ number_format($service->payment->amount) }}원 ({{ $durationDays }}일)</td>
        </tr>
        <tr class="border-b">
    <td class="font-semibold p-2">사용 금액</td>

    <td class="p-2 text-gray-700">
    ⚡ {{ number_format($usedAmount) }}원 
    ({{ $chargedDays }}일 사용 처리
    @if ($chargedDays !== $daysUsed)
        — 실제 사용 {{ $daysUsed }}일    ※ 월 14일 초과시 1개월 사용으로 처리
    @endif
    )
</td>
</tr>
        <tr class="border-b">
            <td class="font-semibold p-2">할인 위약금</td>
            <td class="p-2 text-gray-700">📄 {{ number_format($penalty) }}원  
                @if ($penalty > 0)
                    <span class="text-xs text-gray-500">(할인금액 × 잔여일 / 전체일)</span>
                @endif
            </td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold p-2">총 차감 금액</td>
            <td class="p-2 font-medium text-red-600">
                {{ number_format($usedAmount + $penalty) }}원
            </td>
        </tr>
        <tr>
            <td class="font-semibold p-2">총 환불 금액</td>
            <td class="p-2 font-bold text-green-600 text-lg">
                {{ number_format($refundable) }}원
            </td>
        </tr>
    </tbody>
</table>

            <form method="POST" action="{{ route('services.processRefund', $service->id) }}" id="refundForm">
    @csrf
    @php
    $canRefund = $refundable > 0 && $canRefund; // 금액도 0보다 커야 환불 가능
@endphp
    <label for="reason" class="block font-semibold mt-4 mb-2">환불 사유</label>
    <select name="reason" class="w-full border rounded p-2" {{ !$canRefund ? 'disabled' : '' }}>
        <option value="기본 포털 또는 보통 문제">기본 포털 또는 보통 문제</option>
        <option value="기능 부족">기능이 부족하거나 기대와 다름</option>
        <option value="서비스 오류">서비스 오류/버그 발생</option>
        <option value="타사 이동">다른 서비스로 이동/이전</option>
        <option value="기타">기타</option>
    </select>

    <button type="button"
    class="mt-4 w-full py-2 rounded text-white {{ $canRefund ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-400 cursor-not-allowed' }}"
    onclick="showRefundConfirmModal(this)"  {{-- ← this 추가 --}}
    data-amount="{{ number_format($refundable) }}"
    {{ !$canRefund ? 'disabled' : '' }}>
    환불 요청하기
</button>

    @unless($canRefund)
        <p class="mt-2 text-sm text-red-600 text-center">⚠️ 환불 조건을 만족하지 않거나 환불 가능 금액이 없습니다.</p>
    @endunless
</form>

        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showRefundConfirmModal(button) {
    const amount = button.getAttribute('data-amount');

    Swal.fire({
        title: '환불을 진행할까요?',
        html: `
            <div class="text-left text-sm text-gray-700 leading-relaxed">
                <p><strong>💰 환불 금액:</strong> <span class="text-green-600 font-semibold">${amount}원</span></p>
                <p class="mt-2 text-red-600 font-semibold">⚠️ 환불 처리 시 해당 서버 계정, 웹 파일, 데이터베이스는 즉시 삭제됩니다.</p>
                <label class="mt-4 block text-sm">
                    <input type="checkbox" id="agreeDelete" onchange="document.getElementById('confirmRefundBtn').disabled = !this.checked">
                    <span class="ml-1">위 내용을 확인했으며 데이터 삭제에 동의합니다.</span>
                </label>
            </div>
        `,
        showCancelButton: true,
        cancelButtonText: '취소',
        confirmButtonText: '환불 진행하기',
        customClass: {
            confirmButton: 'bg-red-600 text-white px-4 py-2 rounded disabled:opacity-50',
        },
        didOpen: () => {
            const confirmBtn = Swal.getConfirmButton();
            confirmBtn.id = 'confirmRefundBtn';
            confirmBtn.disabled = true;
        },
        preConfirm: () => {
            document.getElementById('refundForm').submit();
        }
    });
}
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
