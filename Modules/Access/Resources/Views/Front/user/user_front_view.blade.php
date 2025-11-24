@extends('generic::layouts.user_list')
@section('styles')
    <link href="http://keenthemes.com/preview/metronic/theme/assets/pages/css/profile-2.min.css"
          rel="stylesheet" type="text/css"/>
    <style>
        .maher-tags a{
            background-color: #f4f6f8;
            color: #a0a9b4;
            font-size: 11px;
            font-weight: 600;
            padding: 7px 10px;
        }
        .maher-tags li {
            list-style: none;
            display: inline-block;
            margin: 0 5px 20px 0;
        }
        .verfiy {
            color: #fff;
            background: rgba(255, 96, 45, 1);
            transition: .5s;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255, 255, 255, 1);
            text-align: right;
            padding: 2px 7px;
            margin-right: 20px;
            border: 1px solid rgba(255, 96, 45, 1);
        }
        .verfiy:hover{

            color:rgba(255, 96, 45, 1);

            background: none;

            border: 1px solid rgba(255, 96, 45, 1);

            transition: .5s;



        }
    </style>
@endsection
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            {{ $title }}
        </li>
    </ul>
@endsection
@section('list_add_button')
    <a href="{{route('editUserFront')}}" class="btn btn-lg btn-success">{{trans('admin.edit_my_info')}}</a>

@endsection
@section('list_title') {{ @$title }} @endsection
@section('page_body')
<div class="row">

        <div class="col-md-3 col-sm-3">
            <ul class="list-unstyled profile-nav">
                <li>
                    <img src="@if($user->image) {{$user->image}} @else {{asset('uploads/users/default.jpg')}} @endif" class="img-responsive pic-bordered" alt="" />
                </li>

            </ul>
        </div>
        <div class="col-md-9 col-sm-9">
        <div class="row">

                <div class="col-md-8 col-sm-8 profile-info">
                    <h1 class="font-green sbold uppercase">{{$user->name}}</h1>
                    <p> {{$user->about}} </p>

                </div>
                <!--end col-md-8-->
                <div class="col-md-4  col-sm-4">

                    <div class="portlet sale-summary">
                        <div class="portlet-title">
                            <div class="caption font-red sbold"> {{trans('admin.contact_info')}} </div>
                        </div>
                        <div class="portlet-body">
                            <ul class="list-unstyled">
                                @if($user->email)
                                <li>
                                    <i class="fa fa-envelope"></i> {{$user->email}}
                                    <br/><br/>
                                </li>
                                @endif
                                @if($user->phone)
                                <li>
                                    <i class="fa fa-phone"></i> {{$user->phone}}
{{--                                    @if(!$user->verified) --}}
{{--                                        <a href="{{route('showVerificationPage')}}" class="verfiy" title="">{{trans('global.verify')}}</a> --}}
{{--                                    @endif--}}
                                    <br/><br/>
                                </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
                <!--end col-md-4-->

        </div>
        <!--end row-->
        </div>
</div>

@endsection

