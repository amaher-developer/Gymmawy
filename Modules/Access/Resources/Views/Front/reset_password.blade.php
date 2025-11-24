@extends('generic::Front.layouts.auth_master')
@section('title'){{ $title }} | @endsection
@section('style')
    <style>
        #login_bg {
            background: url({{asset('resources/assets/front/img/bg/forgot_password.jpg')}}) center center no-repeat fixed;
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
                <a href="{{asset($lang)}}"><img src="{{$mainSettings->logo}}" width="155" height="36" data-retina="true" alt="" class="logo_sticky"></a>
            </figure>

            @include('generic::errors')

            <form role="form" method="POST" action="{{ route('password.request') }}">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label>{{trans('global.email')}} <span class="required">*</span></label>
                    <input class="form-control" id="email" type="email" value="{{ old('email', @$email) }}"  name="email" @if($lang == 'en') dir="ltr" @endif required>
                    <i class="icon_mail_alt"></i>
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

                <button type="submit" class="btn_1 rounded full-width">{{trans('global.reset_password')}}</button>
{{--                <div class="text-center add_top_10"><strong><a href="{{route('login')}}">{{trans('global.login')}}</a></strong></div>--}}
{{--                <div class="text-center add_top_10"><strong><a href="{{route('register')}}">{{trans('global.sign_up')}}</a></strong></div>--}}
            </form>
            <div class="copy">{{trans('global.copy_right')}}</div>
        </aside>
    </div>
    <!-- /login -->



@endsection
@section('script')

@stop
