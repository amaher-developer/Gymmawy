@extends('generic::Front.layouts.auth_master')
@section('title'){{ $title }} | @endsection
@section('style')
    <style>
        #login_bg {
            background: url({{asset('resources/assets/front/img/bg/login_'.$lang.'.jpg')}}) center center no-repeat fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            min-height: 100vh;
            width: 100%;
        }
        .auth-title{
            text-align: center;
            font-size: 22px;
            font-weight: bolder;
        }
    </style>
@endsection
@section('content')


    <div id="login">
        <aside>
            <figure>
                <a href="{{asset($lang)}}"><img src="{{$mainSettings->logo}}" width="155" height="36" data-retina="true"
                                                alt="" class="logo_sticky"></a>
            </figure>

            <h1 class="auth-title">{{$title}}</h1>
            <div class="access_social">
                <a href="{{route('socialLogin').'?provider=facebook'}}"
                   class="social_bt facebook">{{trans('global.login_facebook')}}</a>
{{--                <a href="{{route('socialLogin').'?provider=twitter'}}" class="social_bt twitter"><i--}}
{{--                            class="fa fa-twitter"></i>{{trans('global.login_twitter')}}</a>--}}
                <a href="{{route('socialLogin').'?provider=google'}}"
                   class="social_bt google">{{trans('global.login_google')}}</a>
            </div>
            <div class="divider"><span>{{trans('global.or')}}</span></div>
            @include('generic::errors')
            <form role="form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>{{trans('global.email')}}</label>
                    <input type="email" name="email" placeholder="{{trans('global.email')}}" class="form-control"
                           id="email" @if($lang == 'en') dir="ltr" @endif>
                    <i class="icon_mail_alt"></i>
                </div>
                <div class="form-group">
                    <label>{{trans('global.password')}}</label>
                    <input type="password" class="form-control" name="password" id="password" value=""
                           @if($lang == 'en') dir="ltr" @endif>
                    <i class="icon_lock_alt"></i>
                </div>
                <div class="clearfix add_bottom_30">
                    <div class="checkboxes float-left">
                        <label class="container_check">{{trans('global.remember_me')}}
                            <input type="checkbox">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="float-right mt-1" style="direction: ltr"><a id="forgot"
                                                                            href="{{route('password.request')}}">{{trans('global.forgot_password')}}</a>
                    </div>
                </div>
                <button type="submit" class="btn_1 rounded full-width">{{trans('global.login')}}</button>
                {{--                <a href="#0" class="btn_1 rounded full-width">{{trans('global.login')}}</a>--}}

                <div class="text-center add_top_10"><strong><a href="{{route('register')}}">{{trans('global.sign_up')}}</a></strong></div>
            </form>

            <div class="copy">{{trans('global.copy_right')}}</div>
        </aside>
    </div>
    <!-- /login -->



@endsection
@section('script')

@stop
