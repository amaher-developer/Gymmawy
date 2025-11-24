@extends('generic::Front.layouts.master')

@section('content')



    <!-- Inner Page Banner Area Start Here -->
    <div class="inner-page-banner-area" style="background-image: url('img/banner/about-banner.jpg');">
        <div class="container">
            <div class="pagination-area">
                <h2 class="inner-section-title-textprimary">{{trans('global.reset_password')}}</h2>
                <div class="section-title-bar"><i class="flaticon-dumbbell"></i></div>
                <ul>
                    <li><a href="{{route('home')}}">{{trans('global.home')}}</a> -</li>
                    <li><a href="{{route('login')}}">{{trans('global.login')}}</a> -</li>
                    <li>{{trans('global.reset_password')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Inner Page Banner Area End Here -->
    <!-- About Page Area Start Here -->
    <div class="section-space-top body-bg" style="padding: 100px; 0">
        <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">{{trans('global.email')}}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required
                                   data-bv-notempty-message="{{trans('generic::global.required')}}">

                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                {{trans('global.send')}}
                            </button>
                        </div>
                    </div>
                </form>




            </div>
        </div>
    </div>
    </div>
@endsection
