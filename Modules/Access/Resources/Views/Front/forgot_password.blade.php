@extends('generic::Front.layouts.auth_master')
@section('title'){{ $title }} | @endsection
@section('style')
    <style>
        #login_bg {
            background: url({{asset('resources/assets/front/img/bg/reset_password.jpg')}}) center center no-repeat fixed;
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
                <a href="{{asset($lang)}}"><img src="{{$mainSettings->logo}}" width="155" height="36" data-retina="true" alt="" class="logo_sticky"></a>
            </figure>

            <h1 class="auth-title">{{$title}}</h1>
            @include('generic::errors')

            <form role="form" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>{{trans('global.email')}}</label>
                    <input type="email" name="email" placeholder="{{trans('global.email')}}" required data-bv-notempty-message="{{trans('generic::global.required')}}" class="form-control" id="email" @if($lang == 'en') dir="ltr" @endif>
                    <i class="icon_mail_alt"></i>
                </div>

                <button type="submit" class="btn_1 rounded full-width">{{trans('global.send')}}</button>
                <div class="text-center add_top_10"><strong><a href="{{route('login')}}">{{trans('global.login')}}</a></strong></div>
                <div class="text-center add_top_10"><strong><a href="{{route('register')}}">{{trans('global.sign_up')}}</a></strong></div>
            </form>
            <div class="copy">{{trans('global.copy_right')}}</div>
        </aside>
    </div>
    <!-- /login -->



@endsection
@section('script')

@stop
