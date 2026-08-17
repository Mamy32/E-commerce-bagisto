<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply this to admin routes
        if ($request->is(config('app.admin_url') . '*') || $request->is(config('app.admin_url'))) {
            if (session()->has('admin_locale')) {
                app()->setLocale(session()->get('admin_locale'));
            }
        }

        return $next($request);
    }
}
