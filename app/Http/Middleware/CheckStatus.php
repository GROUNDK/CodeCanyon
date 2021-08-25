<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class CheckStatus
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
        if (Auth::guard('owner')->check()) {
            $user = auth()->guard('owner')->user();
            if ($user->status  && $user->ev && $user->sv) {
                return $next($request);
            } else {
                return redirect()->route('owner.authorization');
            }
        }

        return redirect()->route('owner.login');
    }
}
