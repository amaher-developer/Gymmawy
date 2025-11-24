@php
    $identifier = request()->segment(3);
@endphp

<style>
    .sub-menu span{
        font-size: 13px;
    }


    .app-sidebar__user {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        color: #a8a8a8;
        width: 100%;
        display: inline-block;
        background-size: cover;
        background-position: left;
    }
    .app-sidebar__user .user-pro-body {
        display: block;
        padding: 15px 0;
    }

    .app-sidebar__user .user-pro-body img {
        display: block;
        margin: 0 auto 0px;
        border: 2px solid #c9d2e8;
        box-shadow: 0px 5px 5px 0px rgba(44, 44, 44, 0.2);
        padding: 3px;
        background: #fff;
    }

    .brround {
        border-radius: 50%;
    }

    .avatar-status {
        content: '';
        position: absolute;
        bottom: 0;
        left: 5px;
        width: 6px;
        height: 6px;
        background-color: #949eb7;
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.95);
        border-radius: 100%;
        bottom: 4px;
    }
    .profile-status {
        content: '' !important;
        position: absolute !important;
        bottom: 0 !important;
        left: 103px !important;
        width: 12px !important;
        height: 12px !important;
        background-color: #22c03c !important;
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.95) !important;
        border-radius: 100% !important;
        top: 73px;
        animation: pulse 2s infinite !important;
        animation-duration: .9s;
        animation-iteration-count: infinite;
        animation-timing-function: ease-out;
        border: 2px solid #fff;
    }

    .avatar {
        position: relative;
        width: 36px;
        height: 36px;
        border-radius: 100% !important;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        font-size: 16px;
        background-color: #0162e8;
        object-fit: cover;
        /*width: 60px;*/
        /*height: 60px;*/
    }
    .bg-green {
        background: #32c5d2!important;
    }
    .app-sidebar__user .user-info {
        margin: 0 auto;
        text-align: center;
    }
    .user-info {
        margin-bottom: 10px!important;
    }
    .app-sidebar__user .user-info h4 {
        font-size: 15px;
    }

    .avatar-xl {
        width: 72px !important;
        height: 72px !important;
        font-size: 36px !important;
    }
    .user-info h4 {
        margin-top: 1rem !important;
        font-weight: 500 !important;
    }
    .user-info span {
        color: #f2f5fb !important;
        font-size: 13px;
    }

    .page-sidebar-closed .page-sidebar .page-sidebar-menu.page-sidebar-menu-closed  .app-sidebar__user {
        display: none;
    }
</style>


<div class="app-sidebar__user ">
    <div class="dropdown user-pro-body">
        <div class="">
            <img alt="user-img" class="avatar avatar-xl brround mCS_img_loaded" src="{{$currentUser->image ? $currentUser->image : asset('resources/assets/front/img/default.jpg')}}" >
            <span class="avatar-status profile-status bg-green"></span>
        </div>
        <div class="user-info">
            <h4 class="font-weight-semibold mt-3 mb-0" >{{$currentUser->name}}</h4>
{{--            <span class="mb-0 text-muted" >{{trans('sw.member')}}</span>--}}
        </div>
    </div>
</div>

<li class="nav-item start @if(Request::is($lang.'/user')) active open @endif">
    <a href="{{route('dashboard')}}" class="nav-link nav-toggle">
        <i class="icon-home"></i>
        <span class="title">{{trans('admin.home')}}</span>
    </a>
</li>
<li class="nav-item @if(Request::is($lang.'/user/show') || Request::is($lang.'/user/edit') || Request::is($lang.'/user/update_password') ) active open @endif">
    <a href="{{route('showUserFront')}}" class="nav-link nav-toggle">
        <i class="icon-user"></i>
        <span class="title">{{trans('admin.my_info')}}</span>
    </a>
</li>
<li class="nav-item @if(Request::is($lang.'/user/article*')) active open @endif">
    <a href="{{route('listUserArticle')}}" class="nav-link nav-toggle">
        <i class="icon-note"></i>
        <span class="title">{{trans('admin.my_articles')}}</span>
    </a>
</li>
<li class="nav-item  @if(Request::is($lang.'/user/trainer*')) active open @endif">
    <a href="{{route('showUserTrainer')}}" class="nav-link nav-toggle">
        <i class="icon-shield"></i>
        <span class="title">{{trans('admin.trainer')}}</span>
    </a>
</li>

<li class="nav-item @if(Request::is($lang.'/user/gym/show') || Request::is($lang.'/user/gym/edit')) active open @endif">
    <a href="{{route('showUserGymBrand')}}" class="nav-link nav-toggle">
        <i class=" icon-fire"></i>
        <span class="title">{{trans('admin.gym')}}</span>
    </a>
</li>
{{--<li class="nav-item start">--}}
{{--    <a href="{{route('listUserStore')}}" class="nav-link nav-toggle">--}}
{{--        <i class="icon-basket"></i>--}}
{{--        <span class="title">{{trans('admin.my_store')}}</span>--}}
{{--    </a>--}}
{{--</li>--}}


{{--@if(\Illuminate\Support\Facades\Auth::user()->gym)--}}

{{--<li aria-haspopup="true" class="nav-item @if(Request::is($lang.'/user/gym/subscription*') || Request::is($lang.'/user/gym/member*') || Request::is($lang.'/user/gym/order*')) active open @endif">--}}
{{--    <a href="javascript:;"--}}
{{--       class="nav-link nav-toggle @if(Request::is($lang.'/user/gym/subscription*') || Request::is($lang.'/user/gym/member*') || Request::is($lang.'/user/gym/order*')) open @endif">--}}
{{--        <i class="icon-settings"></i>--}}
{{--        <span class="title"> {{trans('global.gym_management')}}</span>--}}
{{--        <span class="selected"></span>--}}
{{--        <span class="arrow @if(Request::is($lang.'/user/gym/subscription*') || Request::is($lang.'/user/gym/member*') || Request::is($lang.'/user/gym/order*')) open @endif"></span>--}}
{{--    </a>--}}


{{--    <ul class="sub-menu">--}}
{{--        <li class="nav-item  {{Request::is($lang.'/user/gym/subscription*') ? 'active open' : '' }}">--}}
{{--            <a href="{{route('sw.listSubscription')}}" class="nav-link ">--}}
{{--                <i class="icon-notebook "></i>--}}
{{--                <span class="title">{{trans('global.subscriptions')}}</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item  {{Request::is($lang.'/user/gym/member*') ? 'active open' : '' }}">--}}
{{--            <a href="{{route('sw.listMember')}}" class="nav-link ">--}}
{{--                <i class="icon-notebook "></i>--}}
{{--                <span class="title">{{trans('global.members')}}</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item  {{Request::is($lang.'/user/gym/order*') ? 'active open' : '' }}">--}}
{{--            <a href="{{route('sw.listGymOrder')}}" class="nav-link ">--}}
{{--                <i class="icon-notebook "></i>--}}
{{--                <span class="title">{{trans('global.orders')}}</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        --}}
{{--        --}}
{{--        --}}
{{--        --}}
{{--        <li class="nav-item  {{Request::is('user/gym/report*') ? 'font-green' : '' }}">--}}
{{--            <a href="{{route('listUserReport')}}}" class="nav-link ">--}}
{{--                <i class="icon-notebook "></i>--}}
{{--                <span class="title">{{trans('global.reports')}}</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li aria-haspopup="true" class="{{Request::is('user/gym/subscription*') ? 'act_item' : ''}}"><a class="menu_title nav-link" href="{{route('listUserSubscription')}}">{{trans('global.subscriptions')}}</a></li>--}}
{{--        <li aria-haspopup="true" class="{{Request::is('user/gym/member*') ? 'act_item' : ''}}"><a class="menu_title nav-link" href="{{route('listUserMember')}}">{{trans('global.members')}}</a></li>--}}
{{--        <li aria-haspopup="true" class="{{Request::is('user/gym/order*') ? 'act_item' : ''}}"><a class="menu_title nav-link" href="{{route('listUserGymOrder')}}">{{trans('global.orders')}}</a></li>--}}
{{--        --}}{{--                                        <li aria-haspopup="true" class="{{Request::is('user/report*') ? 'act_item' : ''}}"><a class="menu_title nav-link" href="{{route('listUserReport')}}">{{trans('global.reports')}}</a></li>--}}
{{--    </ul>--}}
{{--</li>--}}
{{--@endif--}}
<!-- END MEGA MENU -->