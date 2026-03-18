# SaaSling

Laravel 12 기반 SaaS 템플릿. Premium 구독 모델을 Clean Architecture로 구현한다.

## 기술 스택

- **Backend** Laravel 12, PHP 8.2+
- **Database** SQLite (개발), MySQL (운영)
- **Frontend** Vite 6, TailwindCSS 4
- **결제** Stripe Billing
- **테스트** PHPUnit 11

## 아키텍처

Clean Architecture 기반. 의존성 방향:

```
Presentation → Application → Domain ← Infrastructure
```

- **Domain** 순수 PHP. 비즈니스 규칙만. Laravel/Stripe 모름.
- **Application** UseCase. Domain을 조합해서 흐름을 만듦.
- **Infrastructure** DB, Stripe 등 외부 연동.
- **Presentation** REST API. Controller, Request, Resource.
- **Shared** 공통 유틸. Traits, Exceptions.

## 디렉토리 구조

```
app/
├── Builders/                       # Custom Eloquent Query Builder
│   └── SubscriptionBuilder.php
├── Models/                         # Eloquent 모델 (Infrastructure에서 사용)
│   ├── User.php
│   └── Subscription.php
├── Rules/                          # Custom Validation Rules
│   ├── ValidPlanId.php
│   └── NotAlreadySubscribed.php
├── Shared/                         # 공통 유틸
│   ├── Exceptions/                 # DomainException, NotFoundException 등
│   └── Traits/                     # Singleton 등
│
└── Subscription/                   # Premium 구독 도메인
    ├── Domain/
    │   ├── Entities/               # Subscription
    │   ├── ValueObjects/           # Plan, SubscriptionStatus
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
        └── Resources/              # SubscriptionResource

tests/
├── Stubs/                          # 테스트 전용 가짜 구현체
│   ├── InMemorySubscriptionRepository.php
│   └── FakePaymentGateway.php
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
