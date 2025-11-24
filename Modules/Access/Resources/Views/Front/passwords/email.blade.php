@extends('generic::Front.layouts.master')

@section('content')

    <div class="sign_up_widget">
        <div class="container">
            <div class="row">
                <div class="col-md-8 border_class">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @else
                        <div class="new_account">
                            <form role="form" method="POST" action="{{ route('password.email') }}">
                                {{ csrf_field() }}
                                <h3>{{trans('global.reset_password')}}</h3>
                                <input placeholder="{{trans('global.enter_email')}}" id="email" type="email"
                                       class="custom_input" name="email" value="{{ old('email') }}" required>
                                <input type="submit" class="login loginmodal-submit login_sign_in" value="{{trans('global.send')}}">
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
