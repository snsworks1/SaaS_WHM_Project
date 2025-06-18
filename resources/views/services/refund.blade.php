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
            <h3 class="text-lg font-bold">💰 환불 계산</h3>
            <p>⚡ 사용 금액: {{ number_format($usedAmount) }}원</p>
            <p>📄 할인 위약금: {{ number_format($penalty) }}원</p>
            <p class="text-green-600 font-bold text-lg">환불 가능 금액: {{ number_format($refundable) }}원</p>

            <form method="POST" action="{{ route('services.processRefund', $service->id) }}">
    @csrf
    <label for="reason" class="block font-semibold mt-4 mb-2">환불 사유</label>
    <select name="reason" class="w-full border rounded p-2">
        <option value="기본 포털 또는 보통 문제">기본 포털 또는 보통 문제</option>
        <option value="기능 부족">기능이 부족하거나 기대와 다름</option>
        <option value="서비스 오류">서비스 오류/버그 발생</option>
        <option value="타사 이동">다른 서비스로 이동/이전</option>
        <option value="기타">기타</option>
    </select>

    <button type="submit"
            class="mt-4 w-full py-2 bg-red-600 text-white rounded hover:bg-red-700">
        환불 요청하기
    </button>
</form>

        </div>
    </div>

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
