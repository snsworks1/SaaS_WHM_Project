1️⃣ Laravel SaaS 기본 뼈대 완성
이건 SaaS 전체 시스템의 중심입니다.

 Laravel Jetstream 설치 (회원가입/로그인/이메일 인증) - 2025-06-05 완료

 SaaS 요금제 테이블 설계 (플랜별 제한사항)

 기본 테넌시 구조 설계 (멀티 테넌시 or 싱글 테넌시)

 Admin 계정 생성 (super admin)


2️⃣ WHM API 연동 설계 및 코딩 시작
 WHM API 접속용 PHP Client 클래스 작성

 SaaS 가입 → WHM API 호출 → 리셀러 계정 자동생성

 샘플 WordPress 설치 자동화 (WP-CLI 사용)

🔥 내가 샘플 WHM API 연동코드 만들어 드림 가능

3️⃣ Cloudflare API 연동 개발
 Cloudflare API Key 발급 → SaaS에 등록

 고객 가입시 → Cloudflare DNS 자동등록 (서브도메인 자동화)

 SSL 자동연동 (AutoSSL or Flexible SSL)

🔥 이건 SaaS 자동화를 위해 꼭 필요 → 내가 샘플코드 제공 가능

4️⃣ 결제 시스템 연동
 Toss Payments 연동 신청

 Laravel Cashier 연동 (구독형 SaaS 구축)

 결제 Webhook → 결제상태 자동갱신

🔥 내가 Toss 연동용 Laravel 코드 샘플도 제공 가능

5️⃣ 관리자 패널 구축 (Admin Panel)
 가입자 관리

 서버별 자원 모니터링 (WHM API 활용)

 결제상태 관리

 SaaS 통계화면

Laravel Nova 사용 추천

6️⃣ SaaS 완성 테스트
 신규 가입 → 자동계정 생성 → 자동 도메인 연동 → 결제 정상동작

 워드프레스 접속 정상확인

 보안 및 장애 테스트



# WHM SaaS 시스템 (WordPress 기반 게임 서버용 SaaS 플랫폼)

## 📌 프로젝트 개요

- WHM 리셀러 서버 자동화 기반 SaaS 플랫폼
- 대상: 게임 서버 운영자, 길드, 프리서버 운영자
- 기능: 회원가입 → 결제 → 워드프레스 사이트 자동 생성 → 서버 자동 배포 → SaaS 관리 시스템

---

## ✅ 현재까지 완료된 기능

### 1. 인프라 세팅
- Ubuntu 24.04 / Nginx / PHP 8.3 / MySQL 8.x / Laravel 12.x

### 2. 회원가입 / 인증 시스템
- Jetstream / Breeze / Fortify 기반 로그인 & 회원가입
- 이메일 인증 (SMTP 연동)
- 전화번호 필드 추가

### 3. SaaS 핵심 모델 설계
- `users` 테이블
- `plans` 테이블
- `is_admin` 관리자 권한 시스템 구축

### 4. 플랜 시스템
- 유저 플랜 선택 기능
- 관리자 플랜 CRUD 시스템 구축

### 5. 관리자 시스템
- 관리자 전용 페이지 구축
- 관리자 회원 관리 (검색, 수정, 플랜 변경)
- 관리자 플랜 관리 (CRUD)

---

## 🔧 예정 개발

- 서버 관리 시스템 (WHM 서버풀 관리)
- WHM API 연동 → 서버 자동 생성, 워드프레스 자동설치
- Toss Payments 정기결제 연동
- SaaS 운영툴 (모니터링, 통계, 결제내역 등)

---

## 📂 디렉토리 및 주요 파일 구조

/app
/Http
/Controllers
/Admin
PlanController.php # 관리자 플랜 관리 컨트롤러
UserController.php # 관리자 회원 관리 컨트롤러
PlansController.php # 유저 전용 플랜 선택 컨트롤러
/Middleware
AdminMiddleware.php # 관리자 전용 접근제어 미들웨어

/database
/migrations
create_users_table.php
create_plans_table.php # 플랜 테이블
add_is_admin_to_users_table.php # 관리자 권한 컬럼 추가

/resources
/views
/layouts
admin.blade.php # 관리자 전용 레이아웃
/admin
/plans
index.blade.php
create.blade.php
edit.blade.php
show.blade.php
/users
index.blade.php
edit.blade.php
/plans
index.blade.php # 유저 전용 플랜 선택

/routes
web.php # 라우팅 정의
middleware.php # Laravel 12 미들웨어 등록

/bootstrap
app.php # Laravel 12 Application 구성 파일

/vite.config.js # Vite 빌드 구성

yaml
복사
편집

---

## 🛠 기술스택

- Laravel 12.x (PHP 8.3)
- MySQL 8.x
- Tailwind CSS
- Breeze + Fortify + Jetstream
- Vite (빌드툴)
- Nginx + Ubuntu 24.04
- SMTP 이메일 인증
- WHM API, Toss Payments (추후 연동 예정)