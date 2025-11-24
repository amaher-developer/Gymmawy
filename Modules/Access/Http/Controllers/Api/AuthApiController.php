<?php

namespace Modules\Access\Http\Controllers\Api;

use App\Http\Requests;
use Modules\Access\Http\Resources\UserResource;
use Modules\Access\Models\User;
use Modules\Generic\Http\Controllers\Api\GenericApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthApiController extends GenericApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(Request $request)
    {
        $data = [];
        $data['facebook_id'] = $facebook_id = request('facebook_id');
        $data['google_id'] = $google_id = request('google_id');
        $data['twitter_id'] = $twitter_id = request('twitter_id');
        $data['instagram_id'] = $instagram_id = request('instagram_id');
        $data['apple_id'] = $apple_id = request('apple_id');
        $data['guest'] = request('guest');
        $data['password'] = request('password');
        $data['name'] = request('name');
        $data['phone'] = request('phone');
        $data['email'] = $email = request('email');
        $data['image'] = request('image');


        $data2['device_type'] = request('device_type');

        if($data['image']){
            $url = ($data['image']);
            $contents = file_get_contents($url);
            $name = substr($url, strrpos($url, '/') + 1);
            file_put_contents(public_path('uploads/users/'.$name), $contents);
            $data['image'] = $name;
        }
        if (!$this->validateApiRequest([ 'device_id', 'device_type', 'grant_type', 'client_id', 'client_secret'])) return $this->response;

        if(!$data['guest'] && !$data['facebook_id'] && !$data['google_id']  && !$data['twitter_id']  && !$data['instagram_id']  && !$data['apple_id']  ){
            return $this->falseResponse(trans('auth.invalid_login'));
        }
        if (!in_array($data2['device_type'], [0, 1])) {
            return $this->falseResponse(trans('auth.invalid_login'));
        }
        $credentials = '';
        if (!empty($data['phone'])) {
            $credentials = 'phone';
        }
        if (!empty($data['facebook_id'])) {
            $credentials = 'facebook_id';
        }
        if (!empty($data['google_id'])) {
            $credentials = 'google_id';
        }
        if (!empty($data['twitter_id'])) {
            $credentials = 'twitter_id';
        }
        if (!empty($data['instagram_id'])) {
            $credentials = 'instagram_id';
        }
        if (!empty($data['apple_id'])) {
            $credentials = 'apple_id';
        }
        if(!empty($data['email'])){
            $credentials = 'email';
        }
        if (!empty($data['guest'])) {
            $data['name'] = 'guest';
        }

        if(!$data['email']){
            $data['email'] = 'demo_'.time().'@gymmawy.com';
        }

        $user = [];
        if($credentials){
            if (!$this->validateApiRequest([ 'name'])) return $this->response;
            $user = User::where([$credentials => $$credentials])->first();
        }
        if (!$user) {
            $data = array_filter($data);
            $user = User::create($data);
            $this->return['user'] = new UserResource($user);
        }else{
            $data = array_filter($data);
            $user->update($data);
            $this->return['user'] = new UserResource($user);
        }

        $this->return['api_token'] = $user->createToken($request->grant_type)->accessToken;

        $this->user_id = $user->id;
        $this->api_user = $user;
        if(@request('device_token')) $this->updatePushToken();

        return $this->successResponse();
    }

    public function register(Request $request)
    {
        if (!$this->validateApiRequest(['name', 'email', 'phone', 'password', 'device_id', 'device_type'])) return $this->response;
        $facebook_id = @$request->input('facebook_id');
        $google_id = @$request->input('google_id');

        if (($facebook_id) || ($google_id)) {
            $social_id = $google_id;
            $social_type = 'google_id';
            if ($facebook_id) {
                $social_id = $facebook_id;
                $social_type = 'facebook_id';
            }

            $social_user = User::where([
                ['email', $request->input('email')],
                ['phone', $request->input('phone')]
            ])->first();

            if ($social_user) {
                $user = (Auth::attempt(['phone' => $request->input('phone'), 'password' => $request->input('password')])) ? Auth::getLastAttempted() : FALSE;
                if (!$user) {
                    return $this->falseResponse(trans('auth.invalid_login'));
                }
                $user->$social_type = $social_id;
                $user->save();
                $this->user_id = $user->id;
            }
        }

        $is_exists = sizeof(User::where('phone', request()->get('phone'))->get());
        $email_exists = User::whereEmail(request('email'))->first();

        if ($is_exists || $email_exists) {
            return $this->falseResponse(trans('auth.failed'));

        } else {
            $inputs = $request->only(['name', 'email', 'phone', 'password']);
            $user = User::create($inputs);
            $this->return['api_token'] = $user->createToken($request->grant_type)->accessToken;
            $this->user_id = $user->id;
        }

        return $this->successResponse();
    }

    public function update_profile(Request $request)
    {
        if (!$this->validateApiRequest()) return $this->response;

        $user = User::find($this->api_user->id);
        if (!empty($user)) {
            $inputs = $inputs_ = $request->only(['name', 'email', 'phone']);
            foreach ($inputs_ as $key => $input) {
                if (empty($input))
                    unset($inputs[$key]);
            }

            if(@$inputs['email']){
                $userCheck = User::where('email', $inputs['email'])->where('id', '!=', $user->id)->first();
                if($userCheck){
                    return $this->falseResponse(trans('auth.duplicate_email'));
                }
            }

            $user->update($inputs);
            $user->save();
            $this->return['user'] = new UserResource($user);
            return $this->successResponse();
        } else {
            return $this->falseResponse(trans('auth.failed'));
        }

    }
    public function myUser()
    {
        if (!$this->validateApiRequest()) return $this->response;

        $user = $this->api_user;
        if (!empty($user)) {
            $this->return['user'] = $user ? new UserResource($user) : '';
            return $this->successResponse();
        } else {
            return $this->falseResponse(trans('auth.failed'));
        }
    }

    public function reset_user_password()
    {
        $email = request()->get('email');
        $users = User::where('email', $email)->get();
        if (sizeof($users)) {
            $user = $users[0];

            $this->user_id = $user->id;
            $code = $this->getUserActivationToken();
            $user->reset_code = $code;
            $user->save();
            $this->get_user();
            $settings = $this->settings;
            Mail::send('emails.forgot_password_mail', array('confirm_link' => route('ApiResetUserPasswordCode', array('reset_code' => $code)), 'logo' => asset($settings->logo), 'title' => $settings->title), function ($message) use ($settings, $email) {
                $message->from($settings->support_mail, $settings->title . " - " . trans('global.resetting_password_page'));
                $message->to($email, $settings->title)->subject($settings->title . " - " . trans('global.resetting_password_page'));
            });
            return $this->successResponse();

        } else {
            return $this->falseResponse(trans('auth.invalid_login'));
        }
    }

    public function reset_user_password_code($reset_code)
    {
        $user = User::where('reset_code', $reset_code)->first();
        if ($user) {
            $new_password = Str::random(10) . $user->id;
            $user->password = bcrypt($new_password);
            $user->reset_code = '';
            $user->save();
            $email = $user->email;
//            $settings = $this->settings;
            Mail::send('emails.forgot_password_confirm_code', array('confirm_code' => $new_password, 'logo' => asset($settings->logo), 'title' => $settings->title), function ($message) use ($settings, $email) {
                $message->from($settings->support_mail, $settings->title . " - " . trans('global.resetting_password_page'));
                $message->to($email, $settings->title)->subject($settings->title . " - " . trans('global.resetting_password_page'));
            });
            echo trans('global.password_sent_to_mail');
        }

    }

}
