
<!DOCTYPE html>
<html lang="ko" class="dark">
<head>
  <meta name="naver-site-verification" content="28b6a23100e916ab0caa77d70504b95b3e48cf5e" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
          <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/IconOnly_Transparent.png') }}">
          

  <title>Hostyle SaaS 기반 웹호스팅</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class'
    };
  </script>
  <style>
    body { font-family: 'Noto Sans KR', sans-serif; }
    .slider img { transition: opacity 1s ease-in-out; }
  </style>

</head>
<body class="bg-zinc-900 text-white">

<!-- Header -->
<!-- Header -->
<header class="bg-zinc-900 border-b border-zinc-800 sticky top-0 z-50">
  <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center px-4 py-3 md:px-6 md:py-4 gap-y-3">

    <!-- 로고 -->
    <div class="flex-shrink-0 w-full md:w-auto flex items-center justify-center md:justify-start">
      <img src="images/logo.png" alt="Hostyle Logo" class="w-40 h-auto mx-auto md:mx-0" />
    </div>

    <!-- 네비게이션 -->
    <nav class="flex flex-wrap justify-center md:justify-end items-center gap-x-4 gap-y-2 w-full md:w-auto text-sm">

      <a href="https://snsworks.co.kr" target="_blank"
         class="text-gray-400 hover:text-blue-400 transition px-2 py-1 md:px-3 md:py-2 rounded-md font-medium">
        S&SWorks 홈페이지
      </a>
      <a href="#why" class="text-gray-200 hover:text-blue-400 transition px-2 py-1">특장점</a>
      <a href="#infra" class="text-gray-200 hover:text-blue-400 transition px-2 py-1">인프라</a>
      <a href="#images" class="text-gray-200 hover:text-blue-400 transition px-2 py-1">기능</a>
      <a href="#plans" class="text-gray-200 hover:text-blue-400 transition px-2 py-1">요금제</a>

      <a href="https://s-organization-887.gitbook.io/hostyle-web/" target="_blank"
         class="text-gray-400 hover:text-blue-400 transition px-2 py-1 md:px-3 md:py-2 rounded-md font-medium">
        📘 가이드북
      </a>

      @auth
        <a href="{{ route('dashboard') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium">
          Dashboard 바로가기
        </a>
      @else
        <a href="{{ route('login') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium">
          로그인
        </a>
      @endauth

    </nav>
  </div>
</header>


<!-- Hero -->
<section class="px-6 py-24 max-w-7xl mx-auto">
  <div class="grid md:grid-cols-2 items-center gap-10">
    
    <!-- 텍스트 영역 -->
    <div class="text-center md:text-left space-y-6">
      <h1 class="text-4xl md:text-5xl font-extrabold leading-snug">
        <span class="text-blue-500">Hostyle</span>
        <br class="hidden md:block">
        <span class="mt-3 block">강력하고 간편한 웹호스팅</span>
      </h1>
      <p class="text-zinc-300 text-lg leading-relaxed">
        회원가입 → 플랜 선택 → 결제 → 자동 구축<br>
        복잡한 설치 없이 <strong>3분 만에 내 웹사이트 오픈</strong>
      </p>
      <a href="{{ route('login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-base transition-all">
        지금 시작하기
      </a>
    </div>

    <!-- 이미지 영역 -->
    <div class="flex justify-center md:justify-end">
      <img src="{{ asset('images/auto-deply1.jpg') }}" alt="자동 구축 예시" class="w-[600px] max-w-full rounded-xl shadow-2xl">
    </div>

  </div>
</section>



<!-- ✅ 더 넓은 슬라이더: 브라우저 전체 폭 사용 -->
<section id="why" class="bg-zinc-950 py-16 px-4">
  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-10 items-center">

    <!-- 좌측 텍스트: 5/12 -->
     
    <div class="md:col-span-4 space-y-5">
      <h2 class="text-3xl md:text-5xl font-bold text-white">왜 Hostyle인가?</h2>
      <ul class="space-y-3 text-sm text-zinc-300 leading-relaxed">
        <li>✅ 병원/식당/학원/커뮤니티/통신사 등등 홍보페이지 최적화</li>
        <li>✅ 클릭 한 번으로 WordPress 설치 완료</li>
        <li>✅ WordPress 템플릿 무료 제공</li>
        <li>✅ 사용자 맞춤형 가이드북 제공</li>
        <li>✅ 유연한 플랜별 월 요금제</li>
        <li>✅ 초보자도 쉽게 사용하는 cPanel 인터페이스</li>
        <li>✅ 결제 후 3분 이내 자동 구축 및 접속 가능</li>
      </ul>
    </div>

    <!-- 우측 슬라이드: 7/12 -->
     <!-- 슬라이더 영역: 이미지 권장 사이즈 1200x750 이상 -->

    <div class="md:col-span-8">
      <div class="slider relative w-full h-[260px] sm:h-[360px] md:h-[480px] lg:h-[560px] bg-zinc-800 rounded-xl overflow-hidden shadow-lg">
        <!-- 추천 이미지 사이즈: 1500x900px 이상 -->
        <img src="images/img1.PNG" alt="슬라이드1"
             class="absolute inset-0 w-full h-full object-cover opacity-100 transition-opacity duration-700">
        <img src="images/img2.png" alt="슬라이드2"
             class="absolute inset-0 w-full h-full object-cover opacity-0">
        <img src="images/img4.PNG" alt="슬라이드3"
             class="absolute inset-0 w-full h-full object-cover opacity-0">
      </div>
    </div>

  </div>
</section>




<script>
// Simple slider logic
const slides = document.querySelectorAll('.slider img');
let current = 0;
setInterval(() => {
  slides[current].style.opacity = 0;
  current = (current + 1) % slides.length;
  slides[current].style.opacity = 1;
}, 3000);
</script>

<!-- Infra -->
<!-- Infra -->
<section id="infra" class="py-20 px-4 max-w-6xl mx-auto">
  <div class="grid md:grid-cols-2 gap-10 items-center">
    
    <!-- 좌측 이미지 -->
    <div class="flex justify-center">
      <img src="{{ asset('images/infra.png') }}" alt="서버 인프라 이미지" class="max-w-full h-auto rounded-lg shadow-lg">
    </div>
    
    <!-- 우측 텍스트 -->
    <div>
      <h2 class="text-5xl font-bold mb-6 text-white">고성능 서버 기반 인프라</h2>
      <ul class="list-disc list-inside text-sm text-zinc-300 space-y-2">
        <li>SSD 기반 고속 저장소 + 최신 CPU</li>
        <li>보안 정책 + 예약 백업 자동화</li>
        <li>Cloudflare 연동: DDoS 방어 및 속도 최적화</li>
        <li>글로벌 DNS 연결: 빠른 응답성 보장</li>
        <li>해외 웹서버 / 도메인 익명성</li>
      </ul>
    </div>

  </div>
</section>


<!-- Detailed Screenshots -->
<section id="images" class="bg-zinc-950 py-20 px-6">
  <div class="max-w-6xl mx-auto space-y-16">
    <!-- Dashboard -->
    <div class="grid md:grid-cols-2 gap-8 items-center">
      <img src="images/img5.PNG" alt="대시보드 이미지" class="rounded-lg shadow-xl h-[300px]" >
      <div>
        <h3 class="text-xl font-semibold mb-2">사용자 대시보드</h3>
        <p class="text-zinc-300 text-sm">서비스 상태, 도메인, 남은 기간 등 모든 정보를 한눈에 확인하는 직관적 대시보드.</p>
      </div>
    </div>
    <!-- Payment Methods -->
    <div class="grid md:grid-cols-2 gap-8 items-center">
      <div>
        <h3 class="text-xl font-semibold mb-2">다양한 결제수단 지원</h3>
        <p class="text-zinc-300 text-sm">카카오페이, 토스, 네이버페이, 신용카드 등 다양한 옵션으로 간편 결제.</p>
      </div>
      <img src="images/pay.PNG" alt="결제수단 이미지" class="rounded-lg shadow-xl">
    </div>
    <!-- cPanel -->
    <div class="grid md:grid-cols-2 gap-4 items-center">
      <img src="images/cpanel.PNG" alt="cpanel 관리페이지" class="rounded-lg shadow-xl w-full h-[400px] object-cover transform group-hover:scale-105 transition duration-300">
      
      <div>
        <h3 class="text-xl font-semibold mb-2">cPanel 서버 관리</h3>
        <p class="text-zinc-300 text-sm">DNS, MySQL, phpMyAdmin 지원 등 초보자도 쉽게 사용하는 cPanel 관리 UI.</p>
      </div>
    </div>
  </div>
</section>

<!-- Card Example -->
<section class="py-20 px-6 max-w-7xl mx-auto ">
  <h2 class="text-2xl font-bold mb-10 text-center">고객 제공 도구 </h2>
  <div class="grid md:grid-cols-4 gap-6">
    <div class="bg-zinc-800 p-6 rounded-xl">
      <h3 class="font-semibold mb-2">서비스 설명서</h3>
      <p class="text-sm text-zinc-300">웹서버 셋팅&운영 설명서 제공</p>
    </div>
    <div class="bg-zinc-800 p-6 rounded-xl">
      <h3 class="font-semibold mb-2">데이터 베이스 제공</h3>
      <p class="text-sm text-zinc-300">Mysql 및 PhpMyAdmin 제공</p>
    </div>
    <div class="bg-zinc-800 p-6 rounded-xl">
      <h3 class="font-semibold mb-2">모니터링</h3>
      <p class="text-sm text-zinc-300">사용중인 웹서버의 메인서버 리얼타임 제공</p>
    </div>
    <div class="bg-zinc-800 p-6 rounded-xl">
      <h3 class="font-semibold mb-2">공유 도메인 DDOS 방어</h3>
      <p class="text-sm text-zinc-300">자체 네트워크 L3/4 + Cloudflare L7  방어</p>
    </div>
  </div>
</section>

<!-- Plans -->
<section id="plans" class="py-20 px-6 bg-zinc-950">
  <div class="max-w-7xl mx-auto text-center">
    <h2 class="text-3xl font-bold mb-10">요금제 플랜</h2>

 <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
  <!-- Basic 플랜 -->
  <div class="bg-white text-black p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-xl hover:scale-[1.01] transition-all duration-300">
    <h3 class="text-xl font-bold mb-2">Basic 플랜</h3>
    <p class="text-blue-600 font-semibold mb-4">₩29,000 / 월</p>
    <ul class="space-y-3 text-sm">
      <li class="flex items-center gap-2"><i class="fas fa-hdd text-blue-600"></i> 5GB SSD 저장공간</li>
      <li class="flex items-center gap-2"><i class="fas fa-tachograph-digital text-blue-600"></i> 월 30GB 트래픽</li>
      <li class="flex items-center gap-2"><i class="fab fa-wordpress text-blue-600"></i> WordPress 자동설치</li>
      <li class="flex items-center gap-2"><i class="fas fa-database text-blue-600"></i> DB 1개 생성 가능</li>
      <li class="flex items-center gap-2"><i class="fas fa-globe text-blue-600"></i> 도메인 1개 연결 가능</li>
      <li class="flex items-center gap-2"><i class="fas fa-palette text-blue-600"></i> 템플릿 기본 제공</li>
      <li class="flex items-center gap-2"><i class="fas fa-shield-alt text-blue-600"></i> 보안 및 캐시 최적화</li>
      <li class="flex items-center gap-2"><i class="fas fa-shield-halved text-blue-600"></i> DDoS 고급 보호</li>
    </ul>
  </div>

  <!-- Pro 플랜 -->
  <div class="bg-white text-black p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-xl hover:scale-[1.01] transition-all duration-300">
    <h3 class="text-xl font-bold mb-2">Pro 플랜</h3>
    <p class="text-purple-600 font-semibold mb-4">₩59,000 / 월</p>
    <ul class="space-y-3 text-sm">
      <li class="flex items-center gap-2"><i class="fas fa-hdd text-purple-600"></i> 20GB SSD 저장공간</li>
      <li class="flex items-center gap-2"><i class="fas fa-infinity text-purple-600"></i> 무제한 트래픽</li>
      <li class="flex items-center gap-2"><i class="fab fa-wordpress text-purple-600"></i> WordPress 자동설치</li>
      <li class="flex items-center gap-2"><i class="fas fa-database text-purple-600"></i> DB 3개 생성 가능</li>
      <li class="flex items-center gap-2"><i class="fas fa-globe text-purple-600"></i> 도메인 3개 연결 가능</li>
      <li class="flex items-center gap-2"><i class="fas fa-star text-purple-600"></i> 템플릿 기본 제공</li>
      <li class="flex items-center gap-2"><i class="fas fa-shield-alt text-purple-600"></i> 강화된 보안 및 캐시</li>
      <li class="flex items-center gap-2"><i class="fas fa-shield-halved text-purple-600"></i> DDoS 고급 보호</li>

    </ul>
  </div>
</div>


    <div class="mt-6">
  <a href="/login" class="inline-block px-2 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">플랜 선택하기</a>
</div>
  </div>
</section>

<!-- FAQ Section -->
 
<!-- FAQ Section with Accordion -->
<section id="faq" class="py-20 px-6 max-w-6xl mx-auto">
  <h2 class="text-3xl font-bold mb-10 text-center">자주 묻는 질문 (FAQ)</h2>

  <div class="space-y-4">
    <!-- 각 FAQ 항목 -->
    <div class="bg-zinc-800 rounded-lg shadow overflow-hidden">
      <button class="w-full text-left px-6 py-4 focus:outline-none flex justify-between items-center faq-toggle">
        <span class="text-white font-medium">Q. 결제 후 바로 사용 가능한가요?</span>
        <svg class="w-5 h-5 text-white transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="faq-content px-6 pb-4 text-sm text-zinc-300 hidden">
        네. 결제가 완료되면 자동으로 cPanel 계정이 즉시 생성되며, 이메일로 접속 정보를 안내드립니다. 평균 3분 이내 구축됩니다.
      </div>
    </div>

    <div class="bg-zinc-800 rounded-lg shadow overflow-hidden">
      <button class="w-full text-left px-6 py-4 focus:outline-none flex justify-between items-center faq-toggle">
        <span class="text-white font-medium">Q. 템플릿은 어떤 방식으로 제공되나요?</span>
        <svg class="w-5 h-5 text-white transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="faq-content px-6 pb-4 text-sm text-zinc-300 hidden">
        템플릿은 WordPress에 자동 설치되는 형태로 제공되며, 로그인 후 테마 설정에서 활성화할 수 있습니다.
      </div>
    </div>

    <div class="bg-zinc-800 rounded-lg shadow overflow-hidden">
      <button class="w-full text-left px-6 py-4 focus:outline-none flex justify-between items-center faq-toggle">
        <span class="text-white font-medium">Q. 도메인이 없어도 시작할 수 있나요?</span>
        <svg class="w-5 h-5 text-white transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="faq-content px-6 pb-4 text-sm text-zinc-300 hidden">
        네. 기본 제공되는 공유 도메인으로 먼저 시작할 수 있으며, 이후 도메인을 연결할 수 있습니다.
      </div>
    </div>

    <div class="bg-zinc-800 rounded-lg shadow overflow-hidden">
      <button class="w-full text-left px-6 py-4 focus:outline-none flex justify-between items-center faq-toggle">
        <span class="text-white font-medium">Q. cPanel 로그인은 어떻게 하나요?</span>
        <svg class="w-5 h-5 text-white transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="faq-content px-6 pb-4 text-sm text-zinc-300 hidden">
        서비스 개설 후 대시보드에서 <strong>1-click 로그인</strong> 기능으로 접속할 수 있습니다.
      </div>
    </div>
        <div class="bg-zinc-800 rounded-lg shadow overflow-hidden">
      <button class="w-full text-left px-6 py-4 focus:outline-none flex justify-between items-center faq-toggle">
        <span class="text-white font-medium">Q. 트래픽/용량 제한은 어떻게 되나요?</span>
        <svg class="w-5 h-5 text-white transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="faq-content px-6 pb-4 text-sm text-zinc-300 hidden">
        BASIC 플랜은 트래픽 월 30GB / PRO 플랜은 무제한을 으로 제공하며, SSD 저장 용량은 각 플랜 기준에 따라 적용됩니다. 디스크 사용량은 대시보드에서 실시간 확인 가능합니다.
      </div>
    </div>
        <div class="bg-zinc-800 rounded-lg shadow overflow-hidden">
      <button class="w-full text-left px-6 py-4 focus:outline-none flex justify-between items-center faq-toggle">
        <span class="text-white font-medium">Q. 환불 정책은 어떻게 되나요?</span>
        <svg class="w-5 h-5 text-white transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="faq-content px-6 pb-4 text-sm text-zinc-300 hidden">
        결제일 기준 14일 이내 일할 계산하여 남은 일수 만큼 가능합니다. 이후에는 월 단위 정산 기준으로 환불이 제한되며, 결제시 할인 되었을 경우 위약금이 발생됩니다.
      </div>
    </div>


    <!-- 필요한 만큼 복사해서 추가 -->
  </div>
</section>


<!-- Footer -->
<footer class="bg-zinc-950 py-10 text-center text-sm text-zinc-400">
  ⓒ 2018 S&S Works. All rights reserved.
      · <button onclick="document.getElementById('refundModal').showModal()" class="underline text-blue-400 hover:text-blue-600">환불 정책</button>
· <button onclick="document.getElementById('termsModal').showModal()" class="underline text-blue-400 hover:text-blue-600">
    서비스 이용약관
</button>    · <button onclick="document.getElementById('privacyModal').showModal()" class="underline text-blue-400 hover:text-blue-600">
    개인정보 처리방침
</button>

    <div class="max-w-6xl mx-auto px-4 text-center space-y-2">
        <p>
            <span class="inline-block">상호명: 에스앤에스웍스</span> |
            <span class="inline-block">서비스명: Hostyle</span> |
            <span class="inline-block">대표자: 김대현</span> |
            <span class="inline-block">사업자등록번호: 522-71-00290</span> |
            <span class="inline-block">통신판매업: 2025-서울강남-03345</span>

       
        </p>
        <p>
            <span class="inline-block">사업장 주소: 서울특별시 강남구 강남대로 112길 47, 2층 369A호</span> |
            <span class="inline-block">홈페이지: https://www.snsworks.co.kr</span> |
            <span class="inline-block">고객센터 운영시간: 영업일 10:00 ~ 18:00</span>
        </p>
    </div>

</footer>

<dialog id="refundModal"class="rounded-xl max-w-[90vw] sm:max-w-xl w-full shadow-lg backdrop:bg-black/50 z-50">
  <div class="p-6 bg-white rounded-xl max-h-[80vh] overflow-y-auto">
        <h2 class="text-lg font-bold mb-4">환불 정책 안내</h2>
        <p class="text-sm text-gray-700 leading-relaxed space-y-2">
            결제일 기준 <strong>14일 이내</strong>에는 사용일수만큼 일할 계산되어 <strong>남은 기간에 대해 환불</strong>이 가능합니다.<br><br>

            <strong>서비스 결제를 체결한 사용자는 아래와 같이 결제에 대한 환불을 요구할 수 있습니다.</strong><br>
            - 회사의 귀책 사유료 인한 결제 오류가 발생된 경우<br>
            - 회사의 귀책 사유로 인한 서비스가 중단된 경우<br>
            - 단순 변심으로 인한 환불<br>
            <strong>단, 다음의 경우는 환불이 제한됩니다:</strong><br>
            - 14일 이후: 환불 불가 (월 단위 정산)<br>
            - 할인 결제 시: 위약금 발생 가능 (할인 금액 ÷ 총 개월수 × 잔여 개월수)<br>

            본 환불 정책은 전자상거래법 및 소비자보호법을 준수합니다.
        </p>
        <div class="mt-6 text-end">
            <button onclick="document.getElementById('refundModal').close()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                닫기
            </button>
        </div>
    </div>
    
</dialog>



    <dialog id="privacyModal" class="rounded-xl max-w-[90vw] sm:max-w-xl w-full shadow-lg backdrop:bg-black/50 z-50 open:overflow-hidden">
  <div class="bg-white rounded-xl w-full max-h-[80vh] overflow-y-auto p-6">
    <h2 class="text-lg font-bold mb-4 text-gray-800">개인정보 처리방침</h2>

    <div class="text-sm text-gray-700 leading-relaxed space-y-4">

      <p>에스앤에스웍스(이하 "회사")은 개인정보 보호법에 따라 이용자의 개인정보를 보호하고 권익을 보호하기 위해 다음과 같은 개인정보 처리방침을 수립·공개합니다.</p>

      <p><strong>1. 수집하는 개인정보 항목</strong><br>
        - 필수항목: 이메일, 비밀번호, 이름, 전화번호, 서비스 이용기록, 결제정보(가상계좌/카드 정보) 사업장주소, 프로필 정보, 회사명 , 사업자등록번호<br>
        
      </p>

      <p><strong>2. 개인정보 수집 방법</strong><br>
        - 회원가입 시 사용자가 직접 입력<br>
        - 서비스 이용 중 자동 수집(Cookie, 접속 IP 등)
      </p>

      <p><strong>3. 개인정보 이용 목적</strong><br>
        - 회원가입 및 본인 확인<br>
        - 서비스 제공 및 계약 이행<br>
        - 고객 상담, 민원 처리<br>
        - 요금 정산 및 결제<br>
        - 서비스 개선 및 마케팅 활용(동의 시)
      </p>

      <p><strong>4. 개인정보 보유 및 이용기간</strong><br>
        - 회원 탈퇴 시 즉시 삭제<br>
        - 관계법령에 따라 일정기간 보존되는 경우:<br>
          · 계약 또는 청약철회 기록: 5년<br>
          · 대금결제 및 재화 공급 기록: 5년<br>
          · 소비자 불만 또는 분쟁처리 기록: 3년
      </p>

      <p><strong>5. 개인정보 제3자 제공</strong><br>
        회사는 원칙적으로 이용자의 개인정보를 외부에 제공하지 않습니다. 단, 다음의 경우에는 예외로 합니다:<br>
        - 이용자의 사전 동의를 받은 경우<br>
        - 법령에 의거하거나 수사기관의 요청이 있는 경우
      </p>

      <p><strong>6. 개인정보 처리 위탁</strong><br>
        회사는 서비스 제공을 위해 아래와 같이 개인정보 처리를 위탁할 수 있습니다:<br>
        - 결제처리: 토스페이먼츠㈜<br>
        - 이메일 발송: Amazon SES (또는 Mailgun 등)
      </p>

      <p><strong>7. 정보주체의 권리와 행사 방법</strong><br>
        - 개인정보 열람, 정정, 삭제, 처리정지 요청 가능<br>
        - 회원정보 수정 또는 탈퇴를 통해 직접 처리 가능<br>
        - 또는 개인정보 보호책임자에게 요청 가능
      </p>

      <p><strong>8. 개인정보 보호책임자</strong><br>
        · 이름: 김대현<br>
        · 이메일: support@snsworks.co.kr<br>
        · 연락처: 010-5914-3150
      </p>

      <p><strong>9. 쿠키의 설치/운영 및 거부</strong><br>
        - 웹사이트는 맞춤형 서비스 제공을 위해 쿠키(cookie)를 사용합니다.<br>
        - 사용자는 브라우저 설정을 통해 쿠키 저장을 거부하거나 삭제할 수 있습니다.
      </p>

      <p class="text-xs text-gray-500">
        본 방침은 2025년 6월 27일부터 시행됩니다.
      </p>
    </div>

    <div class="mt-6 text-end">
      <button onclick="document.getElementById('privacyModal').close()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        닫기
      </button>
    </div>
  </div>
</dialog>


<dialog id="termsModal" class="rounded-xl max-w-[90vw] sm:max-w-xl w-full shadow-lg backdrop:bg-black/50 z-50 open:overflow-hidden">
  <div class="bg-white rounded-xl w-full max-h-[80vh] overflow-y-auto p-6">
    <h2 class="text-lg font-bold mb-4 text-gray-800">서비스 이용약관</h2>

    <div class="text-sm text-gray-700 leading-relaxed space-y-4">
      <p>
        본 약관은 귀하가 당사의 웹호스팅 SaaS 서비스를 이용함에 있어 필요한 조건, 권리 및 의무를 규정합니다.
      </p>

      <p>
        <strong>제1조 (목적)</strong><br>
        본 약관은 사용자가 본 서비스에 가입하고, cPanel를 기반으로 한 웹사이트를 생성하고 운영하는 과정에서 발생할 수 있는 기본적인 이용 조건을 규정합니다.
      </p>

      <p>
        <strong>제2조 (서비스 내용)</strong><br>
        당사는 사용자의 신청에 따라 cPanel 기반 웹사이트를 자동으로 생성하며, WHM 기반 리셀러 서버를 통해 웹호스팅 환경을 제공합니다. 서비스 구성은 선택한 요금제에 따라 상이할 수 있습니다.
      </p>

      <p>
        <strong>제3조 (회원가입 및 계정)</strong><br>
        사용자는 유효한 이메일, 비밀번호, 연락처 등의 정보를 제공하고, 정해진 절차를 통해 회원가입을 완료해야 합니다. 회원 정보는 사용자 본인의 책임 하에 관리되어야 하며, 타인에게 공유 또는 양도할 수 없습니다.
      </p>

      <p>
        <strong>제4조 (서비스 이용 요금)</strong><br>
        서비스는 유료이며, 요금제에 따라 월 단위 또는 정기 결제 방식으로 운영됩니다. 결제 금액 및 조건은 서비스 페이지에 명시된 내용을 따릅니다.
      </p>

      <p>
        <strong>제5조 (계정 정지 및 해지)</strong><br>
        다음과 같은 경우 당사는 사전 통보 없이 계정 정지 또는 해지를 할 수 있습니다:<br>
        - 이용약관을 위반하거나 불법적인 콘텐츠를 운영한 경우<br>
        - cPanel 외 목적의 무단 사용, 리소스 과다 사용 등<br>
        - 저작권 침해 목적 사용<br>
        - 기타 서비스 안정성에 심각한 영향을 줄 경우
      </p>

      <p>
        <strong>제6조 (데이터 보관 및 삭제)</strong><br>
        서비스 해지 후 7일간 데이터가 보관되며, 이후 자동 삭제됩니다. 해지 전 백업은 사용자 책임입니다.
      </p>

      <p>
        <strong>제7조 (면책 조항)</strong><br>
        당사는 아래 사유로 인한 피해에 대해 책임지지 않습니다:<br>
        - 사용자의 과실로 인한 정보 유출 또는 손해<br>
        - IDC/서버 장애, 천재지변, 통신망 문제 등 외부 요인<br>
        - 사용자의 콘텐츠 관리 부주의
      </p>

      <p>
        <strong>제8조 (약관 변경)</strong><br>
        당사는 서비스 개선을 위해 약관을 변경할 수 있으며, 변경 시 웹사이트에 사전 공지합니다.
      </p>

      <p class="text-xs text-gray-500">
        본 약관은 2025년 6월 기준으로 작성되었으며, 최신 버전은 서비스 페이지를 통해 확인할 수 있습니다.
      </p>
    </div>

    <div class="mt-6 text-end">
      <button onclick="document.getElementById('termsModal').close()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        닫기
      </button>
    </div>
  </div>
</dialog>




<script>
  document.querySelectorAll('.faq-toggle').forEach(button => {
    button.addEventListener('click', () => {
      const content = button.nextElementSibling;
      const icon = button.querySelector('svg');

      // 토글 처리
      content.classList.toggle('hidden');
      icon.classList.toggle('rotate-180');
    });
  });
</script>

<!-- Google tag (gtag.js) -->
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Y7BBE8FQ2H"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-Y7BBE8FQ2H');
</script>

<!-- Channel Plugin Load -->
<script>
(function(){
    var w = window;
    if(w.ChannelIO){ return w.console.error("ChannelIO script included twice."); }
    var ch = function(){ ch.c(arguments); };
    ch.q = [];
    ch.c = function(args){ ch.q.push(args); };
    w.ChannelIO = ch;

    function l(){
        if(w.ChannelIOInitialized){ return; }
        w.ChannelIOInitialized = true;
        var s = document.createElement("script");
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://cdn.channel.io/plugin/ch-plugin-web.js";
        var x = document.getElementsByTagName("script")[0];
        if(x.parentNode){ x.parentNode.insertBefore(s, x); }
    }

    if(document.readyState === "complete"){ l(); }
    else {
        w.addEventListener("DOMContentLoaded", l);
        w.addEventListener("load", l);
    }
})();
</script>

@auth
@php
    $service = auth()->user()->service; // hasOne 관계라고 가정
@endphp
<script>
ChannelIO('boot', {
  pluginKey: "d090c74f-23b3-40ae-8ba5-a5d6f84dcc31",
  memberId: "{{ auth()->user()->id }}",
  profile: {
    name: "{{ auth()->user()->name }}",
    email: "{{ auth()->user()->email }}",
    mobileNumber: "{{ auth()->user()->phone }}",
    server_domain: "{{ $service?->domain ?? '없음' }}",
    plan_name: "{{ $service?->plan?->name ?? '없음' }}"
  }
});
</script>
@else
<script>
ChannelIO('boot', {
  pluginKey: "d090c74f-23b3-40ae-8ba5-a5d6f84dcc31"
});
</script>
@endauth
</body>
</html>
