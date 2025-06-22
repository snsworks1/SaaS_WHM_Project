
## 🔄 최근 작업 내역


✅ [2025-06-22] 관리자 통계 - 연장 결제 리스트 기능 추가
📊 핵심 기능

    이번 달 서비스 연장 결제 내역을 관리자 통계 탭에서 확인 가능

    연장일, 만료일, 남은 일수 등 상세 정보 표시

    연장 결제 관련 정보는 payments, service_extensions, services 테이블을 조인하여 구성

🔍 표시 항목

    유저명 (user.name)

    서비스 도메인 (whm_domain)

    플랜명 (plan.name)

    연장 개월 수 (period)

    결제 금액 (amount)

    연장일 (paid_at)

    만료일 (service.expired_at)

    남은 일수 (D-n 형식, expired_at - now() 계산)

🔁 모델 연관 관계 수정 및 보완

    Payment 모델에 extension() 관계 추가
    → hasOne(ServiceExtension::class, 'payment_id', 'payment_key')

    ServiceExtension 모델에 service(), payment() 관계 추가

    Service 모델에 getDaysLeftAttribute() 추가로 남은 일수 계산

    Service 모델 $fillable에 whm_domain 필드 추가

    뷰에 service.whm_domain ?? '도메인 없음' 처리로 null 방지

🖥 관리자 화면 개선

    /admin/stats?tab=renewals 경로에 연장 결제 리스트 테이블 추가

    날짜 포맷 및 남은 일수 소수점 제거

    테이블 레이아웃 개선 및 도메인 출력 오류 수정

🗂 변경된 주요 파일

    resources/views/admin/stats/partials/renewals.blade.php

    App\Models\Payment

    App\Models\ServiceExtension

    App\Models\Service

    App\Http\Controllers\Admin\AdminStatsController.php




## ✅ [2025-06-22] 플랜 업그레이드 기능 완성

### 🔧 핵심 기능
- 사용자의 현재 서비스 플랜을 상위 플랜으로 업그레이드 가능
- 업그레이드 시 기존 플랜의 남은 가치 차감 → 새 플랜의 잔여 금액만 결제
- 할인율 반영된 요금 계산 + 일할 계산 공식 구현

### 💳 Toss Payments 결제 연동
- Toss JS SDK 사용하여 프론트엔드 결제 요청
- 결제 성공 시 backend에서 Toss API로 결제 확인 → 검증 성공 시 업그레이드 처리

### 🔁 업그레이드 처리 내용
- 서비스의 `plan_id`를 업그레이드된 플랜으로 변경
- WHM API 연동을 통해 cPanel 계정의 패키지도 자동 변경
- 서비스 만료일(`expired_at`)은 유지 (연장 아님)

### 🖥 사용자 화면 개선
- 플랜 요금 비교 카드 UI 추가
- 결제 금액 산정 방식(공식) 안내 텍스트 추가
- 서비스 만료일 및 차감 금액 표시

### 🗂 변경된 주요 파일
- `PlanUpgradeController.php`: Toss 연동 및 업그레이드 로직 통합
- `confirm-upgrade.blade.php`: 플랜 요금 비교 UI + 결제 버튼
- `routes/web.php`: 업그레이드 성공/실패 라우트 추가



📌 SaaS 시스템 기능 개선 내역 (2025-06-19 기준)

## ✅ 환불 승인 시 서비스 자동 삭제 처리

환불 웹훅(`PAYMENT_STATUS_CHANGED` + `status: CANCELED`) 수신 시, 다음 절차에 따라 서비스가 자동 삭제됩니다:

### 🔄 처리 흐름
1. Toss Webhook 수신 (`WebhookController@handleTossWebhook`)
2. 주문번호(`order_id`)로 서비스 조회
3. `ProvisioningService::terminateService($service)` 실행

### 🧹 자동 삭제 항목
- WHM 계정 삭제 (`removeacct`)
- Cloudflare DNS 레코드 삭제
- 서비스 DB 레코드 삭제
- 사용된 WHM 서버 디스크 사용량 차감 (`used_disk_capacity`)

### ⚙️ 디스크 차감 방식
- `services.plan_id` → 연결된 플랜(`plans.disk_size`) 기준으로 차감
- 차감 후 음수 방지를 위해 `max(0, used - plan.disk_size)` 적용

### 🛠 실패 처리
- WHM 삭제, DNS 삭제, DB 삭제 중 예외 발생 시 `ErrorLog` 테이블에 자동 기록
  - level, type, title, message, server_id, whm_username 등 포함



✅ 결제 완료 후 자동 프로비저닝 기능 구현
연동 흐름:

Toss Webhook → 결제 완료 확인

WHM 계정 자동 생성 (API)

Cloudflare A레코드 자동 생성 (DNS 연결)

SSH를 통해 사용자 전용 MySQL DB 및 사용자 생성

모든 작업이 완료되면 Payment, Service 테이블에 상세 정보 저장

DB 저장 구조 개선:

payments 테이블: order_id, payment_key, amount, status, approved_at, service_id 추가 저장 로직

services 테이블: whm_username, whm_password, whm_server_id, whm_domain, dns_record_id, order_id 저장 가능하도록 fillable 필드 업데이트

cPanel 바로가기 기능:

createCpanelSession 메서드에서 create_user_session API를 사용하여 직접 접속 가능한 URL 생성 가능

✅ 기타
디버깅 로그 정교화 (📌 로그 태그 사용)

실패 시 상세한 에러 저장: error_logs 테이블을 통한 추적 가능

Cloudflare 연동 시 proxied: true 설정으로 실 운영에 맞춘 구성


2025-06-19 새벽

🎯 기능 구현 내용
1. 사용자 서비스 환불 기능 구현

    서비스 상세(서비스 설정 페이지)에서 "환불 요청하기" 버튼 추가

    /services/{id}/refund 라우트 → 환불 상세 및 예상 금액 표시 페이지

    14일 이내 환불 가능 (정책 적용)

    할인 플랜은 할인분에 대해 위약금 차감 후 환불금 계산

    TossPayments 연동으로 실 결제 취소 처리

    환불 성공 시 서비스 상태: canceled, 결제 상태: CANCELED

2. 환불 UX 개선

    환불 요청 성공 시 모달 팝업(SweetAlert2) 표시

    사용자 확인 시 대시보드로 이동

3. 로직 개선 및 리팩토링

    RefundCalculator 헬퍼 클래스 도입 → 환불 로직 재사용화

    refundForm()에서 계산한 값을 processRefund()에 중복없이 재활용 가능하도록 개선

    날짜 계산 시 .startOfDay() 처리로 1일 오차 방지

🚧 추후 작업 예정

    이 내용도 README에 추가하세요.

환불 성공 시 WHM 계정 삭제 자동화

Cloudflare DNS 레코드 삭제

services 테이블에서 기록을 분리하여 expired_services 또는 archived_services로 이관 (테이블 미생성)

환불/해지 서비스 기록 보관 페이지 구성


✅ WHM SaaS 시스템 진행 작업 내역 (2025-06-18 기준)

✨ 사용자 공지사항 시스템 구축

    사용자용 공지사항 리스트 페이지(/notices)

        공지 종류, 중요도, 제목, 작성일 포함된 테이블 형태

        최신순 정렬 및 보기 편한 디자인

    공지사항 상세 보기 기능

        /api/notices/{id}로 JSON 받아와 모달로 표시

        Editor.js 포맷(JSON)을 HTML로 변환하여 시각적으로 렌더링

    공지사항 미리보기 모달 구현 (대시보드)

        제목 클릭 시 전체 내용 팝업으로 표시

    사용자 대시보드에 최근 공지사항 3개 표시

        제목 클릭 → 모달

        "자세히 보기" → 공지사항 전체 리스트 페이지로 이동

📄 서비스 플랜 변경 기능 구현

    새로운 컨트롤러: PlanUpgradeController 생성

    라우팅: 기존 UserServiceController → PlanUpgradeController 로 교체

    사용자 플랜 변경 3단계 flow:

        /services/{id}/change-plan : 업그레이드 가능한 플랜 리스트

        /services/{id}/confirm-upgrade : 차액 계산 및 결제 확인

        /services/{id}/process-upgrade : 실제 패키지 변경 처리 + WHM API 호출

    플랜 업그레이드 후 완료 페이지: /services/{id}/upgrade-complete

🎨 대시보드 UI 개선

    요약 카드 영역과 헤더 사이 여백 추가 (mt-8)

    공지사항/패치노트 영역 위쪽 여백 추가 (mt-32)

    각 카드별 여백 및 컬럼 배치 개선 (Tailwind grid)


✅ WHM SaaS 시스템 진행 작업 내역 (2025-06-16 기준)

✅ 현재까지 개발 완료된 상태
기능 범주	구현 완료 내용
회원가입/인증	Jetstream 기반 가입, 전화번호 필드 포함, 이메일 인증 완료
플랜 시스템	plans 테이블, 사용자 플랜 선택 및 저장, 플랜별 디스크 용량 설정
결제 연동	Toss Payments 웹훅 연동, 결제 성공 시 서비스 자동 생성 (정기 결제 제외)
WHM 계정 생성	WHM API createacct 사용하여 자동 계정 생성, Cloudflare DNS 연동
DB 자동 생성	SSH로 uapi 명령 실행 → DB 생성, 유저 생성, 권한 부여
서비스 설정 UI	서비스별 DB 정보, 워드프레스 설치 여부 확인 및 설치 버튼, 테마 선택 탭 구현
워드프레스 설치	선택 버전 기반 zip 다운로드 + 설치 디렉토리에 자동 압축 해제
WHM 서버 풀 관리 (Admin)	서버 등록, 삭제, 수정 UI / API 연결 상태 / SSH 연결 상태 / 계정 수 / 디스크 사용량 표시
자동로그인 (cPanel)	사용자 대시보드에서 “cPanel 바로가기” → WHM 세션 API 통해 자동 로그인
디스크 사용량 관리	서비스 생성 시 사용 디스크 증가 (used_disk_capacity += plan.disk_size)

🔜 추가 개발이 필요한 항목들
항목	상세 설명
✅ 워드프레스 설치 후 상태 표시 개선	설치 후 관리자 로그인 바로가기, 설치 성공 메시지 개선
🔄 서비스 환불/해지 처리	환불 신청 → 사용기간 환산 → 서비스 삭제 또는 일시정지
+ 디스크 사용량 감소 처리 (used_disk_capacity -= X)
🔄 정기 결제 연동	Toss Payments 구독 API 연동 (billing key 기반)
🔄 WHM 서버 자동 분산 알고리즘 개선	서버 우선순위, 사용률, 부하 기반의 동적 분산 방식 구현
🔄 서버 연결 실패 시 알림	연결 상태 변경(connected → disconnected) 시 Slack 또는 DB 기록
🔄 서비스 연장/갱신 시스템	유저가 만료 전 연장 가능 / 자동 연장 방식
🔒 사용자 보안 강화	비밀번호 규칙 강화, 2FA 옵션, 비밀번호 변경 UI
📄 사용자 가이드 UI 최종 반영	/guide 또는 /help 메뉴에서 정리된 사용법 노출 예정


1. users, plans, services 기본 모델 및 DB 설계
📁 app/Models/User.php, Plan.php, Service.php

    사용자/플랜/서비스 연동 설계

    Service 모델에 whm_server_id, whm_username, whm_password, expired_at 등 필드 추가

    getStatusAttribute, getDaysLeftAttribute로 상태 계산 자동화

2. 플랜 선택 및 결제 흐름 구현
📁 routes/web.php

Route::get('/checkout/confirm', [PaymentController::class, 'confirmGet'])->name('checkout.confirm');

📁 app/Http/Controllers/PaymentController.php

    Toss Payments 결제 완료 후

        유저 플랜 업데이트

        사용 가능한 WHM 서버 자동 선택

        WHM 계정 자동 생성 (WHM API 사용)

        DNS 자동 생성 (Cloudflare API 사용)

        cPanel DB 자동 생성 (SSH + uapi 사용)

        서비스(services) 테이블에 등록

✅ 디스크 사용량 증가 로직 포함:

$server->used_disk_capacity += $plan->disk_size;
$server->save();

3. WHM 서버 관리 기능 (Admin 전용)
📁 app/Http/Controllers/Admin/WhmServerController.php

    서버 추가/수정/삭제

    실시간 연결 상태 확인 (WHM API 연결 테스트)

    SSH 연결 가능 여부 확인 (fsockopen 사용)

    각 서버의 계정 수 및 디스크 사용량 표시

📁 resources/views/admin/whm_servers/index.blade.php

    카드 형태 UI로 각 서버 상태 시각화

    연결 상태, SSH 상태, 계정 수, 디스크 사용량, 사용률 등 표시

4. 서비스 설정 페이지 (워드프레스 설치)
📁 resources/views/services/settings.blade.php

    DB 정보 출력: DB 이름, 사용자, 비밀번호 비공개 표시

    워드프레스 설치 여부 실시간 확인

    설치 진행 시, 압축 다운로드 및 해제 자동화 진행 표시 (progress bar 포함)

5. cPanel 자동 로그인 기능
📁 app/Http/Controllers/UserServiceController.php

public function openCpanel($id)
{
    $service = Service::findOrFail($id);
    $server = $service->whmServer;

    if (!$server) {
        return redirect()->back()->with('error', 'WHM 서버 정보 없음');
    }

    $api = new WhmApiService($server);
    $url = $api->createCpanelSession($service->whm_username);

    return $url ? redirect()->away($url) : redirect()->back()->with('error', 'cPanel 자동 로그인 실패');
}

📁 app/Services/WhmApiService.php

public function createCpanelSession(string $cpUsername): ?string
{
    $response = Http::withHeaders([
        'Authorization' => 'whm ' . $this->username . ':' . $this->token,
    ])->withOptions(['verify' => false])->get("https://{$this->server->api_hostname}:2087/json-api/create_user_session", [
        'api.version' => 1,
        'user' => $cpUsername,
        'service' => 'cpaneld',
    ]);

    return $response['data']['session'] ?? null;
}

6. DB 자동 생성 기능
🔧 명령 실행 방식

    SSH 접속 → uapi 명령어 3종 실행

        create_database

        create_user

        set_privileges_on_database

✅ 예시:

$commands = [
    "uapi --user={$cpUser} Mysql create_database name={$dbName} collation=utf8_general_ci",
    "uapi --user={$cpUser} Mysql create_user name={$dbUser} password={$dbPassword}",
    "uapi --user={$cpUser} Mysql set_privileges_on_database user={$dbUser} database={$dbName} privileges=ALL",
];

7. 기타 설정 및 개선 사항

    used_disk_capacity, total_disk_capacity 필드 연동 완료 (whm_servers)

    ssh 포트 49999 기본 적용

    WHM 서버에 Laravel 서버의 SSH 키 등록을 통해 명령 실행 자동화 완료

    WHM 서버 hostname: panel-admin-01.hostyle.me 등으로 설정 후, 실제 자동로그인 URL에도 반영 완료



    구분	파일/위치	설명	진행한 내용
1	resources/views/services/settings.blade.php	서비스 상세/설정 페이지	워드프레스 설치 상태 확인 / 설치버튼 / DB 정보 박스 UI 추가 (DB 이름, DB 유저, 비밀번호 미노출 문구 포함)
2	PaymentController.php	결제 완료 시 계정 생성 로직	WHM 계정 생성 후 uapi 명령어로 DB 자동 생성, DB 유저 생성, 권한 부여까지 SSH로 자동 처리
3	WhmServerController.php + admin/whm_servers/index.blade.php	WHM 서버 풀 관리 UI	- 서버 추가/수정/삭제

연결 상태 체크 (WHM API ping)

SSH 연결 테스트 (fsockopen) 결과도 표시

계정 수, 디스크 사용량 등 표시

used_disk_capacity 및 total_disk_capacity 시각화 |
| 4 | ServiceController.php (또는 UserServiceController.php) | cPanel 자동 로그인 | create_user_session API를 통해 로그인 토큰 발급 → 사용자 cPanel 바로가기 동작 |
| 5 | services 테이블, Service.php 모델 | 서비스 생성 후 디스크 사용량 반영 | 서비스 생성시 used_disk_capacity += plan.disk_size 자동 증가 처리 |
| 6 | phpMyAdmin에서 whm_servers DB 확인 | API hostname 필드 활용 | api_hostname 필드를 활용해 자동 로그인 시 hostname 기반으로 처리되도록 조정 |
| 7 | WHM 설정 (Apache Global Configuration) | Index of 차단 처리 | WHM > Apache Configuration > Indexes 옵션 체크 해제 → 기본 디렉토리 리스트 노출 방지 적용 완료 |

✅ 사용된 주요 명령/패턴 요약
DB 생성 명령:

bash
복사
편집
uapi --user=username Mysql create_database name=username_db collation=utf8_general_ci
DB 유저/권한 부여:

bash
복사
편집
uapi --user=username Mysql create_user name=username_admin password=비밀번호  
uapi --user=username Mysql set_privileges_on_database user=username_admin database=username_db privileges=ALL
SSH 연결 테스트 (Laravel 내부):

php
복사
편집
fsockopen($ip, $port, $errno, $errstr, 3)
WHM API 기반 cPanel 자동 로그인:

php
복사
편집
GET /json-api/create_user_session?api.version=1&user={cpuser}&service=cpaneld


### 2025-06-11

#### 🧾 회원가입 개인정보 동의 처리 개선
- 회원가입 시 [필수] 개인정보 수집 및 이용 동의 체크박스 추가
- "보기" 클릭 시 개인정보 전문을 모달 창으로 열람 가능
- 체크하지 않으면 회원가입 불가 (required + disabled 처리)

#### 📬 마케팅 정보 수신 동의 기능 추가
- [선택] 광고성 정보 수신 동의 체크박스 추가
- User 모델에 `marketing_opt_in`, `marketing_opt_in_at` 컬럼 도입
- Fortify `CreateNewUser` 액션 내 해당 필드 저장 처리
- Eloquent `$fillable`, `$casts` 항목 업데이트 완료

> ✅ 적용 위치:  
> - Blade: `resources/views/auth/register.blade.php`  
> - 모델: `App\Models\User.php`  
> - 등록 로직: `App\Actions\Fortify\CreateNewUser.php`  
> - 마이그레이션: `add_marketing_fields_to_users_table.php`


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