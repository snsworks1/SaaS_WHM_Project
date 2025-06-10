
<!DOCTYPE html>
<html lang="ko" class="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
          <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/IconOnly_Transparent_NoBuffer.png') }}">

  <title>Cflow SaaS 기반 웹호스팅</title>
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
<header class="bg-zinc-900 border-b border-zinc-800 sticky top-0 z-50">
  <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">
    <div class="flex items-center">
      <!-- Logo Placeholder -->
   
      <div class="text-lg font-bold">
         <img src="images/FullLogo_Transparent_NoBuffer.png" class="w-48 h-auto mx-auto" >
      </div>
    </div>
    <nav class="space-x-6 text-sm flex items-center">
      <a href="#why" class="hover:text-blue-400">특장점</a>
      <a href="#infra" class="hover:text-blue-400">인프라</a>
      <a href="#images" class="hover:text-blue-400">기능</a>
      <a href="#plans" class="hover:text-blue-400">요금제</a>
      <!-- Login / Dashboard -->
      @auth
        <a href="{{ route('dashboard') }}" class="ml-6 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
          Dashboard 바로가기
        </a>
      @else
        <a href="{{ route('login') }}" class="ml-6 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
          로그인
        </a>
      @endauth    </nav>
  </div>
</header>



<!-- Hero -->
<section class="px-6 py-20 max-w-7xl mx-auto text-center">
  <h1 class="text-5xl font-extrabold mb-6 ">SaaS 자동화 웹 솔루션 - CFlow<br><br>강력하고 간편한 웹호스팅<br></h1>
  <p class="text-zinc-300 mb-6">회원가입 → 플랜 선택 → 결제 → 자동 구축<br>복잡한 설치 없이 3분 만에 내 웹사이트 오픈</p>
  <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">지금 시작하기</a>
</section>

<!-- Key Features with Slider -->
<section id="why" class="bg-zinc-950 py-20 px-6">
  <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-10 items-center">
    <div>
      <h2 class="text-5xl font-bold mb-4">왜 Cflow인가?</h2>
      <ul class="space-y-4 text-sm text-zinc-300">
        <li>✅ 클릭 한 번으로 WordPress 설치 완료</li>
        <li>✅ 프리미엄 유료 템플릿 무료 제공</li>
        <li>✅ 사용자 맞춤형 가이드북 제공</li>
        <li>✅ 무제한 트래픽 / 월 요금제</li>
        <li>✅ 초보자도 쉽게 사용하는 cPanel 인터페이스</li>
        <li>✅ 결제 후 3분 이내 자동 구축 및 접속 가능</li>
      </ul>
    </div>
    <!-- Image Slider -->
    <div class="slider relative w-[800px] h-[500px] bg-zinc-800 rounded-lg overflow-hidden">
      <img src="images/img1.PNG" class="absolute inset-0 w-full h-full object-fill opacity-100">
      <img src="images/img2.png" class="absolute inset-0 w-full h-full object-fill opacity-0">
      <img src="images/img3.PNG" class="absolute inset-0 w-full h-full object-fill opacity-0">
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
      </ul>
    </div>

  </div>
</section>


<!-- Detailed Screenshots -->
<section id="images" class="bg-zinc-950 py-20 px-6">
  <div class="max-w-6xl mx-auto space-y-16">
    <!-- Dashboard -->
    <div class="grid md:grid-cols-2 gap-8 items-center">
      <img src="images/dashboard_img.PNG" alt="대시보드 이미지" class="rounded-lg shadow-xl h-[300px]" >
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
    <div class="max-w-7xl mx-auto space-y-16">
  <h2 class="text-2xl font-bold mb-10 text-center">요금제 플랜</h2>
  <div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white text-black p-6 rounded-xl shadow-lg">
      <h3 class="text-xl font-bold mb-2">Basic 플랜</h3>
      <p class="text-sm mb-4">₩39,000 / 월</p>
      <ul class="text-sm space-y-1">
        <li>✔ 5GB SSD 저장공간</li>
        <li>✔ 무제한 트래픽</li>
        <li>✔ WordPress 자동설치</li>
        <li>✔ 템플릿 기본 제공</li>
        <li>✔ 보안 및 캐시 최적화 포함</li>
      </ul>
    </div>
    <div class="bg-white text-black p-6 rounded-xl shadow-lg">
      <h3 class="text-xl font-bold mb-2">Pro 플랜</h3>
      <p class="text-sm mb-4">₩59,000 / 월</p>
      <ul class="text-sm space-y-1">
        <li>✔ 10GB SSD 저장공간</li>
        <li>✔ 무제한 트래픽</li>
        <li>✔ 프리미엄 템플릿 전체 제공</li>
        <li>✔ 강화된 보안 및 캐시</li>
        <li>✔ 다중 페이지 관리 기능</li>
      </ul>
    </div>
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
        템플릿은 WordPress에 자동 설치되는 형태로 제공되며, 로그인 후 테마 설정에서 활성화할 수 있습니다. Pro 플랜은 프리미엄 템플릿 전체 제공됩니다.
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
        네. 기본 제공되는 임시 도메인(예: yourname.cflow.dev)으로 먼저 시작할 수 있으며, 이후 도메인을 연결할 수 있습니다.
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
        모든 플랜은 트래픽 무제한을 기본으로 제공하며, SSD 저장 용량은 각 플랜 기준에 따라 적용됩니다. 디스크 사용량은 대시보드에서 실시간 확인 가능합니다.
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
</footer>

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
<script async src="https://www.googletagmanager.com/gtag/js?id=G-F476SE7JC5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-F476SE7JC5');
</script>
</body>
</html>
