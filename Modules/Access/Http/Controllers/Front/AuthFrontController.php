<?php

namespace Modules\Access\Http\Controllers\Front;

use Modules\Access\Http\Requests\BrokerRequest;
use Modules\Access\Http\Requests\RegisterRequest;
use Modules\Access\Models\User;
use Modules\Access\Models\UserVisitedDistrict;
use Modules\Generic\Http\Controllers\Front\GenericFrontController;
use Modules\Generic\Http\enums\MailType;
use Modules\Generic\Models\Setting;
use Modules\Item\Http\enums\ItemStatus;
use Modules\Item\Http\enums\OfferStatus;
use Modules\Item\Models\Item;
use Modules\Item\Models\Offer;
use Modules\Location\Models\District;
use Modules\Mailchimp\Http\Controllers\Admin\MailchimpAdminController;
use Modules\Notification\Models\NewsletterSubscriber;
use Modules\SmsGateway\Http\Controllers\Admin\SMSGatewayAdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;

class AuthFrontController extends GenericFrontController
{
    private $social_types = ['facebook' => 'facebook_id', 'twitter' => 'twitter_id', 'google' => 'google_id'];

    public $mailChimpManager;
    /**
     * AuthFrontController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->mailChimpManager = new MailchimpAdminController();

        $this->middleware('guest', ['except' => ['logout', 'getLogout']]);
        $this->middleware('auth', ['only' => ['logout', 'editProfile', 'updateProfile']]);
    }

    public function getLogout()
    {
        $this->auth->logout();
        Session::flush();
        return redirect('/');
    }

    public function showRegistrationForm()
    {
        $user = \session('social_user') ? \session('social_user') : [];
        $is_exists = @User::Where('email', $user['email'])->first();
        if($is_exists){
            Auth::login($is_exists);
            \session()->forget('social_user');
            return redirect(route('dashboard'));
        }
        return view('access::Front.register', [
            'title' => trans('global.register'),
            'user' => $user
        ]);
    }

    /**
     * @param RegisterRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(RegisterRequest $request)
    {
        $social_user = \session('social_user');
        $user_arr = $request->except(['_token', 'newsletter_subscribe','password_confirmation']);
        $is_exists = User::Where('email', $user_arr['email'])->first();
        if ($is_exists && !$social_user) {
            return redirect()->back()->withErrors(['error' => trans('global.email_already_exists')]);
        } else {
            $token = Str::random(40) . time();
            $user_arr['register_token'] = $token;
            $user = User::create($user_arr);

            if($social_user && $social_user['email'] == $user_arr['email']){
                $user->activated = 1;
                $user->save();
                Auth::attempt(['email' => $user_arr['email'], 'password' => $user_arr['password']]);
            }

            if (request('newsletter_subscribe') == 1) {
                NewsletterSubscriber::firstOrCreate(['email' => $user_arr['email']]);
                $email = request('email');
                $mailChimpResponse = $this->mailChimpManager->AddToList($email, MailchimpAdminController::$newsletterListId);
            }
            // send email with password
            @sendMail('user_activation',$user->email, trans('global.new_register'), ['token' => $token, 'name' => $user->name]);

            \session()->forget('social_user');
            return redirect(route('thanksRegister'));
        }
    }



    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            //unset($_COOKIE['user_id']);
            //setcookie('user_id', $user['id'], time() + (86400 * 30) , "/");
            return redirect()->route('home');
        } else {
            return view('access::Front.login', ['title' => trans('global.login')]);

        }
    }


    public function login(Request $request)
    {
        $request->validate(['email' => 'required', 'password' => 'required']);
        $credentials = $request->only(['email', 'password']);
        $user = (Auth::attempt($credentials, true)) ? Auth::getLastAttempted() : FALSE;
        if (!$user) return redirect()->back()->withErrors(['error' => trans('auth.failed')]);
        if ($user->block == '1'){ Auth::logout(); return redirect()->back()->withErrors(['error' => trans('global.block_user_msg')]);}

//        if (count($user->roles) > 0)
//            return redirect('operate');
//        else
            return redirect('user');
    }

    public function redirectToProvider()
    {
        session(['social_provider' => request('provider')]);
        return Socialite::driver(request('provider'))->redirect();
    }



    public function showUser()
    {
        $title = trans('admin.my_info');
        $user = Auth::user();
        return view('access::Front.user.user_front_view', ['title' => $title, 'user' => $user]);
    }


    public function editUser()
    {
        $title = trans('admin.edit_my_info');
        $user = $this->user;
        return view('access::Front.user.user_front_form', ['title' => $title, 'user' => $user]);
    }

    public function updateUser(Request $request)
    {
        if (!empty($this->user)) {
            if ($this->user->phone == request('phone')) {
                $inputs = $inputs_  = $this->prepare_inputs($request->except(['_token', 'phone', 'password']));
                Auth::User()->update($inputs);
                sweet_alert()->success(trans('admin.done'), trans('admin.successfully_edited'));
                return redirect(route('showUserFront'));
            } else {

                $email_exist = User::where('email', request('email'))->value('email');
                if (!$email_exist) {
                    $inputs = $inputs_  = $this->prepare_inputs($request->except(['_token', 'email', 'password']));

//                    $inputs['verified'] = 0;
                    Auth::User()->update($inputs);
                    sweet_alert()->success(trans('admin.done'), trans('admin.successfully_edited'));
//                    return redirect()->back();
                    return redirect(route('showUserFront'));
                } else {
                    return $this->falseReturn('رقم التليفون موجود.', 'Mobile Already Exists.');
                }
            }
        } else {
            return $this->falseReturn('المستخدم غير موجود', 'User Not Found');
        }
    }

    public function handleProviderCallback()
    {
        if (request()->input('error')) {
            return redirect()->route('home')->withErrors(['error' => trans('global.access_denied')]);
        } else {
//            Socialite::driver(session('social_provider'))->stateless();
            $social_user = Socialite::driver(session('social_provider'))->stateless()->user();
            $social_type = $this->social_types[session('social_provider')];
            if ($user = User::where([$social_type => $social_user->id])->first()) {
                Auth::login($user);
                //unset($_COOKIE['user_id']);
                //setcookie('user_id', $user['id'], time() + (86400 * 30) , "/");
                request()->session()->remove('currentArea');
                return redirect()->route('home');
            }
            session(['social_user' => $social_user, 'social_type' => $social_type]);
            return redirect()->route('register');
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/');
    }

    public function showProfile()
    {
        return view('access::Front.show_profile');
    }


    public function editProfile()
    {
        return view('access::Front.edit_profile');
    }

    public function updateProfile(Request $request)
    {
        if (!empty($this->user)) {
            if ($this->user->phone == request('phone')) {
                $inputs = $inputs_ = $request->only(['password', 'name', 'phone']);
                foreach ($inputs_ as $key => $input) {
                    if (empty($input))
                        unset($inputs[$key]);
                }
                Auth::User()->update($inputs);
                request()->session()->flash('success', trans('global.profile_updated_successfully'));
                return redirect()->back();
            } else {

                $phone_exist = User::where('phone', request('phone'))->value('phone');

                if (!$phone_exist) {

                    $inputs = $inputs_ = $request->only(['password', 'email', 'name', 'phone']);
                    foreach ($inputs_ as $key => $input) {
                        if (empty($input))
                            unset($inputs[$key]);
                    }
                    /** @var Address $address */
                    $inputs['verified'] = 0;
                    Auth::User()->update($inputs);
                    Offer::where('user_id', $this->user->id)->whereIn('status', [OfferStatus::delivered, OfferStatus::seen])
                        ->update(['status' => OfferStatus::waiting_for_mobile_verification]);
                    Item::where('user_id', $this->user->id)->whereIn('status', [ItemStatus::not_approved, ItemStatus::approved])
                        ->update(['status' => ItemStatus::waiting_for_mobile_verification]);
                    request()->session()->flash('success', trans('global.profile_updated_successfully'));
                    return redirect()->back();
                } else {
                    return $this->falseReturn('رقم التليفون موجود.', 'Mobile Already Exists.');
                }
            }
        } else {
            return $this->falseReturn('المستخدم غير موجود', 'User Not Found');
        }
    }


    public function newsletterSubscribe()
    {
        $email = request('subscriber_email');
        $subscribe = request('subscribe') ? request('subscribe') : '1';
        NewsletterSubscriber::firstOrCreate(['email' => $email]);
        return '<div class="alert alert-success">'.trans('global.subscription_add_successfully').'</div>';
//        if ($subscribe == 1) {
//            NewsletterSubscriber::firstOrCreate(['email' => $email]);
//
//            $mailChimpResponse = $this->mailChimpManager->AddToList($email, MailchimpAdminController::$newsletterListId);
//            if ($mailChimpResponse['http_code'] != 200) {
//                $this->falseReturn(trans('global.unsuccessfully_send'), trans('global.unsuccessfully_send'));
//            }
//
//        } elseif ($subscribe == 0) {
//            $mailChimpResponse = $this->mailChimpManager->RemoveFromList($email, MailchimpAdminController::$newsletterListId);
//            if ($mailChimpResponse['http_code'] != 200) {
//                $this->falseReturn(trans('global.unsuccessfully_send'), trans('global.unsuccessfully_send'));
//            }
//            NewsletterSubscriber::where('email', $email)->delete();
//        }
//
//        return redirect()->back();

    }


    public function unSubscribeMailChimpList($list_id, $email)
    {
        try {
//            $email = request('email');
            if ($list_id == 'all') {
                $mailchimp_list_arr = District::pluck('mailchimp_list_id');
                $mailchimp_list_arr[] = MailchimpAdminController::$newsletterListId;
                $mailChimpResponse = $this->mailChimpManager->RemoveFromAllSubscribedList($email, $mailchimp_list_arr);
                if ($mailChimpResponse['http_code'] == 204) {
                    NewsletterSubscriber::where('email', $email)->delete();
                } else {
                    $this->falseReturn(trans('global.unsuccessfully_un_subscribed'), trans('global.unsuccessfully_un_subscribed'));

                }
            }
            if ($list_id == 'newsletter') {
                $mailChimpResponse = $this->mailChimpManager->RemoveFromList($email, MailchimpAdminController::$newsletterListId);
                if ($mailChimpResponse['http_code'] == 204) {
                    NewsletterSubscriber::where('email', $email)->delete();
                } else {
                    $this->falseReturn(trans('global.unsuccessfully_un_subscribed'), trans('global.unsuccessfully_un_subscribed'));
                }
            } else {
                $mailchimp_list_id = $list_id;
                $mailChimpResponse = $this->mailChimpManager->RemoveFromList($email, $mailchimp_list_id);
                if ($mailChimpResponse['http_code'] != 204) {
                    $this->falseReturn(trans('global.unsuccessfully_un_subscribed'), trans('global.unsuccessfully_un_subscribed'));
                }
            }
        } catch (\Exception $e) {
            $this->falseReturn(trans('global.unsuccessfully_un_subscribed'), trans('global.unsuccessfully_un_subscribed'));
        }
        return redirect(route('home'));
    }


    public function showVerificationPage()
    {
        $user = User::whereId($this->user->id)->first();
        if ($user) {
            if ($this->user->verified == 0) {
                return view('access::Front.verification');
            } else {
                return $this->falseReturn('الهاتف مفعل', 'Phone is verified');
            }
        } else {
            return $this->falseReturn('المستخدم غير موجود', 'User Not Found');

        }

    }


    public function sendPhoneVerificationCode()
    {
        $user = User::whereId($this->user->id)->first();

        if ($user && ($user->sms_send_num <  $this->mainSettings->max_sms_number)) {
            $generate_code = mt_rand(1000, 9999);
            $user->phone_verification_code = $generate_code;
            $user->sms_send_num++;
            $user->save();
            SMSGatewayAdminController::sentSms($user->phone, 'Welcome to '.env('APP_NAME_EN').' ' . $generate_code . ' is your security number');

            //return redirect()->back();
            return 'true';
        } else {
            //return $this->falseReturn('المستخدم غير موجود', 'User Not Found');
            return 'false';
        }

    }

    public function emailActivate()
    {
        $token = \request('code');
        $user = User::where('register_token', $token)->first();

        if (empty($user)) {
            return redirect()->to('/')
                ->with(['error' => 'Your activation code is either expired or invalid.']);
        }

        $user->update(['register_token' => null, 'activated' => 1]);
        Auth::login($user);
        return redirect()->route('dashboard')
            ->with(['success' => 'Congratulations! your account is now activated.']);
    }

    public function verifyPhone()
    {
        $phone_verification_code = request('phone_verification_code');
        $phone = $this->user->phone;
        $user = User::where('phone', $phone)->where('phone_verification_code', $phone_verification_code)->first();
        if ($user) {
            $user->phone_verification_code = null;
            $user->verified = 1;
            $user->save();
            return redirect(route('dashboard'));
//            return redirect(route('showProfile'));
        } else {
            return $this->falseReturn('كود تفعيل الموبيل غير موجود', 'Phone Verification Code Not Found');
        }


    }

    public function editUserUpdatePassword()
    {
        $title = trans('global.reset_password');
        return view('access::Front.user.user_front_update_password', ['title' => $title]);

    }

    public function updateUserUpdatePassword(User $user, Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ]);
        $input = $request->only(['password', 'old_password']);
        $input['user_id'] = Auth::id();
        $credentials['phone'] = $user->phone;
        $credentials['password'] = $input['old_password'];
        $user = (Auth::attempt($credentials)) ? Auth::getLastAttempted() : FALSE;

        if (!$user)
            return redirect()->back()->withErrors(['error' => 'wrong old password']);

        $user->update(['password' => $input['password']]);

        return redirect()->back();

    }

//    public function showUpdatePasswordForm()
//    {
//        $title = trans('global.reset_password');
//        return view('access::Front.update_password', ['title' => $title]);
//
//    }
//
//    public function updatePassword(User $user, Request $request)
//    {
//        $this->validate($request, [
//            'old_password' => 'required',
//            'password' => 'required|confirmed'
//        ]);
//        $input = $request->only(['password', 'old_password']);
//        $input['user_id'] = Auth::id();
//        $credentials['phone'] = $user->phone;
//        $credentials['password'] = $input['old_password'];
//        $user = (Auth::attempt($credentials)) ? Auth::getLastAttempted() : FALSE;
//
//        if (!$user)
//            return redirect()->back()->withErrors(['error' => 'wrong old password']);
//
//        $user->update(['password' => $input['password']]);
//
//        return redirect()->back();
//
//    }

//    public function sendResetPassword()
//    {
//        return view('auth.passwords.email');
//    }
//
//
//    public function resetPassword()
//    {
//        $phone = request('phone');
//        $user = User::where('phone', $phone)->first();
//        if (!$user)
//            return redirect()->back()->withErrors(['error' => 'Phone does not exists.']);
//
//        $password = str_random(8);
//
//        $user->password = $password;
//        $user->save();
//
//        // send email with password
//        sendMail('',$user->email,'',['user' => $user, 'password' => $password]);
//        request()->session()->flash('success', trans('global.password_sent_successfully'));
//        return redirect(route('login'));
//    }




    private function prepare_inputs($inputs)
    {
        $input_file = 'image';
        $uploaded='';

        $destinationPath = base_path(User::$uploads_path);
        $ThumbnailsDestinationPath = base_path(User::$thumbnails_uploads_path);

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }
        if (!File::exists($ThumbnailsDestinationPath)) {
            File::makeDirectory($ThumbnailsDestinationPath, $mode = 0777, true, true);
        }
        if (request()->hasFile($input_file)) {
            $file = request()->file($input_file);

            if (is_image($file->getRealPath())) {
                $filename = rand(0, 20000) . time() . '.' . $file->getClientOriginalExtension();


                $uploaded = $filename;

                $img = Image::make($file);
                $original_width = $img->width();
                $original_height = $img->height();

                if ($original_width > 1200 || $original_height > 900) {
                    if ($original_width < $original_height) {
                        $new_width = 1200;
                        $new_height = ceil($original_height * 900 / $original_width);
                    } else {
                        $new_height = 900;
                        $new_width = ceil($original_width * 1200 / $original_height);
                    }

                    //save used image
                    $img->encode('jpg', 90)->save($destinationPath . $filename);
                    $img->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg', 90)->save($destinationPath . '' . $filename);

                    //create thumbnail
                    if ($original_width < $original_height) {
                        $thumbnails_width = 400;
                        $thumbnails_height = ceil($new_height * 300 / $new_width);
                    } else {
                        $thumbnails_height = 300;
                        $thumbnails_width = ceil($new_width * 400 / $new_height);
                    }
                    $img->resize($thumbnails_width, $thumbnails_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg', 90)->save($ThumbnailsDestinationPath . '' . $filename);
                } else {
                    //save used image
                    $img->encode('jpg', 90)->save($destinationPath . $filename);
                    //create thumbnail
                    if ($original_width < $original_height) {
                        $thumbnails_width = 400;
                        $thumbnails_height = ceil($original_height * 300 / $original_width);
                    } else {
                        $thumbnails_height = 300;
                        $thumbnails_width = ceil($original_width * 400 / $original_height);
                    }
                    $img->resize($thumbnails_width, $thumbnails_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg', 90)->save($ThumbnailsDestinationPath . '' . $filename);
                }
                $inputs[$input_file]=$uploaded;
            }

        }


//        !$inputs['deleted_at']?$inputs['deleted_at']=null:'';

        return $inputs;
    }

    public function thanksRegister(){
        return view('access::Front.thanks_register', [
            'title' => trans('global.thank_you')]);
    }

}
