# SaaSling

Laravel 12 기반 SaaS 템플릿. Premium 구독 모델을 Clean Architecture로 구현한다.

## 기술 스택

- **Backend** Laravel 12, PHP 8.2+
- **Database** SQLite (개발), MySQL (운영)
- **Frontend** Vite 6, TailwindCSS 4
- **인증** Laravel Sanctum
- **결제** Stripe Billing
- **테스트** PHPUnit 11

## 아키텍처

```
 HTTP 요청
    │
    ▼
┌─────────────────────────────────────────────────────────────┐
│  Presentation                                               │
│  Controller ──► Request (입력 검증) ──► Resource (응답 변환) │
└─────────────────────┬───────────────────────────────────────┘
                      │  UseCase 호출 (Input DTO)
                      ▼
┌─────────────────────────────────────────────────────────────┐
│  Application                                                │
│  UseCase ──► Domain 조합 ──► Port 인터페이스 호출           │
└──────────┬──────────────────────────────┬───────────────────┘
           │ 엔티티/값객체 사용            │ 인터페이스 의존
           ▼                              ▼
┌─────────────────────┐      ┌────────────────────────────────┐
│  Domain             │      │  Infrastructure                │
│  Entity             │      │  EloquentRepository            │
│  ValueObject        │◄─────│  StripeGateway                 │
│  Repository(interface)     │  RamseyUuidGenerator           │
└─────────────────────┘      └────────────────────────────────┘

           공통 유틸 (모든 레이어에서 사용)
┌─────────────────────────────────────────────────────────────┐
│  Shared                                                     │
│  Contracts (UuidGeneratorInterface)                         │
│  Traits    (HasTenant → 쿼리 자동 테넌트 필터링)             │
│  Exceptions (DomainException, NotFoundException 등)         │
└─────────────────────────────────────────────────────────────┘
```

Domain은 순수 PHP. Laravel도 Stripe도 모른다. 의존성은 항상 Domain을 향한다.

## 멀티테넌시

```
 HTTP 요청
    │
    ▼
[Sanctum 미들웨어] ── 인증 실패 ──► 401
    │ 인증 성공
    ▼
[HasTenant 글로벌 스코프]
    │  auth()->user()->account_id 로 WHERE 자동 추가
    ▼
 DB 쿼리 (account_id = ? 격리)

 인증 컨텍스트 없음 (Queue / CLI)
    └──► RuntimeException (fail-close)
         관리자 전체 조회 필요 시: Model::withoutTenant()
```

`Account` 1개 = 테넌트 1개. `User`와 `Subscription`은 모두 `account_id`로 소속 계정에 격리된다.

## 디렉토리 구조

```
app/
├── Builders/                       # Custom Eloquent Query Builder
│   └── SubscriptionBuilder.php
├── Models/                         # Eloquent 모델 (Infrastructure에서 사용)
│   ├── Account.php                 # 테넌트 단위
│   ├── User.php
│   └── Subscription.php
├── Rules/                          # Custom Validation Rules
│   └── ValidPlanId.php
├── Shared/                         # 공통 유틸
│   ├── Contracts/                  # UuidGeneratorInterface
│   ├── Exceptions/                 # DomainException, NotFoundException 등
│   ├── Infrastructure/             # RamseyUuidGenerator
│   └── Traits/                     # HasTenant, Singleton
│
├── Checkout/                       # 결제 주문 도메인
│   └── Application/
│       └── UseCases/CreateOrder/   # Input, Output, UseCase
│
└── Subscription/                   # Premium 구독 도메인
    ├── Domain/
    │   ├── Entities/               # Subscription
    │   ├── ValueObjects/           # Plan, PlanInterval, SubscriptionStatus
    │   └── Repositories/           # SubscriptionRepositoryInterface
    ├── Application/
    │   ├── Ports/                  # PaymentGatewayInterface
    │   └── UseCases/
    │       ├── Subscribe/          # Input, Output, UseCase
    │       ├── Cancel/             # Input, Output, UseCase
    │       └── Renew/              # Input, Output, UseCase
    ├── Infrastructure/
    │   ├── Gateways/               # StripeSubscriptionGateway
    │   └── Repositories/           # EloquentSubscriptionRepository
    └── Presentation/
        ├── Controllers/            # SubscriptionController
        ├── Requests/               # SubscribeRequest
        ├── Resources/              # SubscriptionResource
        └── Rules/                  # NotAlreadySubscribed

tests/
├── Stubs/                          # 테스트 전용 가짜 구현체
│   ├── InMemorySubscriptionRepository.php
│   ├── FakePaymentGateway.php
│   └── FakeUuidGenerator.php
├── Unit/Subscription/
│   ├── Domain/                     # 엔티티 비즈니스 규칙 테스트
│   └── Application/                # UseCase 테스트 (DB/Stripe 없이)
├── Feature/Subscription/           # HTTP API 엔드포인트 테스트
└── TestCase.php                    # 공통 헬퍼 (makePlan, makeSubscription 등)
```

## 테스트 전략

```
Unit 테스트   — DB도 Stripe도 없이 순수 PHP 로직만 검증
Feature 테스트 — HTTP 레벨 검증, Fake 구현체로 외부 의존성 제거
```

Clean Architecture의 핵심 장점: Domain/UseCase는 외부 의존성 없이 테스트 가능.

## API

```
POST   /api/subscriptions              구독 시작
DELETE /api/subscriptions/{id}         구독 취소
POST   /api/subscriptions/{id}/renew   구독 갱신
```

## 환경 설정

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
npm install && npm run dev
```

`.env`에 Stripe 키 추가:

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

## 테스트 실행

```bash
composer test
```
