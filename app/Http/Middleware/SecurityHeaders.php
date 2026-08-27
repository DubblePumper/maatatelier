<?php

namespace App\Http\Middleware;

use App\Support\SiteContext;
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
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        if ($request->routeIs('quote_requests.*')) {
            $response->headers->set('Cache-Control', 'no-store, private');
        }

        if ($request->routeIs('home', 'maatwerk', 'werkwijze', 'inspiratie', 'prijzen', 'about', 'contact', 'privacy', 'cookies', 'accessibility')) {
            $response->headers->remove('Set-Cookie');
        }

        if (SiteContext::isProductionRequest($request)) {
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

            if ($request->isSecure()) {
                $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            }
        }

        return $response;
    }
}
