<?php

namespace Modules\Generic\Http\Controllers\Front;
use App\Exceptions\ApplicationClosed;
use Modules\Article\Models\ArticleCategory;
use Modules\Generic\Models\City;
use Modules\Generic\Models\District;
use Modules\Gym\Models\GymBrand;
use Modules\Software\Models\GymMember;
use Modules\Software\Models\GymOrder;
use Modules\Software\Models\GymSubscription;
use Illuminate\Container\Container as Application;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Modules\Generic\Http\Controllers\GenericController;
use Modules\Generic\Models\Setting;

use Modules\Generic\Repositories\SettingRepository;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class GenericFrontController extends GenericController
{
    public $lang;
    public $user;
    public $current_gym_id;
    public $SettingRepository;
    public $mainSettings;
    public $cities;
    public $districts;
    public $limit;
    public $changeLang;
    public $request_array;

    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
        $this->changeLang = Cache::store('file')->get('changeLang');
        if($this->changeLang != request()->segment(1)){
            Cache::store('file')->clear();
            Cache::store('file')->put('changeLang', request()->segment(1), 600);
        }

        if (request()->segment(1) != 'ar' && request()->segment(1) != 'en') {
            $this->lang = 'ar';
            request()->session()->put('lang', 'ar');
            app()->setLocale(request()->session()->get('lang'));
        } else {
            request()->session()->put('lang', request()->segment(1));
            $this->lang = request()->segment(1);
        }
        $this->limit = 10;

        $this->mainSettings = Cache::store('file')->get('mainSettings');
        if (!$this->mainSettings) {
            $this->mainSettings = Setting::first();
            Cache::store('file')->put('mainSettings',$this->mainSettings, 600 );
        }
        $this->cities = Cache::store('file')->get('cities');
        if (!$this->cities) {
            $this->cities = City::get();
            Cache::store('file')->put('cities',$this->cities, 600 );
        }
        $this->districts = Cache::store('file')->get('districts');
        if (!$this->districts) {
            $this->districts = District::get();
            Cache::store('file')->put('districts',$this->districts, 600 );
        }
        View::share('mainSettings', $this->mainSettings);
        View::share('cities', $this->cities);
        View::share('districts', $this->districts);



//        Cache::remember('mostViews',60 * 24 * 30, function () {
//            return Cache::get('mostViews') ? null : Gym::with(['district.city'])->where('published', 1)->limit(2)->orderBy('views', 'desc')->get();
//        });
//        View::share('mostViews', Cache::get('mostViews'));

        View::share('mainSettings', $this->mainSettings);

        $this->user = @Auth::user();
        View::share('currentUser',$this->user);
        return $next($request);
        });


    }


    public function falseReturn($error_ar = '', $error_en = '')
    {
        return redirect()->back()->withErrors(['error' => ($this->lang == 'en' ? $error_en : $error_ar)]);
    }

    protected function validateFrontFields($required = [], $array_data = [])
    {

        $missing = [];
        foreach ($required as $item) {
            if (!key_exists($item, $array_data) || $array_data[$item] == '') $missing[] = $item;
        }
        if ($missing) {
//            foreach ($missing as &$var) {
//                $label = ItemTypeFieldsLabel::all[$this->lang][$var];
//                if ($label)
//                    $var = ItemTypeFieldsLabel::all[$this->lang][$var];
//            }
            return 'missing ' . implode(', ', $missing);
        }
        return TRUE;
    }
    protected function validateFrontRequest($required = [])
    {

        $missing = [];
//        $required[] = 'device_type';
        foreach ($required as $item) {
            $input = request($item);
            if ((!isset($input)) || $input == '') $missing[] = $item;
        }
        if ($missing) {
//            foreach ($missing as &$var) {
//                $label = ItemTypeFieldsLabel::all[$this->lang][$var];
//                if ($label)
//                    $var = ItemTypeFieldsLabel::all[$this->lang][$var];
//            }
            return 'missing ' . implode(', ', $missing);
        }
        return TRUE;


    }
}
