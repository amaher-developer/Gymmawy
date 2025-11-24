@extends('generic::layouts.user_form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('showUserFront') }}">{{trans('admin.my_info')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('editUserFront') }}">{{trans('admin.edit_my_info')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            {{ $title }}
        </li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('styles')
    <style>
        .district_city_li {display: none;}
    </style>
@endsection
@section('page_body')

    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}


            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('global.password')}}</label>
                <div class="col-md-9">
                    <input  name="old_password" type="Password" placeholder="{{trans('global.password')}}"
                           class="form-control" >
                </div>
            </div>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('global.new_password')}}</label>
                <div class="col-md-9">
                    <input name="password" type="Password" placeholder=" {{trans('global.new_password')}} "
                           class="form-control" >
                </div>
            </div>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('global.new_password_confirmation')}}</label>
                <div class="col-md-9">
                    <input name="password_confirmation" type="Password" placeholder=" {{trans('global.new_password_confirmation')}} "
                           class="form-control" >
                </div>
            </div>

            <div class="form-actions" style="clear:both;">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">{{trans('admin.submit')}}</button>
                        <input type="reset" class="btn default" value="{{trans('admin.reset')}}">
                    </div>
                </div>
            </div>
        </div>
    </form>





@endsection