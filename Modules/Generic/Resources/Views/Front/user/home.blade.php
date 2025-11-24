@extends('generic::layouts.user_form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            {{ $title }}
        </li>
    </ul>
@endsection

@section('styles')
    <style>
        .maher-dashboard-current-image {
            height: 120px;
            width: 120px;
            object-fit: cover;
        }
    </style>
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{asset('resources/assets/admin/pages/css/pricing.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('form_title') {{ @$title }} @endsection
@section('page_body')

    <!-- BEGIN PAGE CONTENT INNER -->
    <div class="page-content-inner">


        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="dashboard-stat2 ">
                    <div class="display">
                        <a href="{{route('articles')}}" title="{{trans('global.articles')}}">
                        <div class="number">
{{--                            <h3 class="font-green-sharp">--}}
{{--                                <span data-counter="counterup" data-value="7800">{{$currentUser->articles}}</span>--}}
{{--                                <small class="font-green-sharp"> </small>--}}
{{--                            </h3>--}}
                            <small>{{$currentUser->articles}} {{trans('global.articles')}}</small>
                        </div>
                        </a>
                        <a href="{{route('createUserArticle')}}" title="{{trans('admin.article_add')}}">
                        <div class="icon">
                            <i class="icon-note"></i>
                        </div>
                        </a>
                    </div>

                    <div class="progress-info">
                        <div class="progress">
                            <span style="width: 100%;" class="progress-bar progress-bar-success green-sharp">
                                <span class="sr-only"> </span>
                            </span>
                        </div>
                        <a href="{{route('createUserArticle')}}">
                        <div class="status">
                            <div class="status-title">
                                {{trans('admin.article_add')}}

                            </div>
                            {{--<div class="status-number"> 76% </div>--}}
                        </div></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="dashboard-stat2 ">
                    @if($currentUser->is_gym)
                        <a href="{{route('editUserGymBrand')}}" title="{{trans('admin.gym_add')}}" >
                    @else
                        <a href="{{route('showUserGymBrand')}}" title="{{trans('admin.gym_show')}}" >
                    @endif
                    <div class="display">
                        <div class="number">
                            <h3 class="font-red-haze">
                                <span data-counter="counterup" data-value="1349">{{$currentUser->is_gym}}</span>
                            </h3>
                            <small>{{trans('admin.gym')}}</small>
                        </div>
                        <div class="icon">
                            <i class="icon-fire"></i>
                        </div>
                    </div>
                    </a>
                    <div class="progress-info">
                        <div class="progress">
                                    <span style="width: 100%;" class="progress-bar progress-bar-success red-haze">
                                        <span class="sr-only"> </span>
                                    </span>
                        </div>
                        @if($currentUser->is_gym)
                        <a href="{{route('showUserGymBrand')}}" >
                            <div class="status">
                                <div class="status-title"> {{trans('admin.gym_show')}}  </div>
                            </div>
                        </a>
                        @else
                        <a href="{{route('editUserGymBrand')}}">
                            <div class="status">
                                <div class="status-title"> {{trans('admin.gym_add')}}  </div>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="dashboard-stat2 ">
                    @if($currentUser->is_trainer)
                        <a href="{{route('editUserTrainer')}}" title="{{trans('admin.trainer_add')}}" >
                    @else
                        <a href="{{route('showUserTrainer')}}" title="{{trans('admin.trainer_show')}}" >
                    @endif
                    <div class="display">
                        <div class="number">
                            <h3 class="font-yellow-haze">
                                <span data-counter="counterup" data-value="1349">{{$currentUser->is_trainer}}</span>
                            </h3>
                            <small>{{trans('admin.trainer')}}</small>
                        </div>
                        <div class="icon">
                            <i class="icon-shield"></i>
                        </div>
                    </div>
                    </a>
                    <div class="progress-info">
                        <div class="progress">
                                    <span style="width: 100%;" class="progress-bar progress-bar-success yellow-haze">
                                        <span class="sr-only"> </span>
                                    </span>
                        </div>
                        @if($currentUser->is_trainer)
                        <a href="{{route('showUserTrainer')}}" >
                            <div class="status">
                                <div class="status-title"> {{trans('admin.trainer_show')}}  </div>
                            </div>
                        </a>
                        @else
                        <a href="{{route('editUserTrainer')}}">
                            <div class="status">
                                <div class="status-title"> {{trans('admin.trainer_add')}}  </div>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            {{--<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">--}}
                {{--<div class="dashboard-stat2 ">--}}
                    {{--<div class="display">--}}
                        {{--<div class="number">--}}
                            {{--<h3 class="font-blue-sharp">--}}
                                {{--<span data-counter="counterup" data-value="567">0</span>--}}
                            {{--</h3>--}}
                            {{--<small>NEW ORDERS</small>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="icon-basket"></i>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="progress-info">--}}
                        {{--<div class="progress">--}}
                                                            {{--<span style="width: 45%;" class="progress-bar progress-bar-success blue-sharp">--}}
                                                                {{--<span class="sr-only">45% grow</span>--}}
                                                            {{--</span>--}}
                        {{--</div>--}}
                        {{--<div class="status">--}}
                            {{--<div class="status-title"> grow </div>--}}
                            {{--<div class="status-number"> 45% </div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">--}}
                {{--<div class="dashboard-stat2 ">--}}
                    {{--<div class="display">--}}
                        {{--<div class="number">--}}
                            {{--<h3 class="font-purple-soft">--}}
                                {{--<span data-counter="counterup" data-value="276">0</span>--}}
                            {{--</h3>--}}
                            {{--<small>NEW USERS</small>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="icon-user"></i>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="progress-info">--}}
                        {{--<div class="progress">--}}
                                                            {{--<span style="width: 57%;" class="progress-bar progress-bar-success purple-soft">--}}
                                                                {{--<span class="sr-only">56% change</span>--}}
                                                            {{--</span>--}}
                        {{--</div>--}}
                        {{--<div class="status">--}}
                            {{--<div class="status-title"> change </div>--}}
                            {{--<div class="status-number"> 57% </div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>


        {{--<div class="portlet light portlet-fit ">--}}
            {{--<div class="portlet-title">--}}
                {{--<div class="caption">--}}
                    {{--<i class="icon-share font-green"></i>--}}
                    {{--<span class="caption-subject font-green bold uppercase">Pricing 1</span>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="portlet-body">--}}
                {{--<div class="pricing-content-1">--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-md-3">--}}
                            {{--<div class="price-column-container border-active">--}}
                                {{--<div class="price-table-head bg-blue">--}}
                                    {{--<h2 class="no-margin">Budget</h2>--}}
                                {{--</div>--}}
                                {{--<div class="arrow-down border-top-blue"></div>--}}
                                {{--<div class="price-table-pricing">--}}
                                    {{--<h3>--}}
                                        {{--<sup class="price-sign">$</sup>24</h3>--}}
                                    {{--<p>per month</p>--}}
                                {{--</div>--}}
                                {{--<div class="price-table-content">--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-user"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">3 Members</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-drawer"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">50GB Storage</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-screen-smartphone"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">Single Device</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-refresh"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">Weekly Backups</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="arrow-down arrow-grey"></div>--}}
                                {{--<div class="price-table-footer">--}}
                                    {{--<button type="button" class="btn grey-salsa btn-outline sbold uppercase price-button">Sign Up</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-3">--}}
                            {{--<div class="price-column-container border-active">--}}
                                {{--<div class="price-table-head bg-red">--}}
                                    {{--<h2 class="no-margin">Solo</h2>--}}
                                {{--</div>--}}
                                {{--<div class="arrow-down border-top-red"></div>--}}
                                {{--<div class="price-table-pricing">--}}
                                    {{--<h3>--}}
                                        {{--<sup class="price-sign">$</sup>39</h3>--}}
                                    {{--<p>per month</p>--}}
                                {{--</div>--}}
                                {{--<div class="price-table-content">--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-user"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">5 Members</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-drawer"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">100GB Storage</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-screen-smartphone"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">Single Device</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-refresh"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">Weekly Backups</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="arrow-down arrow-grey"></div>--}}
                                {{--<div class="price-table-footer">--}}
                                    {{--<button type="button" class="btn grey-salsa btn-outline price-button sbold uppercase">Sign Up</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-3">--}}
                            {{--<div class="price-column-container border-active">--}}
                                {{--<div class="price-table-head bg-green">--}}
                                    {{--<h2 class="no-margin">Start up</h2>--}}
                                {{--</div>--}}
                                {{--<div class="arrow-down border-top-green"></div>--}}
                                {{--<div class="price-table-pricing">--}}
                                    {{--<h3>--}}
                                        {{--<sup class="price-sign">$</sup>59</h3>--}}
                                    {{--<p>per month</p>--}}
                                    {{--<div class="price-ribbon">Popular</div>--}}
                                {{--</div>--}}
                                {{--<div class="price-table-content">--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-user-follow"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">20 Members</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-drawer"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">500GB Storage</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-cloud-download"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">Cloud Syncing</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-refresh"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">Daily Backups</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="arrow-down arrow-grey"></div>--}}
                                {{--<div class="price-table-footer">--}}
                                    {{--<button type="button" class="btn green price-button sbold uppercase">Sign Up</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-3">--}}
                            {{--<div class="price-column-container border-active">--}}
                                {{--<div class="price-table-head bg-purple">--}}
                                    {{--<h2 class="no-margin">Enterprise</h2>--}}
                                {{--</div>--}}
                                {{--<div class="arrow-down border-top-purple"></div>--}}
                                {{--<div class="price-table-pricing">--}}
                                    {{--<h3>--}}
                                        {{--<sup class="price-sign">$</sup>128</h3>--}}
                                    {{--<p>per month</p>--}}
                                {{--</div>--}}
                                {{--<div class="price-table-content">--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-users"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">100 Members</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-drawer"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">2TB Storage</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-cloud-download"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">Cloud Syncing</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row mobile-padding">--}}
                                        {{--<div class="col-xs-3 text-right mobile-padding">--}}
                                            {{--<i class="icon-refresh"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-9 text-left mobile-padding">Weekly Backups</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="arrow-down arrow-grey"></div>--}}
                                {{--<div class="price-table-footer">--}}
                                    {{--<button type="button" class="btn grey-salsa btn-outline price-button sbold uppercase">Sign Up</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}


        <div class="row">
            <div class="col-md-4  col-sm-4">
                <!--begin: widget 1-1 -->
                <div class="mt-widget-1">
                    <div class="mt-icon">
                        <a href="{{route('editUserFront')}}" title="{{trans('admin.edit_my_info')}}">
                            <i class="icon-note"></i>
                        </a>
                    </div>
                    <a href="{{route('showUserFront')}}" title="{{trans('admin.my_info')}}">
                    <div class="mt-img">
                        <img class="maher-dashboard-current-image" src="@if($currentUser->image) {{$currentUser->image}} @else {{asset('uploads/users/default.jpg')}} @endif">
                    </div>
                    </a>
                    <div class="mt-body">
                        <a href="{{route('showUserFront')}}" title="{{trans('admin.my_info')}}">
                        <h3 class="mt-username">{{$currentUser->name}}</h3>
                        </a>
                        <p class="mt-user-title">{{\Illuminate\Support\Str::limit($currentUser->about, $limit = 50, $end = '...')}} </p>
                        <div class="mt-stats">
                            <div class="btn-group btn-group btn-group-justified">
                                <a href="{{route('listUserArticle')}}" title="{{trans('admin.my_articles')}}" class="btn font-green">
                                    <i class="icon-note"></i> {{$currentUser->articles}} </a>
                                <a href="{{route('showUserGymBrand')}}" title="{{trans('admin.gym_show')}}" class="btn font-red">
                                    <i class=" icon-fire"></i> {{$currentUser->is_gym}} </a>
                                <a href="{{route('showUserTrainer')}}" title="{{trans('admin.trainer_show')}}" class="btn font-yellow">
                                    <i class="icon-shield"></i> {{$currentUser->is_trainer}} </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: widget 1-1 -->
            </div>
            <div class="col-md-4 col-sm-4">
            @if($currentGym)
                <!--begin: widget 1-2 -->
                <div class="mt-widget-1">
                    <div class="mt-icon">
                        <a href="{{route('editUserGymBrand')}}" title="{{trans('admin.gym_edit')}}">
                            <i class="icon-note"></i>
                        </a>
                    </div>
                    <a href="{{route('showUserGymBrand')}}" title="{{trans('admin.gym_show')}}">
                    <div class="mt-img">
                        <img class="maher-dashboard-current-image"  src="@if($currentGym->image) {{$currentGym->image}} @else {{asset('uploads/users/default.jpg')}} @endif">
                    </div>
                    </a>
                    <div class="mt-body">
                        <a href="{{route('showUserGymBrand')}}" title="{{trans('admin.gym_show')}}">
                        <h3 class="mt-username">{{$currentGym->name}}</h3>
                        </a>
                        <p class="mt-user-title"> {{\Illuminate\Support\Str::limit($currentGym->description, $limit = 50, $end = '...')}}  </p>
                        <div class="mt-stats">
                            {{--<div class="btn-group btn-group btn-group-justified">--}}
                                {{--<a href="javascript:;" class="btn font-yellow">--}}
                                    {{--<i class="icon-bubbles"></i> 1,7k </a>--}}
                                {{--<a href="javascript:;" class="btn font-blue">--}}
                                    {{--<i class="icon-social-twitter"></i> 2,6k </a>--}}
                                {{--<a href="javascript:;" class="btn font-green">--}}
                                    {{--<i class="icon-emoticon-smile"></i> 3,7k </a>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
                <!--end: widget 1-2 -->
                @endif
            </div>
            <div class="col-md-4 col-sm-4">
            @if($currentTrainer)
                <!--begin: widget 1-3 -->
                <div class="mt-widget-1">
                    <div class="mt-icon">
                        <a href="{{route('editUserTrainer')}}" title="{{trans('admin.trainer_edit')}}">
                            <i class="icon-note"></i>
                        </a>
                    </div>
                    <a href="{{route('showUserTrainer')}}" title="{{trans('admin.trainer_show')}}">
                    <div class="mt-img">
                        <img class="maher-dashboard-current-image"  src="@if($currentTrainer->image) {{$currentTrainer->image}} @else {{asset('uploads/users/default.jpg')}} @endif">
                    </div>
                    </a>
                    <div class="mt-body">
                        <a href="{{route('showUserTrainer')}}" title="{{trans('admin.trainer_show')}}">
                        <h3 class="mt-username">{{$currentTrainer->name}}</h3>
                        </a>
                        <p class="mt-user-title">{{\Illuminate\Support\Str::limit($currentTrainer->about, $limit = 50, $end = '...')}} </p>
                        <div class="mt-stats">
                            {{--<div class="btn-group btn-group btn-group-justified">--}}
                                {{--<a href="javascript:;" class="btn font-yellow">--}}
                                    {{--<i class="icon-bubbles"></i> 1,7k </a>--}}
                                {{--<a href="javascript:;" class="btn font-red">--}}
                                    {{--<i class="icon-social-twitter"></i> 2,6k </a>--}}
                                {{--<a href="javascript:;" class="btn font-green">--}}
                                    {{--<i class="icon-emoticon-smile"></i> 3,7k </a>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
                <!--end: widget 1-3 -->
                @endif
            </div>
        </div>

    </div>



@endsection

