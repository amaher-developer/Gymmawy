<!DOCTYPE html >
<!--[if IE 8]>
<html lang="{{app()->getLocale('lang')}}" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="{{app()->getLocale('lang')}}" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{app()->getLocale('lang')}}" @if(app()->getLocale('lang')=='ar') dir="rtl" @endif >
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>

    <noscript>
        <meta http-equiv='refresh' content='0;url={{ route('noJs') }}'>
    </noscript>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('resources/assets/front/img/logo/favicon.ico')}}">

    <meta charset="utf-8"/>
    <title>{{$mainSettings->name}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('resources/assets/admin/global/plugins/font-awesome/css/font-awesome.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('resources/assets/admin/global/plugins/simple-line-icons/simple-line-icons.min.css')}}"
          rel="stylesheet" type="text/css"/>

    @if($lang == 'ar')

        {{--Arabic section--}}
        <link href="{{asset('resources/assets/admin/global/plugins/bootstrap/css/bootstrap-rtl.min.css')}}"
              rel="stylesheet"
              type="text/css"/>
        <link href="{{asset('resources/assets/admin/global/plugins/bootstrap-switch/css/bootstrap-switch-rtl.min.css')}}"
              rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{asset('resources/assets/admin/global/css/components-rtl.min.css')}}" rel="stylesheet"
              id="style_components" type="text/css"/>
        <link href="{{asset('resources/assets/admin/global/css/plugins-rtl.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{asset('resources/assets/admin/layouts/layout/css/layout-rtl.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{asset('resources/assets/admin/layouts/layout/css/themes/default-rtl.css')}}" rel="stylesheet"
              type="text/css" id="style_color"/>

        <!-- END THEME LAYOUT STYLES -->
        <style>
            .page-bar .page-breadcrumb > li > a, .page-bar .page-breadcrumb > li > span {
                float: left;
            }

            .control-label {
                text-align: right !important;
            }

            .mt-checkbox > span:after {
                left: 1px !important;
                bottom: 6px !important;
                width: 15px !important;
                height: 8px !important;
                right: 0px !important;
            }

            .bootstrap-select.btn-group .dropdown-toggle .filter-option {
                text-align: right !important;
            }
        </style>
    @else
        {{--English section--}}
        <link href="{{asset('resources/assets/admin/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{asset('resources/assets/admin/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}"
              rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{asset('resources/assets/admin/global/css/components.min.css')}}" rel="stylesheet"
              id="style_components" type="text/css"/>
        <link href="{{asset('resources/assets/admin/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css"/>
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{asset('resources/assets/admin/layouts/layout/css/layout.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{asset('resources/assets/admin/layouts/layout/css/themes/default.css')}}" rel="stylesheet"
              type="text/css" id="style_color"/>
        <!-- END THEME LAYOUT STYLES -->
    <style>
        .page-bar .page-breadcrumb > li > a, .page-bar .page-breadcrumb > li > span {
            float: right;
        }

        .control-label {
            text-align: left !important;
        }

        .mt-checkbox > span:after {
            left: 3px !important;
            bottom: 0px !important;
            width: 9px !important;
            height: 16px !important;
            right: 0px !important;
            top: -2px !important;
        }
        .input-icon>i{
            left: 0 !important;
            right: auto;
        }

        .bootstrap-select.btn-group .dropdown-toggle .filter-option {
            text-align: left !important;
        }
    </style>
    @endif
<!-- BEGIN PAGE LEVEL PLUGINS -->
{{--    <link href="{{asset('resources/assets/admin/global/plugins/select2/css/select2.min.css')}}" rel="stylesheet"--}}
{{--          type="text/css"/>--}}
{{--    <link href="{{asset('resources/assets/admin/global/plugins/select2/css/select2-bootstrap.min.css')}}"--}}
{{--          rel="stylesheet" type="text/css"/>--}}
    <!-- END PAGE LEVEL PLUGINS -->

    @yield('styles')

    <style>
        .filter_trigger_button {
            margin-bottom: 20px;
        }

        .widget-bg-color-lite-gray {
            background-color: #EEEEEE !important;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
    </style>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-180323439-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-180323439-1');
    </script>
</head>
<!-- END HEAD -->


{{--<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">--}}
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white
{{--@if(@$gym_system == true) page-sidebar-closed page-sidebar-closed-hide-logo @endif--}}
        ">
<div class="page-wrapper">

    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
            <!-- BEGIN LOGO -->

            <div class="page-logo">
                <div class="menu-toggler sidebar-toggler">
                    <span></span>
                </div>
                <a href="{{route('home')}}" style="width: 85%;text-align: center;">
                        <img src="{{$mainSettings->logo_white}}" alt="logo" class="logo-default"
                             style="margin: 0px;height: 50px;width: 70%;object-fit: contain;">
                </a>
            </div>

            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
               data-target=".navbar-collapse">
                <span></span>
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">

                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN NOTIFICATION DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
{{--                    <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">--}}
{{--                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">--}}
{{--                            <i class="icon-bell"></i>--}}
{{--                            <span class="badge badge-default">--}}
{{--					7 </span>--}}
{{--                        </a>--}}
{{--                        <ul class="dropdown-menu">--}}
{{--                            <li class="external">--}}
{{--                                <h3><span class="bold">12 pending</span> notifications</h3>--}}
{{--                                <a href="extra_profile.html">view all</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:;">--}}
{{--                                            <span class="time">just now</span>--}}
{{--                                            <span class="details">--}}
{{--									<span class="label label-sm label-icon label-success">--}}
{{--									<i class="fa fa-plus"></i>--}}
{{--									</span>--}}
{{--									New user registered. </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:;">--}}
{{--                                            <span class="time">3 mins</span>--}}
{{--                                            <span class="details">--}}
{{--									<span class="label label-sm label-icon label-danger">--}}
{{--									<i class="fa fa-bolt"></i>--}}
{{--									</span>--}}
{{--									Server #12 overloaded. </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:;">--}}
{{--                                            <span class="time">10 mins</span>--}}
{{--                                            <span class="details">--}}
{{--									<span class="label label-sm label-icon label-warning">--}}
{{--									<i class="fa fa-bell-o"></i>--}}
{{--									</span>--}}
{{--									Server #2 not responding. </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:;">--}}
{{--                                            <span class="time">14 hrs</span>--}}
{{--                                            <span class="details">--}}
{{--									<span class="label label-sm label-icon label-info">--}}
{{--									<i class="fa fa-bullhorn"></i>--}}
{{--									</span>--}}
{{--									Application error. </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:;">--}}
{{--                                            <span class="time">2 days</span>--}}
{{--                                            <span class="details">--}}
{{--									<span class="label label-sm label-icon label-danger">--}}
{{--									<i class="fa fa-bolt"></i>--}}
{{--									</span>--}}
{{--									Database overloaded 68%. </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:;">--}}
{{--                                            <span class="time">3 days</span>--}}
{{--                                            <span class="details">--}}
{{--									<span class="label label-sm label-icon label-danger">--}}
{{--									<i class="fa fa-bolt"></i>--}}
{{--									</span>--}}
{{--									A user IP blocked. </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:;">--}}
{{--                                            <span class="time">4 days</span>--}}
{{--                                            <span class="details">--}}
{{--									<span class="label label-sm label-icon label-warning">--}}
{{--									<i class="fa fa-bell-o"></i>--}}
{{--									</span>--}}
{{--									Storage Server #4 not responding dfdfdfd. </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:;">--}}
{{--                                            <span class="time">5 days</span>--}}
{{--                                            <span class="details">--}}
{{--									<span class="label label-sm label-icon label-info">--}}
{{--									<i class="fa fa-bullhorn"></i>--}}
{{--									</span>--}}
{{--									System Error. </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript:;">--}}
{{--                                            <span class="time">9 days</span>--}}
{{--                                            <span class="details">--}}
{{--									<span class="label label-sm label-icon label-danger">--}}
{{--									<i class="fa fa-bolt"></i>--}}
{{--									</span>--}}
{{--									Storage server failed. </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
                    <!-- END NOTIFICATION DROPDOWN -->
                    <!-- BEGIN INBOX DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
{{--                    <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">--}}
{{--                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">--}}
{{--                            <i class="icon-envelope-open"></i>--}}
{{--                            <span class="badge badge-default">--}}
{{--					4 </span>--}}
{{--                        </a>--}}
{{--                        <ul class="dropdown-menu">--}}
{{--                            <li class="external">--}}
{{--                                <h3>You have <span class="bold">7 New</span> Messages</h3>--}}
{{--                                <a href="page_inbox.html">view all</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">--}}
{{--                                    <li>--}}
{{--                                        <a href="inbox.html?a=view">--}}
{{--									<span class="photo">--}}
{{--									<img src="../../assets/admin/layout3/img/avatar2.jpg" class="img-circle" alt="">--}}
{{--									</span>--}}
{{--                                            <span class="subject">--}}
{{--									<span class="from">--}}
{{--									Lisa Wong </span>--}}
{{--									<span class="time">Just Now </span>--}}
{{--									</span>--}}
{{--                                            <span class="message">--}}
{{--									Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="inbox.html?a=view">--}}
{{--									<span class="photo">--}}
{{--									<img src="../../assets/admin/layout3/img/avatar3.jpg" class="img-circle" alt="">--}}
{{--									</span>--}}
{{--                                            <span class="subject">--}}
{{--									<span class="from">--}}
{{--									Richard Doe </span>--}}
{{--									<span class="time">16 mins </span>--}}
{{--									</span>--}}
{{--                                            <span class="message">--}}
{{--									Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="inbox.html?a=view">--}}
{{--									<span class="photo">--}}
{{--									<img src="../../assets/admin/layout3/img/avatar1.jpg" class="img-circle" alt="">--}}
{{--									</span>--}}
{{--                                            <span class="subject">--}}
{{--									<span class="from">--}}
{{--									Bob Nilson </span>--}}
{{--									<span class="time">2 hrs </span>--}}
{{--									</span>--}}
{{--                                            <span class="message">--}}
{{--									Vivamus sed nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="inbox.html?a=view">--}}
{{--									<span class="photo">--}}
{{--									<img src="../../assets/admin/layout3/img/avatar2.jpg" class="img-circle" alt="">--}}
{{--									</span>--}}
{{--                                            <span class="subject">--}}
{{--									<span class="from">--}}
{{--									Lisa Wong </span>--}}
{{--									<span class="time">40 mins </span>--}}
{{--									</span>--}}
{{--                                            <span class="message">--}}
{{--									Vivamus sed auctor 40% nibh congue nibh... </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="inbox.html?a=view">--}}
{{--									<span class="photo">--}}
{{--									<img src="../../assets/admin/layout3/img/avatar3.jpg" class="img-circle" alt="">--}}
{{--									</span>--}}
{{--                                            <span class="subject">--}}
{{--									<span class="from">--}}
{{--									Richard Doe </span>--}}
{{--									<span class="time">46 mins </span>--}}
{{--									</span>--}}
{{--                                            <span class="message">--}}
{{--									Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
                    <!-- END INBOX DROPDOWN -->
                    <!-- BEGIN TODO DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-extended dropdown-tasks" id="header_task_bar">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-globe"></i>
                        </a>
                        <ul class="dropdown-menu extended tasks">
                            <li>
                                <a href="{{preg_replace('/'.request()->segment(1).'/', 'ar', strtolower(request()->fullUrl()),1)}}">العربيه</a>
                            </li>
                            <li><a href="{{preg_replace('/'.request()->segment(1).'/', 'en', strtolower(request()->fullUrl()),1)}}">English</a></li>

                        </ul>
                    </li>
                    <!-- END TODO DROPDOWN -->
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <img alt="" class="img-circle" src="{{$currentUser->image ? $currentUser->image : asset('resources/assets/front/img/avatar_placeholder_white.png')}}"/>
                            <span class="username username-hide-on-mobile"> {{$currentUser->name}}  </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="{{route('showUserFront')}}">
                                    <i class="icon-user"></i>  {{trans('admin.my_info')}}  </a>
                            </li>
                            <li class="divider">
                            </li>
                            <li>
                                <a href="{{ route('logout') }}">
                                    <i class="icon-key"></i> {{trans('admin.logout')}} </a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                    <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                    <!-- END QUICK SIDEBAR TOGGLER -->
                </ul>

{{--                <ul class="nav navbar-nav pull-right">--}}
{{--                    <!-- BEGIN NOTIFICATION DROPDOWN -->--}}
{{--                    <!-- BEGIN INBOX DROPDOWN -->--}}
{{--                    <!-- BEGIN USER LOGIN DROPDOWN -->--}}
{{--                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->--}}
{{--                    <li class="dropdown dropdown-user">--}}
{{--                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"--}}
{{--                           data-close-others="true">--}}
{{--                            <img alt="" class="img-circle" src=" {{$currentUser->image ? $currentUser->image : asset('resources/assets/front/img/avatar_placeholder_white.png')}}"/>--}}
{{--                            <span class="username username-hide-on-mobile"> {{$currentUser->name}} </span>--}}
{{--                            <i class="fa fa-angle-down"></i>--}}
{{--                        </a>--}}
{{--                        <ul class="dropdown-menu dropdown-menu-default">--}}
{{--                            @guest--}}
{{--                                <li>--}}
{{--                                    <a href="{{ route('login') }}">--}}
{{--                                        <i class="icon-key"></i> Login </a>--}}
{{--                                </li>--}}
{{--                            @else--}}
{{--                                <li>--}}
{{--                                <a href="{{route('showUserFront')}}">--}}
{{--                                <i class="icon-user"></i> {{trans('admin.my_info')}} </a>--}}
{{--                                </li>--}}
{{--                                <li class="divider"></li>--}}
{{--                                <li><a href="{{ route('logout') }}">--}}
{{--                                        <i class="icon-logout"></i> {{trans('admin.logout')}} </a>--}}
{{--                                </li>--}}
{{--                            @endguest--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <!-- END USER LOGIN DROPDOWN -->--}}
{{--                    <!-- BEGIN QUICK SIDEBAR TOGGLER -->--}}
{{--                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->--}}
{{--                --}}{{--<li class="dropdown dropdown-quick-sidebar-toggler">--}}
{{--                --}}{{--<a href="javascript:;" class="dropdown-toggle">--}}
{{--                --}}{{--<i class="icon-logout"></i>--}}
{{--                --}}{{--</a>--}}
{{--                --}}{{--</li>--}}
{{--                <!-- END QUICK SIDEBAR TOGGLER -->--}}
{{--                </ul>--}}
{{--                <ul class="nav navbar-nav pull-right">--}}
{{--                    <!-- BEGIN NOTIFICATION DROPDOWN -->--}}
{{--                    <!-- BEGIN INBOX DROPDOWN -->--}}
{{--                    <!-- BEGIN USER LOGIN DROPDOWN -->--}}
{{--                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->--}}
{{--                    <li class="dropdown dropdown-user">--}}
{{--                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"--}}
{{--                           data-close-others="true">--}}
{{--                            <i class="fa fa-globe " style="font-size: medium"></i>--}}
{{--                        </a>--}}
{{--                        <ul class="dropdown-menu dropdown-menu-default">--}}
{{--                            <li><a href="{{preg_replace('/'.request()->segment(1).'/', 'ar', strtolower(request()->fullUrl()),1)}}">العربيه</a></li>--}}
{{--                            <li><a href="{{preg_replace('/'.request()->segment(1).'/', 'en', strtolower(request()->fullUrl()),1)}}">English</a></li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <!-- END USER LOGIN DROPDOWN -->--}}
{{--                    <!-- BEGIN QUICK SIDEBAR TOGGLER -->--}}
{{--                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->--}}
{{--                <!-- END QUICK SIDEBAR TOGGLER -->--}}
{{--                </ul>--}}
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN HEADER & CONTENT DIVIDER -->
    <div class="clearfix"></div>
    <!-- END HEADER & CONTENT DIVIDER -->

    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <!-- BEGIN SIDEBAR -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <div class="page-sidebar navbar-collapse collapse">

                <!-- BEGIN SIDEBAR MENU -->
                <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->


                <ul class="page-sidebar-menu  page-header-fixed
{{--@if($gym_system == true) page-sidebar-menu-closed @endif--}}
                        "
                    data-keep-expanded="false" data-auto-scroll="true"
                    data-slide-speed="200" style="padding-top: 20px">
                    <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <li class="sidebar-toggler-wrapper hide">
                        <div class="sidebar-toggler">
                            <span></span>
                        </div>
                    </li>
                    <!-- END SIDEBAR TOGGLER BUTTON -->
                    <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                    @include('generic::Front.layouts.user-side-bar')

                </ul>
                <!-- END SIDEBAR MENU -->
                <!-- END SIDEBAR MENU -->
            </div>
            <!-- END SIDEBAR -->
        </div>
        <!-- END SIDEBAR -->


        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content">
                <!-- BEGIN PAGE HEADER-->
                <!-- BEGIN PAGE BAR -->

                @yield('alert_info')
{{--                @yield('gym_shortcuts')--}}
{{--                @if($gym_system == true)--}}
{{--                    <div class="alert alert-info">--}}
{{--                        <strong>{{trans('admin.hint')}}:</strong>--}}
{{--                        <span>{{trans('admin.trail_version_management_gym')}} <br/><br/>--}}
{{--                        <i class="fa fa-comments fa-1x"></i> <a data-toggle="modal" data-target="#myFeedback">{{trans('admin.feedback_msg')}}</a>--}}
{{--                        </span>--}}
{{--                    </div>--}}
{{--                    <div class="row widget-row">--}}
{{--                        <div class="col-md-4 col-sm-4">--}}
{{--                            <!-- BEGIN WIDGET THUMB -->--}}
{{--                            <a href="{{route('listUserSubscription')}}" class="maher-gym-shortcuts-a" title="{{trans('global.subscriptions')}}">--}}
{{--                                <div class="widget-thumb widget-bg-color-white widget-bg-color-lite-gray text-uppercase margin-bottom-20 " style="padding: 10px;">--}}
{{--                                    --}}{{--<h4 class="widget-thumb-heading">Current Balance</h4>--}}
{{--                                    <div class="widget-thumb-wrap">--}}
{{--                                        <i class="widget-thumb-icon bg-green icon-layers"></i>--}}
{{--                                        <div class="widget-thumb-body">--}}
{{--                                            <div>--}}
{{--                                                <span class="widget-thumb-subtitle col-md-8 col-sm-8">{{trans('global.subscriptions')}}</span>--}}
{{--                                                <a href="{{route('createUserSubscription')}}" title="{{trans('global.subscription_add')}}">--}}
{{--                                                    <span class="col-md-4 col-sm-4 @if($lang == 'ar') text-left @else text-right @endif"><i class="fa fa-plus-square"></i></span>--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                            <span class="widget-thumb-body-stat " style="clear: both" data-counter="counterup" >{{@$subscriptions_shortcut_count}}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                </div>--}}
{{--                            </a>--}}
{{--                            <!-- END WIDGET THUMB -->--}}
{{--                        </div>--}}
{{--                        <div class="col-md-4 col-sm-4">--}}
{{--                            <!-- BEGIN WIDGET THUMB -->--}}
{{--                            <a href="{{route('listUserMember')}}" class="maher-gym-shortcuts-a" title="{{trans('global.members')}}">--}}
{{--                                <div class="widget-thumb widget-bg-color-white widget-bg-color-lite-gray text-uppercase margin-bottom-20 " style="padding: 10px;">--}}
{{--                                    --}}{{--<h4 class="widget-thumb-heading">Weekly Sales</h4>--}}
{{--                                    <div class="widget-thumb-wrap">--}}
{{--                                        <i class="widget-thumb-icon bg-red icon-users"></i>--}}
{{--                                        <div class="widget-thumb-body">--}}
{{--                                            <div>--}}
{{--                                            <span class="widget-thumb-subtitle  col-md-8 col-sm-8">{{trans('global.members')}}</span>--}}
{{--                                                <a href="{{route('createUserMember')}}" title="{{trans('global.add_member')}}">--}}
{{--                                                    <span class="col-md-4 col-sm-4 @if($lang == 'ar') text-left @else text-right @endif"><i class="fa fa-user-plus"></i></span>--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                                <span class="widget-thumb-body-stat" data-counter="counterup"  style="clear:both;">{{@$members_shortcut_count}}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                            <!-- END WIDGET THUMB -->--}}
{{--                        </div>--}}
{{--                        <div class="col-md-4  col-sm-4">--}}
{{--                            <!-- BEGIN WIDGET THUMB -->--}}
{{--                            <a href="{{route('listUserGymOrder')}}" class="maher-gym-shortcuts-a" title="{{trans('global.orders')}}">--}}
{{--                                <div class="widget-thumb widget-bg-color-white widget-bg-color-lite-gray  text-uppercase margin-bottom-20 " style="padding: 10px;">--}}
{{--                                    --}}{{--<h4 class="widget-thumb-heading">Biggest Purchase</h4>--}}
{{--                                    <div class="widget-thumb-wrap">--}}
{{--                                        <i class="widget-thumb-icon bg-purple icon-basket"></i>--}}
{{--                                        <div class="widget-thumb-body">--}}
{{--                                            <span class="widget-thumb-subtitle">{{trans('global.orders')}}</span>--}}
{{--                                            <span class="widget-thumb-body-stat" data-counter="counterup" >{{@$order_shortcut_count}}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                            <!-- END WIDGET THUMB -->--}}
{{--                        </div>--}}
{{--                        <div class="col-md-3">--}}
{{--                            <!-- BEGIN WIDGET THUMB -->--}}
{{--                            <a href="" class="maher-gym-shortcuts-a" title="{{trans('global.reports')}}">--}}
{{--                                <div class="widget-thumb widget-bg-color-white widget-bg-color-lite-gray text-uppercase margin-bottom-20 " style="padding: 10px;">--}}
{{--                                    --}}{{----}}{{--<h4 class="widget-thumb-heading">Average Monthly</h4>--}}
{{--                                    <div class="widget-thumb-wrap">--}}
{{--                                        <i class="widget-thumb-icon bg-blue icon-bar-chart"></i>--}}
{{--                                        <div class="widget-thumb-body">--}}
{{--                                            <span class="widget-thumb-subtitle">{{trans('global.reports')}}</span>--}}
{{--                                            <span class="widget-thumb-body-stat" data-counter="counterup" >0</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                            <!-- END WIDGET THUMB -->--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- Modal -->--}}
{{--                    <div id="myFeedback"  class="modal fade" role="dialog">--}}
{{--                        <div class="modal-dialog">--}}

{{--                            <!-- Modal content-->--}}
{{--                            <div class="modal-content">--}}
{{--                                <div class="modal-header">--}}
{{--                                    <button type="button" class="close" data-dismiss="modal">&times;</button>--}}
{{--                                    <h4 class="modal-title"><i class="fa fa-comments fa-1x"></i> {{trans('global.feedback')}}</h4>--}}
{{--                                </div>--}}
{{--                                <form method="post" action="{{route('feedback')}}">--}}
{{--                                <div class="modal-body">--}}
{{--                                    {{csrf_field()}}--}}
{{--                                    <p><textarea class="form-control" rows="10" name="feedback" id="feedback" required></textarea></p>--}}
{{--                                </div>--}}
{{--                                <div class="modal-footer">--}}
{{--                                    <button type="submit" class="btn btn-default green text-center" >{{trans('global.save')}}</button>--}}
{{--                                </div>--}}
{{--                                </form>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}

                <div class="page-bar">
                    @yield('breadcrumb')

                </div>
                <!-- END PAGE BAR -->
                <!-- BEGIN PAGE TITLE-->
                @yield('content')
            </div>
            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->
        <!-- BEGIN QUICK SIDEBAR -->
        <a href="javascript:;" class="page-quick-sidebar-toggler">
            <i class="icon-login"></i>
        </a>
        <!-- END QUICK SIDEBAR -->
    </div>
    <!-- END CONTAINER -->

    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="page-footer-inner">
            {{--{{date('Y')}} --}}
            {{--&copy; <a target="_blank" href="http://internetplus.biz/">Internetplus</a>--}}
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
    <!-- END FOOTER -->


</div>
</body>
<!-- END CORE PLUGINS -->

<!--[if lt IE 9]>
{{--<script src="{{asset('resources/assets/admin/global/plugins/respond.min.js')}}"></script>--}}
{{--<script src="{{asset('resources/assets/admin/global/plugins/excanvas.min.js')}}"></script>--}}
{{--<script src="{{asset('resources/assets/admin/global/plugins/ie8.fix.min.js')}}"></script>--}}
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="{{asset('resources/assets/admin/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('resources/assets/admin/global/plugins/bootstrap/js/bootstrap.min.js')}}"
        type="text/javascript"></script>
<script src="{{asset('resources/assets/admin/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
<script src="{{asset('resources/assets/admin/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}"
        type="text/javascript"></script>
<script src="{{asset('resources/assets/admin/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
<script src="{{asset('resources/assets/admin/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"
        type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
{{--<script src="{{asset('resources/assets/admin/global/plugins/jquery.pulsate.min.js')}}" type="text/javascript"></script>--}}
{{--<script src="{{asset('resources/assets/admin/global/plugins/jquery-bootpag/jquery.bootpag.min.js')}}"--}}
{{--        type="text/javascript"></script>--}}
{{--<script src="{{asset('resources/assets/admin/global/plugins/holder.js')}}" type="text/javascript"></script>--}}

{{--<script src="{{asset('resources/assets/admin/global/scripts/datatable.js')}}"--}}
{{--        type="text/javascript"></script>--}}

{{--<script src="{{asset('resources/assets/admin/global/plugins/datatables/datatables.min.js')}}"--}}
{{--        type="text/javascript"></script>--}}

{{--<script src="{{asset('resources/assets/admin/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}"--}}
{{--        type="text/javascript"></script>--}}
{{--<script src="{{asset('resources/assets/admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"--}}
{{--        type="text/javascript"></script>--}}

{{--<!-- END PAGE LEVEL PLUGINS -->--}}
{{--<!-- BEGIN THEME GLOBAL SCRIPTS -->--}}
<script src="{{asset('resources/assets/admin/global/scripts/app.min.js')}}" type="text/javascript"></script>
{{--<!-- END THEME GLOBAL SCRIPTS -->--}}
{{--<!-- BEGIN PAGE LEVEL SCRIPTS -->--}}

{{--<script src="{{asset('resources/assets/admin/pages/scripts/table-datatables-buttons.js')}}"--}}
{{--        type="text/javascript"></script>--}}

{{--<script src="{{asset('resources/assets/admin/pages/scripts/ui-general.min.js')}}" type="text/javascript"></script>--}}
{{--<!-- END PAGE LEVEL SCRIPTS -->--}}
{{--<!-- BEGIN THEME LAYOUT SCRIPTS -->--}}
<script src="{{asset('resources/assets/admin/layouts/layout/scripts/layout.min.js')}}" type="text/javascript"></script>
{{-- --}}


{{--<script src="{{asset('resources/assets/admin/layouts/layout/scripts/demo.min.js')}}" type="text/javascript"></script>--}}
{{--<script src="{{asset('resources/assets/admin/layouts/global/scripts/quick-sidebar.min.js')}}"--}}
{{--        type="text/javascript"></script>--}}
{{--<script src="{{asset('resources/assets/admin/layouts/global/scripts/quick-nav.min.js')}}"--}}
{{--        type="text/javascript"></script>--}}
<!-- END THEME LAYOUT SCRIPTS -->



{{--<script src="{{asset('resources/assets/admin/global/plugins/select2/js/select2.full.min.js')}}"--}}
{{--        type="text/javascript"></script>--}}
{{--<script src="{{asset('resources/assets/admin/pages/scripts/components-select2.js')}}" type="text/javascript"></script>--}}

<!-- BEGIN Sweet Alert SCRIPTS -->
<link href="{{asset('resources/assets/admin/global/plugins/sweet-alerts/sweetalert_2.css')}}"
      rel="stylesheet"
      type="text/css"/>
<script src="{{asset('resources/assets/admin/global/plugins/sweet-alerts/sweetalert_2.js')}}"
        type="text/javascript"></script>
@include('generic::flash')
@include('generic::new_notifications')
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/5c404111ab5284048d0d571d/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<!--End of Tawk.to Script-->
@yield('scripts')

<script>
    //    $(document).ready(function() {
    //        $('#sample_3').DataTable( {
    //            "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
    //        } );
    //    } );
</script>


</html>
