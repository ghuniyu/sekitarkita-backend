<?php

namespace App\Http\Middleware;

use Closure;


class PartnerMiddleware extends \Laravel\Nova\Http\Middleware\Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if ($request->getHost() !== env('APP_DOMAIN','sekitarkita.id') && $request->getHost() !== 'localhost') {
            return parent::handle($request, $next, ...$guards);
        }

        return $next($request);
    }
}
