<?php

namespace App\Checkout\Application\Ports;

use App\Checkout\Domain\Entities\Order;

interface PaymentServiceInterface
{
    /**
     * Spring Boot 결제 서비스에 결제 요청.
     * 결제 URL 또는 트랜잭션 ID 반환.
     */
    public function requestPayment(Order $order): string;
}
