<?php

namespace Modules\Generic\Http\Middleware;

use App\Exceptions\ApplicationClosed;
use Modules\Generic\Models\City;
use Modules\Generic\Models\District;
use Modules\Generic\Models\Setting;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class UnderMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!request()->is('operate') && !request()->is('operate/*')) {

            $setting = Cache::store('file')->get('mainSettings');
            if (!$setting) {
                $setting = Setting::first();
                Cache::store('file')->put('mainSettings',$setting, 600 );
            }
            $cities = Cache::store('file')->get('cities');
            if (!$cities) {
                $cities = City::get();
                Cache::store('file')->put('cities',$cities, 600 );
            }
            $districts = Cache::store('file')->get('districts');
            if (!$districts) {
                $districts = District::get();
                Cache::store('file')->put('districts',$districts, 600 );
            }
            // $under_maintenance = Setting::first()->value('under_maintenance');
            $under_maintenance = $setting->under_maintenance;
            if ($under_maintenance) {
                throw new ApplicationClosed();
            }
            View::share('mainSettings', $setting);
            View::share('cities', $cities);
            View::share('districts', $districts);
        }
        return $next($request);
    }

}
