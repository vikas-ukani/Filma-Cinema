<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'paytm-callback',
        '/cashfree/success',
        'rave/callback',
        '/wallet/success/using/paytm',
        'checkout/with/method/wallet',
        '/paytabs/callback',
    ];
}
