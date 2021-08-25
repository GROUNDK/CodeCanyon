<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotCounterManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'counterManager')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('counterManager.login');
        }

        return $next($request);
    }
}
