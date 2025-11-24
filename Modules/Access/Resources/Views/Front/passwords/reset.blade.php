@extends('generic::Front.layouts.master')

@section('content')
    <div class="sign_up_widget">
        <div class="container">
            <div class="row">
                <div class="col-md-8 border_class">
                    <div class="new_account">
                        <form method="POST" action="{{ route('password.request') }}">
                            <h3>{{trans('global.reset_password')}}</h3>
                            {{csrf_field()}}
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input value="{{ old('email') }}" class="custom_input" type="email" name="email"
                                   placeholder="{{trans('global.email')}} ">
                            <input class="custom_input" type="password" name="password" placeholder="{{trans('global.password')}}">
                            <input class="custom_input" type="password" name="password_confirmation"
                                   placeholder="{{trans('global.password_confirm')}} ">
                            <input type="submit" class="login loginmodal-submit" value="{{trans('global.update')}}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
