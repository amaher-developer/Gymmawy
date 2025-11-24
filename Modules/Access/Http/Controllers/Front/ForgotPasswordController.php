<?php

namespace Modules\Access\Http\Controllers\Front;

// Note: SendsPasswordResetEmails trait was removed in Laravel 12
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Modules\Generic\Http\Controllers\Front\GenericFrontController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends GenericFrontController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // Laravel 12: Trait moved/removed - functionality needs to be implemented manually
    // use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('access::Front.forgot_password', ['title' => trans('global.forgot_password')]);
    }
    
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = Password::sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
    
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return redirect()->route('thanks')->with('status', trans($response));
    }
    
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()->withErrors(['email' => trans($response)]);
    }


}
