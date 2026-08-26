<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), browsing-topics=()');
        $response->headers->set('X-Frame-Options', 'DENY');

        if ($request->routeIs('quote_requests.*')) {
            $response->headers->set('Cache-Control', 'no-store, private');
        }

        if (app()->isProduction()) {
            $response->headers->set('Content-Security-Policy', implode('; ', [
                "default-src 'self'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'none'",
                "object-src 'none'",
                "script-src 'self' https://www.googletagmanager.com",
                "style-src 'self' 'unsafe-inline'",
                "font-src 'self'",
                "img-src 'self' data: https://*.google-analytics.com https://www.googletagmanager.com",
                "connect-src 'self' https://*.google-analytics.com https://*.analytics.google.com https://www.googletagmanager.com",
                'upgrade-insecure-requests',
            ]));
        }

        return $response;
    }
}
