<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;

use Closure;
use Auth;

class IsAdmin
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
        if(Auth::check())
        {
            if ((Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin'  || Auth::user()->role == 'sales-admin' || Auth::user()->role == 'sales'))
            {
                return $next($request);
            }
            else
            {
                abort(404);
            }
        }

        return $next($request);
    }
}
