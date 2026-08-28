<?php

use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'locale' => SetLocale::class,
        ]);

        $middleware->trustHosts(
            at: function (): array {
                $productionHosts = array_map(
                    static fn (string $host): string => '^'.preg_quote($host, '/').'$',
                    config('maatatelier.production_hosts', []),
                );

                if (! app()->isProduction()) {
                    $productionHosts = [...$productionHosts, '^localhost$', '^127\.0\.0\.1$'];
                }

                return $productionHosts;
            },
            subdomains: false,
        );
        $middleware->append(SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
