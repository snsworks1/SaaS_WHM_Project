<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Z8Aj8el0OZ..." crossorigin="anonymous" referrerpolicy="no-referrer" />

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">서비스 신청</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto">
        <form id="multiStepForm" method="POST">
            @csrf

            {{-- Step 1: 플랜 선택 --}}
<div id="step-1">
    @include('components.upgrade-progress-5', ['step' => 1])
    <h3 class="text-xl font-bold mb-6 text-center">1단계: 플랜 선택</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        @foreach ($plans as $plan)
            <label class="cursor-pointer block">
                <input type="radio" name="plan_id" value="{{ $plan->id }}"
       data-price="{{ $plan->price }}"
       data-name="{{ $plan->name }}"
       data-disk="{{ $plan->disk_size }}"
       class="peer hidden" required>

                <div class="p-6 border rounded-xl bg-white transition-all peer-checked:border-blue-600 peer-checked:ring-2 peer-checked:ring-blue-200 hover:shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-xl font-bold text-gray-900">{{ $plan->name }} 플랜</div>
                        <div class="text-right text-base text-blue-700 font-semibold">
                            ₩{{ number_format($plan->price) }} / 월
                        </div>
                    </div>

                    <ul class="mt-4 space-y-3 text-sm text-gray-700">
                        @if ($plan->name === 'basic')
                            <li class="flex items-center gap-2"><i class="fas fa-hdd text-blue-600"></i> 5GB SSD 저장공간</li>
                            <li class="flex items-center gap-2"><i class="fas fa-infinity text-blue-600"></i> 무제한 트래픽</li>
                            <li class="flex items-center gap-2"><i class="fab fa-wordpress text-blue-600"></i> WordPress 자동설치</li>
                            <li class="flex items-center gap-2"><i class="fas fa-palette text-blue-600"></i> 템플릿 기본 제공</li>
                            <li class="flex items-center gap-2"><i class="fas fa-shield-alt text-blue-600"></i> 보안 및 캐시 최적화 포함</li>
                            <li class="flex items-center gap-2"><i class="fas fa-shield-halved text-blue-600"></i> DDoS 고급 보호</li>
                            <li class="flex items-center gap-2"></li>
                            <li class="flex items-center gap-2"></li>
                            <li class="flex items-center gap-2"></li>
                            <li class="flex items-center gap-2"></li>
                            <li class="flex items-center gap-2"></li><li class="flex items-center gap-2"></li>
                            <li class="flex items-center gap-2"></li><li class="flex items-center gap-2"></li>
                        @elseif ($plan->name === 'pro')
                            <li class="flex items-center gap-2"><i class="fas fa-hdd text-purple-600"></i> 10GB SSD 저장공간</li>
                            <li class="flex items-center gap-2"><i class="fas fa-infinity text-purple-600"></i> 무제한 트래픽</li>
                            <li class="flex items-center gap-2"><i class="fas fa-star text-purple-600"></i> 프리미엄 템플릿 전체 제공</li>
                            <li class="flex items-center gap-2"><i class="fas fa-shield-alt text-purple-600"></i> 강화된 보안 및 캐시</li>

                            <li class="flex items-center gap-2"><i class="fas fa-shield-halved text-purple-600"></i> DDoS 고급 보호</li>
<li class="flex items-center gap-2"><i class="fas fa-rocket text-purple-600"></i> 고속 캐시 및 성능 최적화</li>
<li class="flex items-center gap-2"><i class="fas fa-cloud-upload-alt text-purple-600"></i> 주 1회 자동 백업</li>
<li class="flex items-center gap-2"><i class="fas fa-wrench text-purple-600"></i> SFTP 지원</li>
<li class="flex items-center gap-2"><i class="fas fa-robot text-purple-600"></i> AI 이상 트래픽 탐지</li>
                        @endif
                    </ul>
                </div>
            </label>
        @endforeach
    </div>

    <div class="mt-6 text-center text-sm text-gray-500">
<button type="button" onclick="document.getElementById('refundModal').showModal()" class="underline hover:text-blue-600">
        💡 환불 정책 보기
    </button>
</div>
    <div class="mt-8 text-right">
                    <button type="button" onclick="validateStep1()" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">다음</button>

    </div>
    
</div>






            {{-- Step 2: 요금 확인 --}}
            <div id="step-2" class="hidden">
                @include('components.upgrade-progress-5', ['step' => 2])
                <h3 class="text-xl font-bold mb-6">2단계: 결제 기간 선택</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach ([1 => '1개월', 3 => '3개월 (2%)', 6 => '6개월 (4%)', 12 => '1년 (10%)'] as $key => $label)
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
<div id="virtual-account-warning" class="text-sm text-orange-600 mt-3 hidden">
    ※ 6개월 이상 결제 시 가상계좌 결제는 불가 합니다.
</div>
<div id="early-cancel-warning" class="text-sm text-red-500 mt-2 hidden leading-snug">
    <!-- 할인 시 표시 -->
</div>


<div class="mt-6 flex justify-between">
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
                    <input type="text" id="username" name="whm_username" class="w-full border rounded p-3" >
                    <p id="username-error" class="text-sm text-red-500 hidden mt-1"></p>
                </div>
                <div class="mb-4">
                    <label class="block font-medium mb-1">비밀번호</label>
                    <input type="password" id="whm_password" name="whm_password" class="w-full border rounded p-3" >
                    <p id="password-error" class="text-sm text-red-500 hidden mt-1"></p>
                </div>
                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="goToStep(2)" class="px-6 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">이전</button>
                    <button type="button" onclick="handleStep2Next()" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">다음</button>
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
            <span class="font-semibold text-right text-gray-900"><span id="summary-username"></span>.hostyle.me</span>
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



    <dialog id="refundModal" class="rounded-xl max-w-xl w-full shadow-lg backdrop:bg-black/50 z-50">
    <div class="p-6 bg-white rounded-xl">
        <h2 class="text-lg font-bold mb-4">환불 정책 안내</h2>
        <div class="text-sm text-gray-700 leading-relaxed space-y-2">
            <p>결제일 기준 <strong>14일 이내</strong>에는 사용일수만큼 일할 계산되어 <strong>남은 기간에 대해 환불</strong>이 가능합니다.</p>
            <p>
                <strong>환불 가능 사유:</strong><br>
                - 회사의 귀책 사유로 인한 결제 오류<br>
                - 회사의 귀책 사유로 인한 서비스 중단<br>
                - 단순 변심에 의한 환불 요청 (단, 14일 이내)
            </p>
            <p>
                <strong>환불 제한 사항:</strong><br>
                - <strong>14일 이후</strong> 환불 불가 (월 단위 정산 기준)<br>
                - <strong>할인 결제 시</strong>: 위약금 발생 가능<br>
                &nbsp;&nbsp;→ 계산식: <code>할인금액 ÷ 총 개월수 × 잔여 개월수</code>
            </p>
            <p class="text-xs text-gray-500">
                본 환불 정책은 전자상거래법 및 소비자보호법을 준수합니다.
            </p>
        </div>
        <div class="mt-6 text-end">
            <button onclick="document.getElementById('refundModal').close()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                닫기
            </button>
        </div>
    </div>
</dialog>

<div id="plan-alert-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl shadow-lg max-w-sm w-full text-center">
        <div class="text-red-500 text-4xl mb-3">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <h2 class="text-lg font-bold mb-2 text-gray-800">플랜을 선택해주세요</h2>
        <p class="text-sm text-gray-600 mb-4">이용하실 플랜을 선택해야 다음 단계로 진행할 수 있습니다.</p>
        <button onclick="hidePlanAlert()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            닫기
        </button>
    </div>
</div>

<!-- 입력 오류 모달 -->
<div id="username-warning-modal"
     class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
  <div class="bg-white text-center rounded-xl p-6 max-w-sm w-full shadow-xl">
    <div class="text-red-500 text-3xl mb-2">
      <i class="fas fa-exclamation-circle"></i>
    </div>
    <h2 class="font-bold text-lg text-gray-800 mb-2">WHM 아이디를 입력해주세요</h2>
    <p class="text-sm text-gray-600 mb-4">또는 이메일 형식은 사용할 수 없습니다.</p>
    <button onclick="hideUsernameModal()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">닫기</button>
  </div>
</div>

<!-- 패스워드 미입력 모달 -->
<div id="password-warning-modal"
     class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
  <div class="bg-white text-center rounded-xl p-6 max-w-sm w-full shadow-xl">
    <div class="text-red-500 text-3xl mb-2">
      <i class="fas fa-exclamation-circle"></i>
    </div>
    <h2 class="font-bold text-lg text-gray-800 mb-2">비밀번호를 확인 해주세요</h2>
    <p class="text-sm text-gray-600 mb-4">  대문자, 소문자, 숫자, 특수문자를 포함하여 8자 이상이어야 합니다.</p>
    <button onclick="hidePasswordModal()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
      닫기
    </button>
  </div>
</div>




<!-- Toss SDK -->
<script src="https://js.tosspayments.com/v1"></script>

<script>
    let finalPrice = 0;
    let currentStep = 1;
    const discounts = { 1: 0, 3: 0.02, 6: 0.04, 12: 0.1 };
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
document.getElementById('summary-disk').innerText =
    selectedPlan.dataset.disk + 'GB';


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

        // 위약금 안내 표시
        if (discountRate > 0) {
            document.getElementById('early-cancel-warning').innerHTML =
                '※ 중도 해지 시 할인 반환 위약금이 발생 됩니다..<br>' +
                '<span class="text-xs text-gray-700">일반적으로 위약금은 <strong>(할인 금액 ÷ 총 개월수 × 잔여 개월수)</strong>로 계산됩니다.</span>';
            document.getElementById('early-cancel-warning').classList.remove('hidden');
        } else {
            document.getElementById('early-cancel-warning').classList.add('hidden');
            document.getElementById('early-cancel-warning').innerHTML = '';
        }

        // ✅ 가상계좌 결제 불가 안내
        const warningBox = document.getElementById('virtual-account-warning');
        if (months >= 6) {
            warningBox.classList.remove('hidden');
        } else {
            warningBox.classList.add('hidden');
        }
    });
});




    document.addEventListener('DOMContentLoaded', function () {
    const paymentButton = document.getElementById('paymentButton');
    if (!paymentButton) return;

    paymentButton.addEventListener('click', async function () {
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

        try {
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
        } catch (error) {
            console.error("❌ Toss 결제 실패:", error);
            showPaymentError();
        }
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


<script>
  function showPaymentError() {
      const alertBox = document.getElementById('payment-error-alert');
      if (alertBox) {
          alertBox.classList.remove('hidden');
      }
  }

  function hidePaymentError() {
      const alertBox = document.getElementById('payment-error-alert');
      if (alertBox) {
          alertBox.classList.add('hidden');
      }
  }
</script>

<script>
function validateStep1() {
    const selectedPlan = document.querySelector('input[name="plan_id"]:checked');
    if (!selectedPlan) {
        showPlanAlert();
        return;
    }

    goToStep(2);
}

function showPlanAlert() {
    const modal = document.getElementById('plan-alert-modal');
    if (modal) modal.classList.remove('hidden');
}

function hidePlanAlert() {
    const modal = document.getElementById('plan-alert-modal');
    if (modal) modal.classList.add('hidden');
}


function handleStep2Next() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('whm_password').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    // 1. WHM 아이디: 비어있거나 이메일 형식이면 안 됨
    if (!username || emailRegex.test(username)) {
        showUsernameModal(); // "아이디를 입력해주세요 또는 이메일 형식은 사용할 수 없습니다"
        return;
    }

    // 2. 비밀번호: 비어있거나 조건 미충족 시 실패
    if (!password || !passwordRegex.test(password)) {
        showPasswordModal(); // "비밀번호는 대문자, 소문자, 숫자, 특수문자를 포함하여 8자 이상이어야 합니다"
        return;
    }

    // 모두 통과 → 다음 단계
    goToStep(4);
}

function showUsernameModal() {
    const modal = document.getElementById('username-warning-modal');
    if (modal) modal.classList.remove('hidden');
}

function hideUsernameModal() {
    const modal = document.getElementById('username-warning-modal');
    if (modal) modal.classList.add('hidden');
}

function showPasswordModal() {
    document.getElementById('password-warning-modal')?.classList.remove('hidden');
}

function hidePasswordModal() {
    document.getElementById('password-warning-modal')?.classList.add('hidden');
}
</script>


<div id="payment-error-alert" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl shadow-lg max-w-sm w-full text-center">
        <div class="text-red-500 text-4xl mb-3">
            <i class="fas fa-times-circle"></i>
        </div>
        <h2 class="text-lg font-bold mb-2 text-gray-800">결제가 취소되었습니다</h2>
        <p class="text-sm text-gray-600 mb-4">사용자가 결제를 중단하거나 창을 닫았습니다.</p>
        <button onclick="hidePaymentError()" class="mt-2 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
            닫기
        </button>
    </div>
</div>

</x-app-layout>
