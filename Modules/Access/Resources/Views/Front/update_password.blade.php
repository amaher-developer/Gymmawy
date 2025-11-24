@extends('generic::Front.layouts.master')

@section('content')
    <div class="container make-margin-top make-margin-bottom remove-the-padding">
        <div class="col-xs-12 col-md-9 col-sm-12">
            <div class="passowrd-mian add-review">
                <h3> {{trans('global.reset_password')}} </h3>

                <form method="POST">
                    {{csrf_field()}}
                    {{ method_field('PATCH') }}

                    <input name="old_password" type="Password" placeholder="{{trans('global.password')}}">
                    <input name="password" type="Password" placeholder=" {{trans('global.new_password')}} ">
                    <input name="password_confirmation" type="Password" placeholder=" {{trans('global.new_password_confirmation')}} ">
                    <input type="submit" value="{{trans('global.update')}}" class="make-margin-top edit-btn">
                </form>
            </div>
        </div>
        @include('access::Front.sidebar')
    </div>
@endsection