<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use Closure;

## Models
use App\Models\ModulesModel;

class RedirectIfAuthenticatedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::check())
        {
            $arrModules = ModulesModel::where('status','0')->orderBy('id','asc')->get(['id','name','slug']);
            
            view()->share('arrModules', $arrModules);

            $request->attributes->add([
                'arrModules' => $arrModules
            ]);

            return $next($request);
        }

        return redirect('/admin/auth/login?'.Str::random('30'));
        //return $next($request);
    }
}
