<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function (\Illuminate\Routing\Router $router) {
            $router->middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // ✅ Define the "api" rate limiter here
            \Illuminate\Support\Facades\RateLimiter::for('api', function (\Illuminate\Http\Request $request) {
                return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by(
                    optional($request->user())->id ?: $request->ip()
                );
            });
        },
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        $middleware->api([
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

