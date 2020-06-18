<?php

namespace App\Http\Middleware;

use Closure;

class AutomatedServicesAvailableMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty(config('automated_identification.enabled'))) {
            return redirect()->back();
        }
        return $next($request);
    }
}
