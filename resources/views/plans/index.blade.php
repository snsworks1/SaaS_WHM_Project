<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">서비스 신청</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto">
        <form id="multiStepForm" method="POST">
            @csrf

            {{-- Step 1: 플랜 선택 --}}
            <div id="step-1">
                @include('components.upgrade-progress-5', ['step' => 1])
                <h3 class="text-xl font-bold mb-6">1단계: 플랜 선택</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach ($plans as $plan)
                        <label class="cursor-pointer">
                            <input type="radio" name="plan_id" value="{{ $plan->id }}"
                                   data-price="{{ $plan->price }}" data-name="{{ $plan->name }}"
                                   class="peer hidden" required>
                            <div class="p-5 border rounded-lg transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:shadow-md">
                                <div class="text-lg font-semibold">{{ $plan->name }}</div>
                                <div class="text-sm text-gray-600">{{ number_format($plan->price) }}원 / {{ $plan->disk_size }}GB</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                <div class="mt-6 text-right">
                    <button type="button" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition" onclick="goToStep(2)">다음</button>
                </div>
            </div>

            {{-- Step 2: 요금 확인 --}}
            <div id="step-2" class="hidden">
                @include('components.upgrade-progress-5', ['step' => 2])
                <h3 class="text-xl font-bold mb-6">2단계: 결제 기간 선택</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach ([1 => '1개월', 3 => '3개월 (2%)', 6 => '6개월 (4%)', 12 => '1년 (10%)', 24 => '2년 (20%)'] as $key => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="duration" value="{{ $key }}" class="peer hidden" {{ $key == 1 ? 'checked' : '' }}>
                            <div class="p-4 border rounded-lg text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:shadow-md">
                                <div class="font-semibold">{{ $label }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
<div id="price-summary" class="mt-4 bg-blue-50 border border-blue-300 rounded p-4 text-blue-800 font-semibold text-sm shadow-sm hidden">
    <!-- 자바스크립트에서 내용 채워짐 -->
</div>
<div id="early-cancel-warning" class="text-sm text-red-500 mt-2 hidden leading-snug">
    <!-- 할인 시 표시 -->
</div>                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="goToStep(1)" class="px-6 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">이전</button>
                    <button type="button" onclick="goToStep(3)" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">다음</button>
                </div>
            </div>

            {{-- Step 3: 사용자 정보 입력 --}}
            <div id="step-3" class="hidden">
                @include('components.upgrade-progress-5', ['step' => 3])
                <h3 class="text-xl font-bold mb-6">3단계: 사용자 정보 입력</h3>
                <div class="mb-4">
                    <label class="block font-medium mb-1">WHM 아이디</label>
                    <input type="text" id="username" name="whm_username" class="w-full border rounded p-3" required>
                    <p id="username-error" class="text-sm text-red-500 hidden mt-1"></p>
                </div>
                <div class="mb-4">
                    <label class="block font-medium mb-1">비밀번호</label>
                    <input type="password" id="whm_password" name="whm_password" class="w-full border rounded p-3" required>
                    <p id="password-error" class="text-sm text-red-500 hidden mt-1"></p>
                </div>
                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="goToStep(2)" class="px-6 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">이전</button>
                    <button type="button" onclick="goToStep(4)" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">다음</button>
                </div>
            </div>

            {{-- Step 4: 결제서 확인 --}}
            <div id="step-4" class="hidden">
                @include('components.upgrade-progress-5', ['step' => 4])

<div class="bg-white border border-gray-200 rounded-lg shadow p-6 max-w-2xl mx-auto">
    <h4 class="text-lg font-bold mb-4">4단계: 결제서 확인</h4>
    <div class="space-y-3 text-sm text-gray-700">
        <div class="flex justify-between border-b pb-1">
            <span class="font-medium">플랜명</span>
            <span id="summary-plan-name" class="font-semibold text-right text-gray-900">-</span>
        </div>
        <div class="flex justify-between border-b pb-1">
            <span class="font-medium">도메인</span>
            <span class="font-semibold text-right text-gray-900"><span id="summary-username"></span>.cflow.dev</span>
        </div>
        <div class="flex justify-between border-b pb-1">
            <span class="font-medium">이용 기간</span>
            <span id="summary-period" class="font-semibold text-right text-gray-900">-</span>
        </div>
        <div class="flex justify-between border-b pb-1">
            <span class="font-medium">디스크 용량</span>
            <span id="summary-disk" class="font-semibold text-right text-gray-900">-</span>
        </div>
        <div class="flex justify-between border-b pb-1">
            <span class="font-medium">신청일</span>
            <span id="summary-start" class="font-semibold text-right text-gray-900">-</span>
        </div>
        <div class="flex justify-between border-b pb-1">
            <span class="font-medium">만료일</span>
            <span id="summary-end" class="font-semibold text-right text-gray-900">-</span>
        </div>
        <div class="flex justify-between text-base font-bold pt-3">
            <span>최종 결제 금액</span>
            <span id="summary-price" class="text-right text-blue-600">-</span>
        </div>
    </div>
</div>

                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="goToStep(3)" class="px-6 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">이전</button>
                    <button type="button" id="paymentButton" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">결제하기</button>
                </div>
            </div>
</div>
            {{-- Step 5: 결제 결과 --}}
            <div id="step-5" class="hidden text-center">
                @include('components.upgrade-progress-5', ['step' => 5])
                <h3 class="text-xl font-bold mb-6">5단계: 결제 완료</h3>
                <p class="text-green-600 text-lg font-semibold">🎉 결제가 성공적으로 완료되었습니다.<br>서비스가 곧 활성화됩니다.</p>
            </div>
        </form>
    </div>

<!-- Toss SDK -->
<script src="https://js.tosspayments.com/v1"></script>

<script>
    let finalPrice = 0;
    let currentStep = 1;
    const discounts = { 1: 0, 3: 0.02, 6: 0.04, 12: 0.1, 24: 0.2 };
    const tossPayments = TossPayments("{{ config('services.toss.client_key') }}");

    function goToStep(step) {
        for (let i = 1; i <= 5; i++) {
            document.getElementById('step-' + i)?.classList.add('hidden');
        }
        document.getElementById('step-' + step)?.classList.remove('hidden');
        currentStep = step;

        if (step === 4) updateSummary();
    }

    function updateSummary() {
        const selectedPlan = document.querySelector('input[name="plan_id"]:checked');
        const duration = document.querySelector('input[name="duration"]:checked')?.value;
        const username = document.getElementById('username').value;

        const price = parseInt(selectedPlan.dataset.price);
        const discountRate = discounts[duration] || 0;
        const total = Math.round(price * duration * (1 - discountRate));
        const now = new Date();
const durationMonths = parseInt(duration);
const expiry = new Date(now);
expiry.setMonth(now.getMonth() + durationMonths);
 finalPrice = total;


// 날짜 YYYY-MM-DD 포맷
function formatDate(date) {
    return date.toISOString().slice(0, 10);
}

document.getElementById('summary-start').innerText = formatDate(now);
document.getElementById('summary-end').innerText = formatDate(expiry);
document.getElementById('summary-disk').innerText = selectedPlan.closest('label').querySelector('div.text-sm')?.innerText.split('/')[1] || '-';



        document.getElementById('summary-plan-name').innerText = selectedPlan.dataset.name;
        document.getElementById('summary-username').innerText = username;
document.getElementById('summary-period').innerText = duration + ' 개월';
        document.getElementById('summary-price').innerText = total.toLocaleString();
        



     
    }

    document.querySelectorAll('input[name="duration"]').forEach(el => {
    el.addEventListener('change', () => {
        const selected = document.querySelector('input[name="plan_id"]:checked');
        if (!selected) return;

        const price = parseInt(selected.dataset.price);
        const months = parseInt(el.value);
        const discountRate = discounts[months];
        const discounted = Math.round(price * months * (1 - discountRate));

        document.getElementById('price-summary').innerText =
            `할인 적용 총 금액: ${discounted.toLocaleString()}원 (${discountRate * 100}% 할인)`;
        document.getElementById('price-summary').classList.remove('hidden');

        // ✅ 위약금 안내 추가
        if (discountRate > 0) {
            document.getElementById('early-cancel-warning').innerHTML =
                '※ 중도 해지 시 할인 반환 위약금이 발생 됩니다..<br>' +
                '<span class=\"text-xs text-gray-700\">일반적으로 위약금은 <strong>(할인 금액 ÷ 총 개월수 × 잔여 개월수)</strong>로 계산됩니다.</span>';
            document.getElementById('early-cancel-warning').classList.remove('hidden');
        } else {
            document.getElementById('early-cancel-warning').classList.add('hidden');
            document.getElementById('early-cancel-warning').innerHTML = '';
        }
    });
});



    document.getElementById('paymentButton').addEventListener('click', async function () {
        const selected = document.querySelector('input[name="plan_id"]:checked');
        const username = document.getElementById('username').value;
        const password = document.getElementById('whm_password').value;
        const duration = document.querySelector('input[name="duration"]:checked')?.value;
        const orderId = 'order_' + Date.now();

        if (!selected || !username || !password) {
            alert("모든 항목을 입력해주세요.");
            return;
        }

        const price = parseInt(selected.dataset.price);
        const name = selected.dataset.name;

        await tossPayments.requestPayment('카드', {
            amount: finalPrice,
            orderId: orderId,
            orderName: `${name} 플랜 (${duration}개월)`,
            successUrl: '{{ url("/checkout/confirm") }}' +
                '?plan_id=' + selected.value +
                '&username=' + encodeURIComponent(username) +
                '&password=' + encodeURIComponent(password) +
                '&order_id=' + orderId +
                '&period=' + duration,
            failUrl: '{{ url("/checkout/fail") }}'
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const usernameInput = document.getElementById('username');
        const usernameError = document.getElementById('username-error');
        const passwordInput = document.getElementById('whm_password');
        const passwordError = document.getElementById('password-error');
        const paymentButton = document.getElementById('paymentButton');

        let isUsernameValid = false;
        let isPasswordValid = false;

        usernameInput.addEventListener('blur', function () {
            const username = usernameInput.value.trim();
            if (username === '') {
                clearUsernameError();
                isUsernameValid = false;
                updateSubmitButton();
                return;
            }

            fetch('/check-whm-username', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ username: username })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    usernameInput.classList.add('border-red-500');
                    usernameError.textContent = '이미 사용 중인 아이디입니다.';
                    usernameError.classList.remove('hidden');
                    isUsernameValid = false;
                } else {
                    clearUsernameError();
                    isUsernameValid = true;
                }
                updateSubmitButton();
            })
            .catch(error => {
                console.error('WHM username check failed:', error);
            });
        });

        passwordInput.addEventListener('input', function () {
            const password = passwordInput.value;
            const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            if (!strongRegex.test(password)) {
                passwordInput.classList.add('border-red-500');
                passwordError.textContent = '대문자, 소문자, 숫자, 특수문자를 포함하여 8자 이상 입력하세요.';
                passwordError.classList.remove('hidden');
                isPasswordValid = false;
            } else {
                passwordInput.classList.remove('border-red-500');
                passwordError.textContent = '';
                passwordError.classList.add('hidden');
                isPasswordValid = true;
            }
            updateSubmitButton();
        });

        function clearUsernameError() {
            usernameInput.classList.remove('border-red-500');
            usernameError.textContent = '';
            usernameError.classList.add('hidden');
        }

        function updateSubmitButton() {
            paymentButton.disabled = !(isUsernameValid && isPasswordValid);
        }
    });

    goToStep(1);
</script>



</x-app-layout>
