✅ WHM SaaS 프로젝트 현재까지 완성 설계도 (2025-06-09 기준)
🏗 전체 시스템 흐름도
arduino
복사
편집
회원가입 → 플랜 선택 → 서비스 생성 (WHM 계정 자동생성)
→ SaaS 대시보드 → 서비스 관리 (만료, 연장, 자동정지, 삭제)
→ 관리자 패널 → 회원관리 / 서버관리 / 서비스모니터링
→ 자동 WHM 제어 (suspend / unsuspend / delete)
→ 매일 cron 스케줄러를 통한 자동정지엔진
📂 디렉토리 및 주요 소스 구조
pgsql
복사
편집
app/
 ├── Console/
 │    └── Commands/
 │         └── SaaSServiceMonitor.php (자동정지엔진)
 ├── Http/
 │    ├── Controllers/
 │    │     ├── PlansController.php (플랜 선택/서비스 생성)
 │    │     ├── Admin/
 │    │     │     ├── ServerController.php (WHM 서버 관리)
 │    │     │     └── ServiceController.php (서비스 관리, 연장/수정/삭제)
 │    ├── Middleware/
 │    │     └── AdminMiddleware.php (관리자 인증)
 ├── Models/
 │    ├── User.php
 │    ├── Plan.php
 │    ├── Service.php
 │    └── WhmServer.php
 └── Services/
      └── SaasProvisioningService.php (WHM 계정 생성 엔진)
      └── WhmApiService.php (WHM API 통신 모듈)
resources/views/
 ├── plans/index.blade.php (플랜 선택)
 └── admin/
      ├── servers/ (서버 관리 뷰)
      ├── services/ (서비스 모니터링 뷰)
      │    └── index.blade.php
routes/
 └── web.php (전체 라우팅)
public/
 └── css/
      └── admin-table.css (관리자 전용 CSS 독립 적용)
🔐 주요 라우트 (routes/web.php)
php
복사
편집
// 일반 유저용
Route::get('/plans', [PlansController::class, 'index'])->name('plans');
Route::post('/plans/select', [PlansController::class, 'select'])->name('plans.select');

// 관리자 전용 그룹
Route::middleware(['admin'])->group(function () {
    
    // WHM 서버 관리
    Route::resource('/admin/servers', ServerController::class)->names('admin.servers');
    
    // 서비스 관리
    Route::get('/admin/services', [ServiceController::class, 'index'])->name('admin.services.index');
    Route::get('/admin/services/{id}/edit', [ServiceController::class, 'edit'])->name('admin.services.edit');
    Route::post('/admin/services/{id}/update', [ServiceController::class, 'update'])->name('admin.services.update');
    Route::post('/admin/services/{id}/extend', [ServiceController::class, 'extend'])->name('admin.services.extend');
    Route::delete('/admin/services/{id}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
});
📊 DB 테이블 구조
1️⃣ users 테이블 (Jetstream 기본)
컬럼명	타입	설명
id	BIGINT	PK
name	VARCHAR	사용자명
email	VARCHAR	이메일
password	VARCHAR	패스워드
phone	VARCHAR	추가된 전화번호

2️⃣ plans 테이블 (플랜 관리)
컬럼명	타입	설명
id	BIGINT	PK
name	VARCHAR	플랜명
price	INTEGER	가격
disk_size	INTEGER	디스크 용량 (GB)
whm_package	VARCHAR	WHM 패키지명

3️⃣ whm_servers 테이블 (서버풀 관리)
컬럼명	타입	설명
id	BIGINT	PK
name	VARCHAR	서버명
ip_address	VARCHAR	WHM IP
whm_user	VARCHAR	WHM 접근 유저명
whm_token	TEXT	WHM API 토큰
status	ENUM	활성/비활성

4️⃣ services 테이블 (서비스 인스턴스)
컬럼명	타입	설명
id	BIGINT	PK
user_id	FK → users	
plan_id	FK → plans	
whm_username	VARCHAR	WHM 유저명
whm_domain	VARCHAR	도메인 (서브도메인 기반)
whm_server_id	FK → whm_servers	
expired_at	DATETIME	만료일
status	ENUM	active / suspended / deleted
created_at	DATETIME	
updated_at	DATETIME	

🧠 SaaS 핵심 로직 흐름
1️⃣ 가입 → 플랜 선택 → 계정생성
SaaSProvisioningService 가 WHM API 통해 자동 계정 생성

WHM 계정 생성 성공시 services 레코드 생성

expired_at은 최초 1개월 설정됨

2️⃣ 자동정지엔진 (SaaSServiceMonitor.php)
매일 스케줄러 (Laravel schedule:run) 에 의해 실행

만료일이 지나면:

D+2 : WHM suspendAcct

D+3 : WHM deleteAcct

3️⃣ 관리자가 수동 연장
admin.services.extend 라우트 통해

expired_at 1개월 연장

만약 suspend 상태면 unsuspendAcct 호출

4️⃣ 수동 삭제 (관리자 삭제)
DB 삭제

WHM deleteAcct 호출

사용 디스크 용량 감소 처리 포함

🧪 테이블 관계도 (ERD)
text
복사
편집
User (1) ---- (N) Service (N) ---- (1) Plan
                      |
                      |
                  (1) WhmServer
🖥 관리자 UI 상태
✅ 전체 서비스 모니터링

✅ 연장 / 수정 / 삭제 기능

✅ 상태별 컬러뱃지 완벽 분리 (충돌없는 css)

🔧 추가 메모리에 저장된 규칙
✅ 모든 Admin View는 @extends('layouts.admin')로 통일

✅ 모든 Admin Table은 admin-table.css 독립 스타일 유지

이제부터 연장 결제 PG 연동 / 신규 고객 대시보드 기능 / SaaS 정산엔진 등도 이 구조 그대로 쉽게 확장 가능.





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