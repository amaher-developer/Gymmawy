@extends('generic::Front.layouts.auth_master')
@section('title'){{ $title }} | @endsection
@section('style')
    <style>
        #login_bg {
            background: url({{asset('resources/assets/front/img/bg/register.jpg')}}) center center no-repeat fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            min-height: 100vh;
            width: 100%;
        }
    </style>
@endsection
@section('content')

    <div id="login">
        <aside>
            <figure>
                <a href="{{asset($lang)}}"><img src="{{$mainSettings->logo}}"
                                                width="155" height="36" data-retina="true" alt=""
                                                class="logo_sticky"></a>
            </figure>
            @if(@$social_error)
                <div class="alert alert-danger">
                    {{ @$social_error }}
                </div>
            @endif

            <form autocomplete="off" method="POST" action="{{ route('socialRegister') }}">
                {{csrf_field()}}
                <input type="hidden" name="{{ $social_type }}"
                       value="{{ $social_user->id }}">
                <div class="form-group">
                    <label>{{trans('global.name')}} <span class="required">*</span></label>
                    <input value="{{ $social_user->name }}" class="form-control" type="text" name="name" @if($lang == 'en') dir="ltr" @endif required>
                    <i class="ti-user"></i>
                </div>
                <div class="form-group">
                    <label>{{trans('global.email')}} <span class="required">*</span></label>
                    <input class="form-control" type="email" value="{{ $social_user->email }}" name="email" @if($lang == 'en') dir="ltr" @endif required>
                    <i class="icon_mail_alt"></i>
                </div>
                <div class="form-group">
                    <label>{{trans('global.phone')}}</label>
                    <input class="form-control" type="tel" value="{{ @$social_user->phone }}" name="phone"  @if($lang == 'en') dir="ltr" @endif>
                    <i class="icon_phone"></i>
                </div>
                <div class="form-group">
                    <label>{{trans('global.password')}} <span class="required">*</span></label>
                    <input name="password" class="form-control" type="password" id="password1" @if($lang == 'en') dir="ltr" @endif required>
                    <i class="icon_lock_alt"></i>
                </div>
                <div class="form-group">
                    <label>{{trans('global.password_confirm')}} <span class="required">*</span></label>
                    <input name="password_confirmation" class="form-control" type="password" id="password2" @if($lang == 'en') dir="ltr" @endif required>
                    <i class="icon_lock_alt"></i>
                </div>
                <div id="pass-info" class="clearfix"></div>
                <button type="submit" class="btn_1 rounded full-width add_top_30">{{trans('global.sign_up')}}</button>
                <div class="text-center add_top_10">{{trans('global.account_found')}} <strong><a
                                href="{{route('login')}}">{{trans('global.login')}}</a></strong></div>
            </form>
            <div class="copy">{{trans('global.copy_right')}}</div>
        </aside>
    </div>
    <!-- /login -->



@endsection
