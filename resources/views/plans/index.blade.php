<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">플랜 선택</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto">
            <form id="planForm" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($plans as $plan)
                        <label class="p-4 border rounded cursor-pointer flex items-center space-x-2">
                            <input type="radio" name="plan_id" value="{{ $plan->id }}"
                                   data-price="{{ $plan->price }}" data-name="{{ $plan->name }}" required>
                            <div>
                                <div class="font-semibold">{{ $plan->name }}</div>
                                <div class="text-sm text-gray-600">{{ number_format($plan->price) }}원 / {{ $plan->disk_size }}GB</div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <!-- WHM 아이디 입력 -->
                <div class="mb-4">
                    <label>WHM 아이디 (도메인 앞부분)</label>
                    <input type="text" id="username" name="whm_username" class="w-full border rounded p-2" required>
                    <p id="username-error" class="mt-1 text-sm text-red-500 hidden"></p>
                    <small>입력한 값 → {아이디}.cflow.dev 생성</small>
                </div>

                <!-- 비밀번호 입력 -->
                <div class="mb-4">
                    <label>비밀번호</label>
                    <input type="password" id="whm_password" name="whm_password" class="w-full border rounded p-2" required>
                    <p id="password-error" class="mt-1 text-sm text-red-500 hidden"></p>
                </div>

                <!-- 결제 버튼 -->
                <button type="button" id="paymentButton"
                        class="mt-4 w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded disabled:bg-gray-400"
                        disabled>
                    결제하기
                </button>
            </form>

            @if($errors->has('whm_username'))
                <p class="text-red-500 text-sm">{{ $errors->first('whm_username') }}</p>
            @endif
        </div>
    </div>
</x-app-layout>

<!-- Toss SDK -->
<script src="https://js.tosspayments.com/v1"></script>
<script>
const tossPayments = TossPayments("{{ config('services.toss.client_key') }}");

document.getElementById('paymentButton').addEventListener('click', async function () {
    const selected = document.querySelector('input[name="plan_id"]:checked');
    const username = document.getElementById('username').value;
    const password = document.getElementById('whm_password').value;

    if (!selected || !username || !password) {
        alert("모든 항목을 입력해주세요.");
        return;
    }

    const orderId = 'order_' + Date.now();
    const price = selected.dataset.price;
    const name = selected.dataset.name;

    await tossPayments.requestPayment('카드', {
        amount: parseInt(price),
        orderId: orderId,
        orderName: name + " 플랜",
        successUrl: '{{ url("/checkout/confirm") }}' +
            '?plan_id=' + selected.value +
            '&username=' + encodeURIComponent(username) +
            '&password=' + encodeURIComponent(password) +
            '&order_id=' + orderId,
        failUrl: '{{ url("/checkout/fail") }}'
    });
});
</script>

<!-- 유효성 검사 JS -->
<script>
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
</script>
