<?php

namespace App\Checkout\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Shared\Traits\JsonRespondController;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    use JsonRespondController;

    /**
     * Test용!!
     * @param string $orderId
     * @return JsonResponse
     */
    public function showForTEST(string $orderId): JsonResponse
    {
        return $this->respondOk(['order_id' => 1]);
    }
}
