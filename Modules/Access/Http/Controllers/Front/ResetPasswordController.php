<?php

namespace Modules\Access\Http\Controllers\Front;

use Illuminate\Auth\Events\PasswordReset;
// Note: ResetsPasswords trait was removed in Laravel 12
// use Illuminate\Foundation\Auth\ResetsPasswords;
use Modules\Generic\Http\Controllers\Front\GenericFrontController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends GenericFrontController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    // Laravel 12: Trait moved/removed - functionality needs to be implemented manually
    // use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';


    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('access::Front.reset_password')->with(
            ['token' => $token, 'email' => $request->email, 'title' => trans('global.reset_password')]
        );
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }
    
    public function reset(Request $request)
    {
        $request->validate($this->rules());

        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $this->resetPassword($user, Hash::make($password));
            }
        );

        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request, $response)
                    : $this->sendResetFailedResponse($request, $response);
    }

    protected function resetPassword($user, $password)
    {
        $user->password = $password;

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }
    
    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->redirectTo)->with('status', trans($response));
    }
    
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return back()->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
    }
    
    protected function guard()
    {
        return \Auth::guard();
    }

}
