<?php

namespace App\Checkout\Domain\Repositories;

use App\Checkout\Domain\Entities\Order;

interface OrderRepositoryInterface
{
    public function findById(string $id): ?Order;
    public function save(Order $order): void;
}
