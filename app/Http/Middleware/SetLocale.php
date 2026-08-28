<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $locale): Response
    {
        abort_unless(in_array($locale, ['nl', 'fr'], true), 404);

        $previousLocale = app()->getLocale();
        app()->setLocale($locale);

        try {
            $response = $next($request);
            $response->headers->set('Content-Language', $locale === 'fr' ? 'fr-BE' : 'nl-BE');

            return $response;
        } finally {
            app()->setLocale($previousLocale);
        }
    }
}
