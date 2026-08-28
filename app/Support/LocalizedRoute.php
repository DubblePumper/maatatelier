<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use InvalidArgumentException;

final class LocalizedRoute
{
    /**
     * Return the localized name for a route whose Dutch name is canonical.
     */
    public static function name(string $baseName, ?string $locale = null): string
    {
        $locale = self::normalizeLocale($locale ?? self::locale());
        $baseName = self::baseName($baseName) ?? $baseName;

        return $locale === 'fr' ? 'fr.'.$baseName : $baseName;
    }

    /**
     * Generate an absolute URL for the selected locale.
     *
     * @param  array<string, mixed>  $parameters
     */
    public static function url(string $baseName, array $parameters = [], ?string $locale = null): string
    {
        return route(self::name($baseName, $locale), $parameters);
    }

    /**
     * Determine whether the current route matches one of the locale-neutral names.
     */
    public static function isCurrent(string ...$baseNames): bool
    {
        $currentBaseName = self::baseName();

        if ($currentBaseName === null) {
            return false;
        }

        foreach ($baseNames as $baseName) {
            if (Str::is(self::baseName($baseName) ?? $baseName, $currentBaseName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate the equivalent URL in another locale, preserving route parameters.
     */
    public static function switchUrl(string $locale): string
    {
        $locale = self::normalizeLocale($locale);
        $baseName = self::baseName();

        if ($baseName !== null) {
            $localizedName = self::name($baseName, $locale);

            if (Route::has($localizedName)) {
                $currentRoute = request()->route();
                $parameters = [];

                foreach ($currentRoute?->parameterNames() ?? [] as $parameterName) {
                    if ($currentRoute->hasParameter($parameterName)) {
                        $parameters[$parameterName] = $currentRoute->parameter($parameterName);
                    }
                }

                return route($localizedName, $parameters);
            }
        }

        return self::url('home', locale: $locale);
    }

    /**
     * Strip the locale prefix from a route name.
     */
    public static function baseName(?string $routeName = null): ?string
    {
        $routeName ??= Route::currentRouteName();

        if ($routeName === null) {
            return null;
        }

        return Str::startsWith($routeName, 'fr.')
            ? Str::after($routeName, 'fr.')
            : $routeName;
    }

    /**
     * Return the site's normalized two-letter locale.
     */
    public static function locale(): string
    {
        return Str::startsWith(app()->getLocale(), 'fr') ? 'fr' : 'nl';
    }

    private static function normalizeLocale(string $locale): string
    {
        if (! in_array($locale, ['nl', 'fr'], true)) {
            throw new InvalidArgumentException("Unsupported locale [{$locale}].");
        }

        return $locale;
    }
}
