<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;


// Middleware nhóm 'web'
use App\Http\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;


// Middleware tuyến (route middleware)
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;


class Kernel extends HttpKernel
{
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],
        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'admin' => IsAdmin::class,
        'guest' => RedirectIfAuthenticated::class,
        'verified' => EnsureEmailIsVerified::class,
    ];
}
