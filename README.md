# WHM SaaS 시스템 개발 현황 정리

## 📌 프로젝트 개요

WordPress 기반 게임 서버용 SaaS 플랫폼으로, WHM 리셀러 서버 자동화를 통해 회원가입부터 서버 배포까지 원클릭으로 처리하는 시스템입니다.

**대상 고객**: 게임 서버 운영자, 길드, 프리서버 운영자  
**핵심 기능**: 회원가입 → 플랜 선택 → 결제 → WordPress 사이트 자동 생성 → 서버 관리

---

## 🗓 개발 진행 현황 (날짜별)

### 2025-06-19 (최신)
#### 🔄 환불 시스템 완전 구축
- **환불 요청 UI**: `/services/{id}/refund` 페이지 구현
- **환불 금액 계산 로직**: 
  - 14일 이내: 일할 계산 후 위약금 차감
  - 14일 초과: 월 단위 사용금액 + 위약금 반영
  - 1개월 플랜은 14일 초과 시 환불 불가
- **Toss Payments 부분 환불 API 연동**: `cancelAmount` 포함
- **자동 서비스 삭제**: 환불 승인 시 WHM 계정, Cloudflare DNS, DB 자동 삭제
- **디스크 사용량 관리**: 서비스 삭제 시 `used_disk_capacity` 자동 차감

#### ✅ 결제 완료 후 자동 프로비저닝
- **Toss Webhook**: 결제 완료 확인 자동화
- **WHM 계정 생성**: API 기반 자동 생성
- **Cloudflare DNS**: A레코드 자동 생성
- **MySQL DB 생성**: SSH를 통한 사용자 전용 DB 및 계정 생성
- **cPanel 바로가기**: `create_user_session` API로 원클릭 접속

### 2025-06-18
#### ✨ 사용자 공지사항 시스템
- **공지사항 리스트**: `/notices` 페이지 구현
- **상세 보기**: Editor.js 포맷을 HTML로 변환하여 모달 표시
- **대시보드 연동**: 최근 공지사항 3개 표시

#### 📄 서비스 플랜 변경 기능
- **PlanUpgradeController**: 플랜 업그레이드 전용 컨트롤러
- **3단계 플로우**: 플랜 선택 → 차액 확인 → 실제 변경 처리
- **WHM API 연동**: 패키지 변경 자동화

### 2025-06-16
#### 🎯 워드프레스 설치 및 서비스 관리
- **워드프레스 자동 설치**: 선택 버전 기반 zip 다운로드 및 압축 해제
- **서비스 설정 UI**: DB 정보, 설치 상태 확인, 테마 선택
- **WHM 서버 풀 관리**: 서버 등록/삭제/수정, 연결 상태 모니터링
- **디스크 사용량 관리**: 서비스 생성 시 자동 증가 처리

### 2025-06-11
#### 🧾 개인정보 보호 강화
- **개인정보 동의**: 필수 동의 체크박스 및 전문 모달
- **마케팅 수신 동의**: 선택적 광고성 정보 수신 동의
- **User 모델 확장**: `marketing_opt_in`, `marketing_opt_in_at` 필드 추가

### 2025-06-09
#### 🏗 SaaS 핵심 시스템 완성
- **자동정지엔진**: `SaaSServiceMonitor` 명령어로 만료 서비스 자동 관리
- **관리자 패널**: 서비스 모니터링, 연장, 수정, 삭제 기능
- **WHM API 서비스**: 계정 생성, 정지, 해제, 삭제 자동화
- **데이터베이스 구조 완성**: ERD 기반 테이블 관계 확립

### 2025-06-05
#### 🚀 프로젝트 초기 설정
- **Laravel Jetstream**: 회원가입/로그인/이메일 인증 구축
- **기본 모델**: User, Plan, Service, WhmServer 모델 설계
- **관리자 시스템**: AdminMiddleware 및 관리자 전용 라우팅

---

## 🏛 시스템 아키텍처

### 📊 데이터베이스 구조
```
users (사용자)
├── id, name, email, phone
├── is_admin (관리자 권한)
└── marketing_opt_in (마케팅 동의)

plans (요금제)
├── id, name, price
├── disk_size (디스크 용량)
└── whm_package (WHM 패키지명)

whm_servers (서버 풀)
├── id, name, ip_address
├── whm_user, whm_token
├── used_disk_capacity, total_disk_capacity
└── status (연결 상태)

services (서비스 인스턴스)
├── user_id → users
├── plan_id → plans
├── whm_server_id → whm_servers
├── whm_username, whm_domain
├── order_id, payment_key
└── expired_at, status
```

### 🔄 핵심 프로세스 흐름

#### 1. 서비스 생성 플로우
```
회원가입 → 플랜 선택 → Toss 결제 → Webhook 수신
→ WHM 계정 생성 → Cloudflare DNS 등록 → MySQL DB 생성
→ 서비스 테이블 저장 → 이메일 알림
```

#### 2. 환불 처리 플로우
```
환불 요청 → 사용기간 계산 → 위약금 차감 → Toss 부분환불
→ Webhook 수신 → WHM 계정 삭제 → DNS 삭제 → DB 정리
```

#### 3. 자동 관리 시스템
```
매일 스케줄러 실행 → 만료 서비스 확인
→ D+2: WHM 계정 정지 → D+3: 완전 삭제
```

---

## 🛠 기술 스택

### Backend
- **Laravel 12.x** (PHP 8.3)
- **MySQL 8.x**
- **Laravel Jetstream** (인증)
- **Laravel Fortify** (사용자 관리)

### Frontend
- **Tailwind CSS**
- **Blade Templates**
- **SweetAlert2** (모달)
- **Editor.js** (공지사항)

### External APIs
- **WHM API** (서버 관리)
- **Cloudflare API** (DNS 관리)
- **Toss Payments** (결제 처리)

### Infrastructure
- **Ubuntu 24.04**
- **Nginx**
- **SSH** (원격 명령 실행)

---

## 📂 주요 파일 구조

```
app/
├── Console/Commands/
│   └── SaaSServiceMonitor.php          # 자동정지엔진
├── Http/Controllers/
│   ├── PaymentController.php           # 결제 처리
│   ├── PlanUpgradeController.php       # 플랫폼 업그레이드
│   ├── ServiceSettingsController.php   # 서비스 설정
│   └── Admin/
│       ├── WhmServerController.php     # 서버 풀 관리
│       └── ServiceController.php       # 서비스 관리
├── Models/
│   ├── User.php, Plan.php, Service.php
│   └── WhmServer.php
├── Services/
│   ├── WhmApiService.php              # WHM API 통신
│   ├── TossPaymentService.php         # 토스 결제
│   ├── ProvisioningService.php        # 프로비저닝
│   └── RefundCalculator.php           # 환불 계산
└── Http/Middleware/
    └── AdminMiddleware.php            # 관리자 권한

resources/views/
├── services/
│   ├── settings.blade.php             # 서비스 설정
│   └── refund.blade.php              # 환불 페이지
├── admin/
│   ├── services/index.blade.php       # 서비스 모니터링
│   └── whm_servers/index.blade.php    # 서버 관리
└── notices/
    └── index.blade.php               # 공지사항
```

---

## ✅ 완료된 핵심 기능

### 🔐 사용자 관리
- [x] 회원가입/로그인 (Jetstream)
- [x] 이메일 인증
- [x] 개인정보 동의 처리
- [x] 마케팅 수신 동의

### 💳 결제 및 환불
- [x] Toss Payments 연동
- [x] Webhook 기반 자동 처리
- [x] 부분 환불 시스템
- [x] 위약금 계산 로직

### 🖥 서버 관리
- [x] WHM 서버 풀 관리
- [x] 자동 계정 생성/삭제
- [x] 디스크 사용량 추적
- [x] 연결 상태 모니터링

### 🌐 인프라 자동화
- [x] Cloudflare DNS 자동 등록
- [x] MySQL DB 자동 생성
- [x] WordPress 자동 설치
- [x] cPanel 원클릭 접속

### 📊 관리 도구
- [x] 관리자 패널
- [x] 서비스 모니터링
- [x] 자동정지엔진
- [x] 공지사항 시스템

---

## 🔜 향후 개발 계획

### 🚀 우선순위 높음
- [ ] 정기 결제 시스템 (구독형)
- [ ] 사용자 대시보드 개선
- [ ] 서비스 사용량 통계
- [ ] 모바일 반응형 최적화

### 📈 비즈니스 로직
- [ ] 멀티 서버 로드밸런싱
- [ ] 자동 백업 시스템
- [ ] 보안 모니터링
- [ ] API 문서화

### 🎨 사용자 경험
- [ ] 실시간 알림 시스템
- [ ] 사용자 가이드 페이지
- [ ] 고객 지원 채팅
- [ ] 다국어 지원

---

## 📋 주요 특징

### 🎯 완전 자동화
- 회원가입부터 서버 배포까지 **무인 자동화**
- 결제, 환불, 서비스 생명주기 **전체 자동 관리**

### 🛡 안정성
- **에러 로깅 시스템** (`error_logs` 테이블)
- **실패 처리 메커니즘** (WHM, DNS, DB 작업)
- **자동 복구 시스템**

### 📊 확장성
- **서버 풀 시스템**으로 무제한 확장 가능
- **모듈화된 구조**로 기능 추가 용이
- **API 기반 설계**로 외부 연동 간편

### 💰 수익성
- **SaaS 모델**로 안정적 수익 구조
- **자동화**로 운영비용 최소화
- **확장성**으로 매출 성장 가능

이 프로젝트는 현재 **상용 서비스 가능 수준**까지 개발이 완료되었으며, 실제 고객 서비스를 위한 **최종 테스트 및 배포 단계**에 있습니다.