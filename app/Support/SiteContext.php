<?php

namespace App\Support;

use Illuminate\Http\Request;

final class SiteContext
{
    public static function isProductionRequest(Request $request): bool
    {
        if (app()->isProduction()) {
            return true;
        }

        $productionHosts = array_map(
            static fn (string $host): string => strtolower($host),
            config('maatatelier.production_hosts', []),
        );

        return in_array(strtolower($request->getHost()), $productionHosts, true);
    }
}
