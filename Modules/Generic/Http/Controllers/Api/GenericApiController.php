<?php

namespace Modules\Generic\Http\Controllers\Api;
use Modules\Addon\Http\Resources\CalorieCategoryResource;
use Modules\Addon\Models\CalorieCategory;
use Modules\Article\Http\Resources\ArticleResource;
use Modules\Article\Models\Article;
use Modules\Banner\Http\Resources\BannerResource;
use Modules\Banner\Models\Banner;
use Modules\Generic\Classes\Constants;
use Modules\Generic\Http\Requests\ContactRequest;
use Modules\Generic\Http\Resources\CityDistrictResource;
use Modules\Generic\Http\Resources\FavoriteGymResource;
use Modules\Generic\Http\Resources\FavoriteTrainerResource;
use Modules\Generic\Http\Resources\NotificationResource;
use Modules\Generic\Http\Resources\SettingResource;
use Modules\Generic\Models\City;
use Modules\Generic\Models\Contact;
use Modules\Generic\Models\Setting;
use Modules\Gym\Http\Resources\GymResource;
use Modules\Gym\Models\Gym;
use Modules\Gym\Models\GymFavorite;
use Modules\Notification\Http\Controllers\Api\FirebaseApiController;
use Modules\Notification\Models\PushNotification;
use Modules\Trainer\Http\Resources\TrainerResource;
use Modules\Trainer\Models\Trainer;
use Modules\Trainer\Models\TrainerFavorite;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;
use Modules\Access\Models\User;
use Modules\Generic\Http\Controllers\GenericController;
use Modules\Generic\Repositories\SettingRepository;
use Modules\Notification\Http\Controllers\Admin\OneSignalController;
use Modules\Notification\Models\Push_tokens;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GenericApiController extends GenericController
{

    public $return = [];
    public $user_id;
    public $api_user;
    public $limit;
    public $lang;
    public $device_type;
    public $device_token;
    public $response;
    private $SettingRepository;


    public function __construct()
    {
        parent::__construct();
        $this->device_type = request('device_type');
        $this->device_token = request('device_token');
        $lang = \request('lang') ? \request('lang') : env('DEFAULT_LANG');
        $this->lang = isset($lang) && in_array($lang, explode(',', env('SYSTEM_LANG'))) ? $lang : env('DEFAULT_LANG');

        app()->setLocale($this->lang);
//        $this->user_id = request()->get('user_id');
        $this->api_user = Auth::guard('api')->user();
        $this->limit = 20;
        $this->SettingRepository = new SettingRepository(new Application);
//        $this->CountryRepository=new CountryRepository(new Application);
    }


    public function splash()
    {
        $this->successResponse();
        $this->get_settings();
//        $this->get_version();
//        $this->get_cities_and_districts();
//        $record = Push_tokens::whereToken($this->device_token)->first();
//        $this->return['one_signal_token'] = '';
//        if (!$record) {
//            $oneSignalResponse = OneSignalController::addUserToOneSignal($this->device_token, $this->device_type);
//            if (@$oneSignalResponse->success) {
//                Push_tokens::create([
//                    'device_type' => $this->device_type,
//                    'token' => $this->device_token,
////                    'user_id' => $this->user_id,
//                    'one_signal_token' => $oneSignalResponse->id
//                ]);
//
//                $this->return['one_signal_token'] = $oneSignalResponse->id;
//            }
//        }


        return $this->return;
    }
    private function banner($lang){
        $banners = Banner::select('id', 'title', 'image', 'lang', 'category_id', 'gym_id', 'url', 'phone', 'date_from', 'date_to');
        $banners->where('lang', $lang);
        $banners->where('type', Constants::BannerHomeType);
        $banners->whereDate('date_from','<=', Carbon::now())->whereDate('date_to','>=', Carbon::now());
        $banner = $banners->orderBy(DB::raw('RAND()'))->get();
        return $banner ?  BannerResource::collection($banner) : '';
    }
    public function home()
    {
        $lang = request('lang');
        $lang = isset($lang) && in_array($lang, explode(',', env('SYSTEM_LANG'))) ? $lang : env('DEFAULT_LANG');
        if(!$this->validateApiRequest())
            return $this->response;

//        $dir = ("uploads/mobile_sliders/*.jpg");
//        $this->return['sliders'] = array_map(function ($image){
//                                        return [
//                                                "id" => 1,
//                                                "image"=> asset($image),
//                                                "title"=> "test",
//                                                "phone"=> "01002509905",
//                                                "url"=> "https://google.com",
//                                                "gym_id"=> "1",
//                                                "category_id"=> "1"
//                                            ];
//                                    }, glob( $dir ));

        $this->return['sliders'] = $this->banner($lang);

        $this->return['calorie_categories'] = CalorieCategoryResource::collection(CalorieCategory::select('id', 'name_en', 'name_ar', 'image')->orderBy(DB::raw('RAND()'))->limit(4)->get());
        $this->return['articles'] = ArticleResource::collection(Article::select('id', 'title', 'description', 'language', 'image', 'published', 'views')->where('language', $lang)->where('for_mobile', true)->orderBy('id', 'desc')->limit(2)->get());

        $gyms = Gym::select('id', 'district_id', 'gym_brand_id')->with(['district.city', 'gym_brand' => function($q){
            $q->select('id', 'name_en', 'name_ar', 'main_phone', 'logo');
        }])->where('featured', true)->where('published', true)->orderBy(DB::raw('RAND()'))->limit(4)->get();

        $this->return['gyms'] = $gyms ? GymResource::collection($gyms) : [];

        $trainers = Trainer::with(['categories', 'city'])->where('published', true)->limit('views', '>' ,'100')->orderBy(DB::raw('RAND()'))->limit(6)->get();
        $this->return['trainers'] = $trainers ? TrainerResource::collection($trainers) : [];

//        $this->get_version();
        if(@request('device_token')) $this->updatePushToken();
        return $this->successResponse();
    }

    public function get_user()
    {
        $this->return['user'] = $user = User::whereId($this->api_user->id)->first();
        if (!$this->return['user'])
            $this->return['user'] = new User();
    }

    public function get_settings()
    {
        $this->return['settings'] = new SettingResource($this->SettingRepository->select( 'about_en', 'about_ar', 'terms_en', 'terms_ar', 'phone','support_email','facebook', 'twitter', 'instagram', 'ios_version', 'android_version')->first());

        return $this->return;
    }
    public function get_cities_and_districts()
    {
        $cities = City::with('district')->get();
        $this->return['cities'] = CityDistrictResource::collection($cities);

        return $this->return;
    }
    public function get_version()
    {
        $setting = $this->SettingRepository->select('android_version', 'ios_version')->first();
        $this->return['ios_version'] = $setting->ios_version;
        $this->return['android_version'] = $setting->android_version;

        return $this->return;
    }



    public function updatePushToken()
    {
        $this->validateApiRequest(['device_token']);

        if(in_array(request('device_type'), [Constants::ANDROID, Constants::IOS]))
            $device_type = request('device_type');
        else
            $device_type = Constants::ANDROID;

        $device_token = request('device_token');

        $record = Push_tokens::where('token', $device_token)->first();
        if (!$record) {
            Push_tokens::create([
                'device_type' => $device_type,
                'token' => $device_token,
                'user_id' => $this->api_user->id,
            ]);
            (new FirebaseApiController())->addTokenToTopic($device_token, $device_type);
        } else {
            $record->update(['user_id' => $this->api_user->id]);
        }
        $this->successResponse();
        return $this->response;
    }

    public function contact(Request $request)
    {
        if (!$this->validateApiRequest(['name', 'email', 'phone', 'message'])) return $this->response;

        if (!empty($this->api_user->id)) {
            $inputs = $inputs_ = $request->only(['name', 'email', 'phone', 'message']);
            foreach ($inputs_ as $key => $input) {
                if (empty($input))
                    unset($inputs[$key]);
            }
            $data = array(
                'name' => $request->name
            , 'phone' => $request->phone
            , 'email' => $request->email
            , 'msg' => $request->message
            );
            $setting = Setting::first();
            Mail::send('emails.contact_us', $data, function ($message) use ($data, $setting) {
                $message->from($data['email'], $data['name']);
                $message->to($setting->support_email, trans('global.contact_us'))->subject(trans('global.contact_us'));
            });
            $data['type'] = 2;
            Contact::create($data);

            $this->return['message'] = trans('global.contact_add_successfully');;
            return $this->successResponse();
        } else {
            return $this->falseResponse(trans('auth.failed'));
        }

    }

    public function myFavorites(){
        if (!empty($this->api_user->id)) {

            $gyms = GymFavorite::with(['gym.gym_brand', 'gym.categories', 'gym.district.city'])->where('user_id', $this->api_user->id)->get();
            $trainers = TrainerFavorite::with(['trainer.categories', 'trainer.city'])->where('user_id', $this->api_user->id)->get();
            $this->return['gyms'] = $gyms ? FavoriteGymResource::collection($gyms) : [];
            $this->return['trainers'] = $trainers ? FavoriteTrainerResource::collection($trainers) : [];

            return $this->successResponse();
        } else {
            return $this->falseResponse(trans('auth.failed'));
        }
    }

    public function myNotifications(){
        if (!empty($this->api_user->id)) {
            $notifications = PushNotification::where('user_id', $this->api_user->id)->orWhere('user_id', null)->orderBy('id', 'desc')->paginate($this->limit);
            $this->return['notifications'] = $notifications ? NotificationResource::collection($notifications) : [];
            return $this->successResponse();
        } else {
            return $this->falseResponse(trans('auth.failed'));
        }
    }

    public function logErrors(Request $request)
    {
        mail('eng.a7med.ma7er@gmail.com', 'Gymmawy ' . $request->subject, $request->body);
        $this->successResponse();
        return $this->return;
    }


    protected function requestHasUser($key = 'user_id', $action = '', $action_data = [])
    {
        if (!request($key)) {
            $this->return['error'] = 'missing user_id';
            $this->return['action'] = $action;
            $this->return['action_data'] = $action_data;

            return FALSE;
        }
        return TRUE;
    }




    protected function validateApiRequest($required = [], $action = '', $action_data = [])
    {
        $missing = [];
//        $required[] = 'lang';
//        $required[] = 'device_type';

        foreach ($required as $item) {
            $input = request($item);
            if ((!isset($input)) || $input == '') $missing[] = $item;
        }
        if ($missing) {
            $error = 'missing ' . implode(', ', $missing);
            $this->response= $this->falseResponse($error, $action , $action_data );
            return FALSE;
        }
        return TRUE;
    }

    public function falseResponse($error = '', $action = '', $action_data = [])
    {

//        $this->return['action'] = $action;
//        $this->return['action_data'] = $action_data;
        $this->return['error'] = $error;

//        return $this->response = response()->json($this->return)->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR, $error);
        return $this->response = response()->json($this->return)->setStatusCode(Response::HTTP_BAD_REQUEST, $error);

    }

    public function successResponse($action = '', $action_data = [])
    {
//        $this->return['error'] = '';
//        $this->return['action'] = $action;
//        $this->return['action_data'] = $action_data;
        if (request()->has('need_user') && request('need_user') == 1)
            $this->get_user();

        if (request()->has('need_settings') && request('need_settings') == 1)
            $this->get_settings();
        return $this->response = response()->json($this->return)->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);

    }


    public function returnPaginationData(&$pagination_result)
    {
        $next = ($pagination_result->currentPage() >= $pagination_result->lastPage()) ? -1 : ($pagination_result->currentPage());
        $pagination_result = $pagination_result->toArray()['data'];
        $this->return['page'] = $next;
    }
    public function getPaginateAttribute($records){
        $this->return['current_page'] = $records->currentPage();
        $this->return['next_page'] = $records->currentPage()+1;
        $this->return['pages_count'] = ceil(($records->total() / $this->limit));
    }

}
