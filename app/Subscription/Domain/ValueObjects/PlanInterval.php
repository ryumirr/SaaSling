<?php

namespace App\Subscription\Domain\ValueObjects;

enum PlanInterval: string
{
    case Monthly = 'monthly';
    case Yearly  = 'yearly';
}
