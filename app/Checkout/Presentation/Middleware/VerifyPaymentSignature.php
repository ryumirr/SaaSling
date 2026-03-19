<?php

namespace App\Checkout\Presentation\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPaymentSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-Payment-Signature');
        $secret    = config('services.payment.secret');
        $expected  = hash_hmac('sha256', $request->getContent(), $secret);

        if (!$signature || !hash_equals($expected, $signature)) {
            abort(403, 'Invalid payment signature');
        }

        return $next($request);
    }
}
