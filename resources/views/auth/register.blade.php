<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />
  

        <form method="POST" action="{{ route('register') }}">
            @csrf

        <!-- 고객 유형 선택 -->
<div class="mt-6">
    <label class="block font-medium text-sm text-gray-700 mb-2">고객 유형</label>
    <div class="grid grid-cols-2 gap-4">
        <div onclick="selectCustomerType('personal')" id="card-personal"
            class="cursor-pointer border rounded p-4 text-center hover:border-indigo-500">
            🙋 개인 고객
        </div>
        <div onclick="selectCustomerType('business')" id="card-business"
            class="cursor-pointer border rounded p-4 text-center hover:border-indigo-500">
            🏢 사업자 고객
        </div>
    </div>
    <input type="hidden" name="customer_type" id="customer_type" value="personal">
</div>


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


<!-- 사업자 정보 필드 (초기에는 숨김) -->
<div id="business-fields" class="mt-6 hidden space-y-4">
    <!-- 구분 라벨 -->
    <div class="border-b border-gray-300 pb-2 mb-2">
        <h3 class="text-md font-semibold text-gray-800">📄 사업자 전용 입력 항목</h3>
        <p class="text-sm text-gray-500 mt-1">※ 사업자 고객만 입력해주세요.</p>
    </div>

    <div>
        <label class="block font-medium text-sm text-gray-700">상호</label>
        <input type="text" name="company_name" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
        <label class="block font-medium text-sm text-gray-700">사업자번호</label>
        <input type="text" name="business_number" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" placeholder="예: 123-45-67890">
    </div>
    
     <!-- 주소 검색 -->
<div>
    <label class="block font-medium text-sm text-gray-700">사업자 주소</label>
    <div class="flex gap-2 mb-2">
        <input type="text" id="address_base" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="주소 검색" readonly>
        <button type="button" onclick="execDaumPostcode()"
            class="px-3 py-2 text-sm bg-indigo-500 text-white rounded hover:bg-indigo-600">
            주소 찾기
        </button>
    </div>

    <input type="text" id="address_detail" class="w-full border-gray-300 rounded-md shadow-sm mb-2" placeholder="상세 주소 입력">
    
    <!-- 실제로 서버에 전송되는 주소 -->
    <input type="hidden" id="business_address" name="business_address">
</div>


    <div>
        <label class="block font-medium text-sm text-gray-700">업태</label>
        <input type="text" name="business_type" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
        <label class="block font-medium text-sm text-gray-700">종목</label>
        <input type="text" name="business_item" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
        <label class="block font-medium text-sm text-gray-700">계산서 발행 이메일</label>
        <input type="email" name="invoice_email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
    </div>
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

<x-button id="registerBtn" class="ms-4">
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

<script>
    function selectCustomerType(type) {
        document.getElementById('customer_type').value = type;

        const personalCard = document.getElementById('card-personal');
        const businessCard = document.getElementById('card-business');
        const businessFields = document.getElementById('business-fields');

        const requiredFields = [
            'company_name',
            'business_number',
            'business_address',
            'business_type',
            'business_item',
            'invoice_email'
        ];

        if (type === 'business') {
            businessFields.classList.remove('hidden');
            businessCard.classList.add('border-indigo-500', 'bg-indigo-50');
            personalCard.classList.remove('border-indigo-500', 'bg-indigo-50');

            // 필수값 추가
            requiredFields.forEach(id => {
                const el = document.getElementsByName(id)[0];
                if (el) el.setAttribute('required', 'required');
            });
        } else {
            businessFields.classList.add('hidden');
            personalCard.classList.add('border-indigo-500', 'bg-indigo-50');
            businessCard.classList.remove('border-indigo-500', 'bg-indigo-50');

            // 필수값 제거
            requiredFields.forEach(id => {
                const el = document.getElementsByName(id)[0];
                if (el) el.removeAttribute('required');
            });
        }
    }
</script>

<!-- Daum 주소 API 스크립트 -->
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>

<script>
    function execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function (data) {
                let fullAddr = data.address;
                if (data.addressType === 'R') {
                    if (data.bname !== '') fullAddr += ' ' + data.bname;
                    if (data.buildingName !== '') fullAddr += ' (' + data.buildingName + ')';
                }
                document.getElementById('address_base').value = fullAddr;
                updateFullBusinessAddress();
            }
        }).open();
    }

    // 상세 주소 입력 시에도 병합 처리
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('address_detail').addEventListener('input', updateFullBusinessAddress);
    });

    function updateFullBusinessAddress() {
        const base = document.getElementById('address_base').value || '';
        const detail = document.getElementById('address_detail').value || '';
        const full = base + (detail ? ' ' + detail : '');
        document.getElementById('business_address').value = full;
    }
</script>
    </x-authentication-card>
</x-guest-layout>
