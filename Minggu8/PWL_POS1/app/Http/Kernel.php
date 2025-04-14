<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Middleware global
    protected $middleware = [
        // ...
        
    ];

    // Middleware group seperti 'web', 'api'
    protected $middlewareGroups = [
        'web' => [
            // ...
            
        ],

        'api' => [
            // ...
        ],
    ];

    // Middleware alias, termasuk middleware yang kamu buat
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'authorize' => \App\Http\Middleware\AuthorizeUser::class, // ← ini middleware buatan kamu
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        // ... lainnya
    ];
}
