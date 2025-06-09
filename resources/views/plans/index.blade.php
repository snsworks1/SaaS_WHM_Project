<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">플랜 선택</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto">
            <form method="POST" action="{{ route('plans.select') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($plans as $plan)
                        <label class="p-4 border rounded cursor-pointer flex items-center space-x-2">
                            <input type="radio" name="plan_id" value="{{ $plan->id }}" required>
                            <div>
                                <div class="font-semibold">{{ $plan->name }}</div>
                                <div class="text-sm text-gray-600">{{ number_format($plan->price) }}원 / {{ $plan->disk_size }}GB</div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <!-- 아이디 입력 -->
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

                <button id="submitBtn" type="submit"
                    class="px-4 py-2 rounded text-white transition-colors duration-200 disabled:bg-gray-400 bg-indigo-600 hover:bg-indigo-700"
                    disabled>
                    계정 생성
                </button>

            </form>

            @if($errors->has('whm_username'))
    <p class="text-red-500 text-sm">{{ $errors->first('whm_username') }}</p>
@endif
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const usernameInput = document.getElementById('username');
    const usernameError = document.getElementById('username-error');
    const passwordInput = document.getElementById('whm_password');
    const passwordError = document.getElementById('password-error');
    const submitBtn = document.getElementById('submitBtn');

    let isUsernameValid = false;
    let isPasswordValid = false;

    // WHM 아이디 중복검사
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

    // 비밀번호 실시간 강도 체크 (WHM 강도 정책 기반)
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
        submitBtn.disabled = !(isUsernameValid && isPasswordValid);
    }
});
</script>
