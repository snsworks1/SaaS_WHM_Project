<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />
  

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('이름') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('E-mail') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
    <label for="phone" class="block font-medium text-sm text-gray-700">연락처</label>
    <input id="phone"
           class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
           type="text"
           name="phone"
           value="{{ old('phone') }}"
           required
           pattern="^01[0-9]{8,9}$"
           title="휴대폰 번호는 01012345678 형식으로 입력해주세요." />
</div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('패스워드') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('패스워드 재확인') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <!-- 개인정보 동의 -->
            <div class="mt-6 text-sm text-gray-700">
                <label class="flex items-start space-x-2">
                    <input type="checkbox" id="agree_privacy" name="agree_privacy" class="mt-1" required>
                    <span>
                        [필수] 개인정보 수집 및 이용 동의에 동의합니다.
                        <button type="button" onclick="document.getElementById('privacy-modal').classList.remove('hidden')" class="text-indigo-600 underline ml-1">보기</button>
                    </span>
                </label>
            </div>

            <div class="mt-4 text-sm text-gray-700">
    <label class="flex items-start space-x-2">
        <input type="checkbox" id="marketing_opt_in" name="marketing_opt_in" class="mt-1">
        <span>[선택] 이벤트, 프로모션 등 광고성 정보 수신에 동의합니다.</span>
    </label>
</div>

            <!-- 모달 -->
            <div id="privacy-modal" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center hidden">
                <div class="bg-white w-full max-w-xl p-6 rounded shadow-lg relative text-sm">
                    <h2 class="text-lg font-bold mb-4">개인정보 수집 및 이용 동의</h2>
                    <div class="max-h-[60vh] overflow-y-auto space-y-3 text-gray-700">
                        <p><strong>수집 항목:</strong> 이름, 이메일, 연락처, 비밀번호</p>
                        <p><strong>수집 목적:</strong> 회원가입, 본인 확인, 서비스 제공, 고객 문의 응대 등</p>
                        <p><strong>보유 기간:</strong> 탈퇴 시까지 또는 관련 법령 보존 기간까지</p>

                        <p class="mt-4 font-medium">[개인정보 처리 위탁 안내]</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>수탁자:</strong> 토스페이먼츠(Toss Payments)</li>
                            <li><strong>위탁 업무:</strong> 결제 처리 및 정산</li>
                            <li><strong>보유 기간:</strong> 계약 종료 시 또는 법령 기준 보존</li>
                        </ul>

                        <p class="text-red-600 mt-3">※ 동의하지 않으면 회원가입이 불가합니다.</p>
                    </div>
                    <button onclick="document.getElementById('privacy-modal').classList.add('hidden')" class="absolute top-2 right-3 text-xl text-gray-400 hover:text-red-500">&times;</button>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('로그인 페이지 돌아가기') }}
                </a>

                <x-button class="ms-4">
                    {{ __('회원가입') }}
                </x-button>
            </div>
        </form>

                <!-- 스크립트: 동의 체크 여부로 버튼 활성화 -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const checkbox = document.getElementById('agree_privacy');
                const button = document.getElementById('registerBtn');

                checkbox.addEventListener('change', () => {
                    button.disabled = !checkbox.checked;
                });
            });
        </script>
    </x-authentication-card>
</x-guest-layout>
