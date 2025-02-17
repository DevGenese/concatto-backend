<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * Alias of VerifyCsrfToken for consistency.
 */
class ValidateCsrfToken extends Middleware
{
    protected $except = [
        "/api/*",
        "/login",
    ];
}
