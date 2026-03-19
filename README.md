# SaaSling

Laravel 12 기반 SaaS 템플릿. Premium 구독 모델과 체크아웃을 Clean Architecture로 구현한다.

## 기술 스택

- **Backend** Laravel 12, PHP 8.2+
- **Database** SQLite (개발), MySQL (운영)
- **Frontend** Vite 6, TailwindCSS 4
- **인증** Laravel Sanctum
- **결제** Spring Boot 결제 서비스 (HTTP 통신)
- **테스트** PHPUnit 11

## 아키텍처

Clean Architecture 기반. 의존성 방향:

```
Presentation → Application → Domain ← Infrastructure
```

- **Domain** 순수 PHP. 비즈니스 규칙만. Laravel/외부 서비스 모름.
- **Application** UseCase. Domain을 조합해서 흐름을 만듦.
- **Infrastructure** DB, 외부 API 등 연동.
- **Presentation** REST API. Controller, Request, Resource.
- **Shared** 공통 유틸. Traits, Exceptions.

### 서비스 분리 구조

```
Laravel (체크아웃, 구독, 웹훅 수신)
    │
    │ HTTP
    ▼
Spring Boot (결제 처리, PG사 통신)
    │
    │ 웹훅
    ▼
Laravel /api/webhook/payment (주문 상태 업데이트)
```

## 디렉토리 구조

```
app/
├── Builders/                       # Custom Eloquent Query Builder
│   └── SubscriptionBuilder.php
├── Models/                         # Eloquent 모델 (Infrastructure에서 사용)
│   ├── Account.php
│   ├── User.php
│   └── Subscription.php
├── Rules/                          # Custom Validation Rules
│   ├── ValidPlanId.php
│   └── NotAlreadySubscribed.php
├── Shared/                         # 공통 유틸
│   ├── Exceptions/                 # DomainException, NotFoundException 등
│   └── Traits/                     # Singleton, HasTenant
│
├── Checkout/                       # 체크아웃 도메인
│   ├── Domain/
│   │   ├── Entities/               # Order, OrderItem
│   │   ├── ValueObjects/           # Money, OrderStatus
│   │   └── Repositories/           # OrderRepositoryInterface
│   ├── Application/
│   │   ├── Ports/                  # PaymentServiceInterface (Spring Boot)
│   │   └── UseCases/
│   │       ├── CreateOrder/        # 주문 생성
│   │       └── HandlePayment/      # 웹훅 처리 (결제 완료/실패)
│   ├── Infrastructure/
│   │   ├── Gateways/               # SpringPaymentGateway
│   │   └── Repositories/           # EloquentOrderRepository
│   ├── Presentation/
│   │   ├── Controllers/            # CheckoutController, WebhookController
│   │   ├── Requests/               # CreateOrderRequest
│   │   └── Resources/              # OrderResource
│   └── Providers/                  # CheckoutServiceProvider
│
└── Subscription/                   # Premium 구독 도메인
    ├── Domain/
    │   ├── Entities/               # Subscription
    │   ├── ValueObjects/           # Plan, SubscriptionStatus
    │   └── Repositories/           # SubscriptionRepositoryInterface
    ├── Application/
    │   ├── Ports/                  # PaymentGatewayInterface
    │   └── UseCases/
    │       ├── Subscribe/          # 구독 시작
    │       ├── Cancel/             # 구독 취소
    │       └── Renew/              # 구독 갱신
    ├── Infrastructure/
    │   ├── Gateways/               # StripeSubscriptionGateway
    │   └── Repositories/           # EloquentSubscriptionRepository
    ├── Presentation/
    │   ├── Controllers/            # SubscriptionController
    │   ├── Requests/               # SubscribeRequest
    │   └── Resources/              # SubscriptionResource
    └── Providers/                  # SubscriptionServiceProvider

tests/
├── Stubs/                          # 테스트 전용 가짜 구현체
│   ├── InMemorySubscriptionRepository.php
│   └── FakePaymentGateway.php
├── Unit/Subscription/
│   ├── Domain/                     # 엔티티 비즈니스 규칙 테스트
│   └── Application/                # UseCase 테스트 (DB/외부 서비스 없이)
├── Feature/Subscription/           # HTTP API 엔드포인트 테스트
└── TestCase.php                    # 공통 헬퍼 (createAccount, createUser 등)
```

## 테스트 전략

```
Unit 테스트    — DB도 외부 서비스도 없이 순수 PHP 로직만 검증
Feature 테스트 — HTTP 레벨 검증, Fake 구현체로 외부 의존성 제거
```

## API

```
# Checkout
POST   /api/checkout                   주문 생성 + Spring Boot에 결제 요청
POST   /api/webhook/payment            결제 완료/실패 수신 (인증 없음)

# Subscription
POST   /api/subscriptions              구독 시작
DELETE /api/subscriptions/{id}         구독 취소
POST   /api/subscriptions/{id}/renew   구독 갱신
```

## 멀티테넌시

Account 기반. 모든 데이터는 `account_id`로 격리된다.

```
Account (회사/조직)
└── Users
    └── Subscriptions  ← HasTenant 트레이트로 자동 필터링
```

## 환경 설정

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
npm install && npm run dev
```

`.env`에 추가:

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

PAYMENT_SERVICE_URL=http://localhost:8080  # Spring Boot 결제 서비스
```

## 테스트 실행

```bash
composer test
```
