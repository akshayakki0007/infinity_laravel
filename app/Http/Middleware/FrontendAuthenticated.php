<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use Closure;

## Models
use App\Models\CategoryModel;
use App\Models\SiteSettingModel;

class FrontendAuthenticated
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
        $setting = SiteSettingModel::find('1');
        $arrCategoryObj = CategoryModel::where('status','0')->orderBy('id','asc')->get(['id','name','slug']);

        view()->share('arrSetting', $setting);
        view()->share('arrCategoryObj', $arrCategoryObj);

        $request->attributes->add([
            'arrSetting' => $setting,
            'arrCategoryObj' => $arrCategoryObj
        ]);

        return $next($request);
    }
}
