<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" @if($lang=='ar') dir="rtl" @else dir="ltr" @endif>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="@if(isset($title)) {{$title}} |@endif {{ @$mainSettings->name }}">
    <title>@if(isset($title)) {{$title}} |@endif {{ @$mainSettings->name }}</title>

    <meta name="keywords" content="{{@$mainSettings->name}}, {!!  @$mainSettings->meta_keywords !!} {{@$metaKeywords}}"/>
    <meta name="description" content="{{@$mainSettings->name}}, {{ @$metaDescription ?? $mainSettings->meta_description }}"/>
    <meta name="robots" content="index, follow" />

{{--    <link rel="canonical" href="{{ url()->current() }}" />--}}

{{--    <link rel="alternate" href="{{url('/ar')}}" hreflang="x-default">--}}
    <link rel="alternate" href="{{preg_replace('/'.request()->segment(1).'/', 'ar', strtolower(request()->fullUrl()),1)}}" hreflang="ar" />
    <link rel="alternate" href="{{preg_replace('/'.request()->segment(1).'/', 'en', strtolower(request()->fullUrl()),1)}}" hreflang="en" />
    <meta http-equiv="Content-Language" content="{{$lang}}" />
    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content=" @if(isset($title)) {{$title}} |@endif {{ @$mainSettings->name }}">
    <meta itemprop="description" content="{{@$mainSettings->name}}, {{ @$metaDescription ?? @$title.', '.$mainSettings->meta_description }}">
    <meta itemprop="image" content="@if(@($metaImage)){{$metaImage}}@else{{asset('resources/assets/front/img/logo/default.png')}}@endif">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content=" @if(isset($title)) {{$title}} |@endif {{ @$mainSettings->name }}">
    <meta property="og:description" content="{{@$mainSettings->name}}, {{ @$metaDescription ?? $mainSettings->meta_description }}">
    <meta property="og:image" content="@if(@($metaImage)){{$metaImage}}@else{{asset('resources/assets/front/img/logo/default.png')}}@endif">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content=" @if(isset($title)) {{$title}} |@endif {{ @$mainSettings->name }}">
    <meta name="twitter:description" content="{{@$mainSettings->name}}, {{ @$metaDescription ?? $mainSettings->meta_description }}">
    <meta name="twitter:image" content="@if(@($metaImage)){{$metaImage}}@else{{asset('resources/assets/front/img/logo/default.png')}}@endif">

    <meta name="robots" content="index, follow"/>
    <meta name="Googlebot" content="index, follow"/>
    <meta name="FAST-WebCrawler" content="index, follow"/>
    <meta name="Scooter" content="index, follow"/>
    <meta name="GOOGLEBOT" content="NOODP"/>
    <meta name="revisit-after" content="daily"/>
    <meta name="allow-search" content="yes"/>
    <meta name="msnbot" content="INDEX, FOLLOW"/>
    <meta name="YahooSeeker" content="INDEX, FOLLOW"/>
    <meta name="rating" content="general"/>
    <meta name="robots" content="all"/>
    <meta http-equiv="Cache-control" content="public"/>

{{--    <meta name="robots" content="noindex">--}}
{{--    <meta name="robots" content="nofollow">--}}
{{--    <meta name="googlebot" content="noindex">--}}


@yield('meta')

<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicons-->
    <link rel="shortcut icon" href="{{asset('resources/assets/front/img/logo/favicon.ico')}}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{asset('resources/assets/front/img/logo/favicon.ico')}}" type="image/x-icon"/>

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800" rel="preload">

    <!-- BASE CSS -->

    @if($lang=='ar')
        <link href="{{asset('resources/assets/front/css/bootstrap-rtl.min.css')}}" rel="stylesheet">
    @else
        <link href="{{asset('resources/assets/front/css/bootstrap.min.css')}}" rel="stylesheet">
    @endif
    <link href="{{asset('resources/assets/front/css/style.min.css')}}" rel="stylesheet">

    @if($lang=='ar')
        <link href="{{asset('resources/assets/front/css/style-rtl.css')}}" rel="stylesheet">
    @endif
    <link href="{{asset('resources/assets/front/css/vendors.min.css')}}" rel="stylesheet preload">

    <!-- YOUR CUSTOM CSS -->
    <link href="{{asset('resources/assets/front/css/custom.css')}}" rel="stylesheet">

    <style>
        .required {
            color: #e02222;
        }
        body {
            font-size: 1.00rem;
        }
    </style>

    @yield('style')

    @if(!env('local'))
<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-180323439-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-180323439-1');
    </script>


    <!-- Global site  - Google Adsensce -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8099437480361514"
            crossorigin="anonymous"></script>
    @endif
</head>

<body @if($lang=='ar') class="rtl" @else class="ltr" @endif >

<div id="page">

    <header class="header menu_fixed">
        {{--        <div id="preloader">--}}
        {{--            <div data-loader="circle-side"></div>--}}
        {{--        </div><!-- /Page Preload -->--}}
        <div id="logo">
            <a href="{{asset($lang)}}">
                <img src="{{$mainSettings->logo_white}}" width="" height="36" data-retina="true"
                     alt="{{$mainSettings->name}}" class="logo_normal">
                <img src="{{$mainSettings->logo}}" width="" height="36"
                     data-retina="true" alt="{{$mainSettings->name}}" class="logo_sticky">
            </a>
        </div>
        <ul id="top_menu">
            {{--            <li><a href="cart-1.html" class="cart-menu-btn" title="Cart"><strong>4</strong></a></li>--}}
            <li><a id="login_btn"  @if($currentUser) href="{{route('dashboard')}}"  class="login " @else href="#sign-in-dialog"  class="login sign-in-form"  title="{{trans('global.login')}}" @endif>{{trans('global.login')}}</a></li>
            <li><a @if($currentUser) href="{{route('favorites')}}" @endif class="wishlist_bt_top"
                   title="{{trans('global.wishlist')}}">{{trans('global.wishlist')}}</a></li>
        </ul>
        <!-- /top_menu -->
        <a href="#menu" class="btn_mobile">
            <div class="hamburger hamburger--spin" id="hamburger">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </div>
        </a>
        <nav id="menu" class="main-menu">
            <ul>

                <li><span><a href="{{asset($lang)}}">{{trans('global.home')}}</a></span></li>
                <li><span><a href="{{route('gyms')}}">{{trans('global.gyms')}}</a></span></li>
                <li><span><a href="{{route('trainers')}}">{{trans('global.trainers')}}</a></span></li>
                <li><span><a href="{{route('articles')}}">{{trans('global.articles')}}</a></span></li>
                <li><span><a href="{{route('asks')}}">{{trans('global.questions')}}</a></span></li>
{{--                <li><span><a href="{{route('login')}}">{{trans('global.login')}}</a></span></li>--}}
                <li><span><a href="{{route('contact')}}">{{trans('global.contact_us')}}</a></span></li>
            </ul>
        </nav>
    </header>
    <!-- /header -->


    @yield('content')


    @if((\Request::route()->getName() != 'gyms') && (\Request::route()->getName() != 'trainers'))
    <footer>
        <div class="container margin_60_35">
            <div class="row">
                <div class="col-lg-5 col-md-12 p-r-5">
                    <p><img src="{{$mainSettings->logo_white}}" width="" height="36"
                            data-retina="true" alt="{{$mainSettings->name}}"></p>
                    <p>{{trans('global.footer_about_msg')}}</p>
                    <div class="follow_us">
                        <ul>
                            <li>{{trans('global.follow_us')}}</li>
                            @if(@$mainSettings->facebook)<li><a href="{{@$mainSettings->facebook}}"><i class="ti-facebook"></i></a></li>@endif
                            @if(@$mainSettings->twitter)<li><a href="{{@$mainSettings->twitter}}"><i class="ti-twitter-alt"></i></a></li>@endif
                            @if(@$mainSettings->google)<li><a href="{{@$mainSettings->google}}"><i class="ti-google"></i></a></li>@endif
                            @if(@$mainSettings->pinterest)<li><a href="{{@$mainSettings->pinterest}}"><i class="ti-pinterest"></i></a></li>@endif
                            @if(@$mainSettings->instagram)<li><a href="{{@$mainSettings->instagram}}"><i class="ti-instagram"></i></a></li>@endif
                        </ul>
                    </div>

                    <h5>{{trans('global.ask_gymmawy')}}</h5>
                    <ul class="contacts">
                        <li><a href="{{route('createQuestionAsk')}}" class="ask-href"><i class="icon-question"></i> {{trans('global.add_question')}}</a>&nbsp;&nbsp;&nbsp;| <a href="{{route('asks')}}" class="ask-href"><i class="icon-list"></i> {{trans('global.questions')}}</a></li>
                    </ul>

                </div>
                <div class="col-lg-3 col-md-6 ml-lg-auto">
                    <h5>{{trans('global.links')}}</h5>
                    <ul class="links">
{{--                        <li><a href="{{trans('about')}}">{{trans('global.about')}}</a></li>--}}
                        <li><a href="{{route('register')}}">{{trans('global.register')}}</a></li>
                        <li><a href="{{route('login')}}">{{trans('global.login')}}</a></li>
                        <li><a href="{{route('gyms')}}">{{trans('global.gyms')}}</a></li>
                        <li><a href="{{route('trainers')}}">{{trans('global.trainers')}}</a></li>
                        <li><a href="{{route('articles')}}">{{trans('global.articles')}}</a></li>
                        <li><span><a href="{{route('asks')}}">{{trans('global.questions')}}</a></span></li>
                        <li><a href="{{route('bodybuilders')}}">{{trans('global.bodybuilders')}}</a></li>
                        <li><a href="{{route('contact')}}">{{trans('global.contact_us')}}</a></li>
                        <li><a href="https://demo.gymmawy.com" title="{{trans('global.gym_managment_system')}}" >{{trans('global.gym_managment_system')}}</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    {{--                    <h5>{{trans('global.contact')}}</h5>--}}
                    {{--                    <ul class="contacts">--}}
                    {{--                        <li><a href="tel://61280932400"><i class="ti-mobile"></i> + 61 23 8093 3400</a></li>--}}
                    {{--                        <li><a href="mailto:info@Panagea.com"><i class="ti-email"></i> info@Panagea.com</a></li>--}}
                    {{--                    </ul>--}}

                    <div class="row col-md-12">
                        <div class="col-md-12">
                            <a href="{{$mainSettings->android_app}}"  target="_blank" >
                                <img src="{{asset('resources/assets/front/')}}/img/play_store_icon.png" style="height: 100px;object-fit: contain;text-align: center" alt="{{trans('global.app_gymmawy')}}" title="{{trans('global.app_gymmawy')}}">
                            </a>
                        </div>
                        <div class="col-md-12">
                            <a href="{{$mainSettings->ios_app}}"  target="_blank" >
                                <img src="{{asset('resources/assets/front/')}}/img/apple_store_icon.png" style="height: 100px;object-fit: contain;text-align: center" alt="{{trans('global.app_gymmawy')}}" title="{{trans('global.app_gymmawy')}}">
                            </a>
                        </div>
                        <div class="col-md-12">
                            <a href="https://demo.gymmawy.com"  target="_blank" >
                                <img src="{{asset('resources/assets/front/')}}/img/system_icon.png" style="height: 100px;object-fit: contain;text-align: center" alt="{{trans('global.gym_managment_system')}}" title="{{trans('global.gym_managment_system')}}">
                            </a>
                        </div>
                    </div>
                    <div style="clear: both;float: none"></div>
                    <div id="newsletter">
                        <h6>{{trans('global.newsletter')}}</h6>
                        <div id="message-newsletter"
                             style="padding-bottom: 20px">{{trans('global.footer_newsletter_msg')}}</div>
                        <form method="post" action="{{route('newsletterSubscribe')}}" name="newsletter_form" id="newsletter_form">
                            <div class="form-group">
                                <input type="hidden" name="_token" id="token_newsletter" value="{{csrf_token()}}">
                                <input type="email" name="email_newsletter" id="email_newsletter" class="form-control"
                                       placeholder="{{trans('global.email')}}">
                                <input type="submit" value="{{trans('global.register_now')}}" id="submit-newsletter">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <!--/row-->
            <hr>
            <div class="row">
                <div class="col-lg-6">
                    <ul id="footer-selector">
                        <li>
                            <div class="styled-select" id="lang-selector">
                                <select id="change_language">
                                    <option value="en" @if($lang == 'en') selected @endif>English</option>
                                    <option value="ar" @if($lang == 'ar') selected @endif>عربي</option>
                                    {{--                                    <option value="Spanish">Spanish</option>--}}
                                    {{--                                    <option value="Russian">Russian</option>--}}
                                </select>
                            </div>
                        </li>
                        {{--                        <li>--}}
                        {{--                            <div class="styled-select" id="currency-selector">--}}
                        {{--                                <select>--}}
                        {{--                                    <option value="US Dollars" selected>US Dollars</option>--}}
                        {{--                                    <option value="Euro">Euro</option>--}}
                        {{--                                </select>--}}
                        {{--                            </div>--}}
                        {{--                        </li>--}}
{{--                        <li><img src="{{asset('resources/assets/front/img/cards_all.svg')}}" alt=""></li>--}}
                    </ul>
                </div>
                <div class="col-lg-6">
                    <ul id="additional_links">
                        <li><a href="{{route('about')}}">{{trans('global.about')}}</a></li>
                        <li><a href="{{route('terms')}}">{{trans('global.terms')}}</a></li>
                        <li><a href="{{route('policy')}}">{{trans('global.policy')}}</a></li>
                        <li><span>{{trans('global.copy_right')}}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!--/footer-->
        @endif
</div>
<!-- page -->

<!-- Sign In Popup -->
<div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide">
    <div class="small-dialog-header">
        <h3>{{trans('global.login')}}</h3>
    </div>
        <div class="sign-in-wrapper">
            <a href="{{route('socialLogin').'?provider=facebook'}}" class="social_bt facebook">{{trans('global.login_facebook')}}</a>
{{--            <a href="{{route('socialLogin').'?provider=twitter'}}" class="social_bt twitter"><i class="fa fa-twitter"></i>{{trans('global.login_twitter')}}</a>--}}
            <a href="{{route('socialLogin').'?provider=google'}}" class="social_bt google">{{trans('global.login_google')}}</a>
            <div class="divider"><span>{{trans('global.or')}}</span></div>

            <form action="{{route('login')}}" method="post">
                {{csrf_field()}}
            <div class="form-group">
                <label>{{trans('global.email')}}</label>
                <input type="email" class="form-control" name="email" id="email">
                <i class="icon_mail_alt"></i>
            </div>
            <div class="form-group">
                <label>{{trans('global.password')}}</label>
                <input type="password" class="form-control" name="password" id="password" value="">
                <i class="icon_lock_alt"></i>
            </div>
            <div class="clearfix add_bottom_15">
                <div class="checkboxes float-left">
                    <label class="container_check">{{trans('global.remember_me')}}
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="float-right mt-1"><a
{{--                                                id="forgot"--}}
                                                 href="{{ route('password.request') }}">{{trans('global.forgot_password')}}</a>
                </div>
            </div>
            <div class="text-center"><input type="submit" value="{{trans('global.login')}}" class="btn_1 full-width">
            </div>
            </form>
            <div class="text-center">
                {{trans('global.account_not_found')}} <a href="{{route('register')}}">{{trans('global.sign_up')}}</a>
            </div>
            <form action="{{ route('password.email') }}" method="post">
                {{csrf_field()}}
            <div id="forgot_pw">
                <div class="form-group">
                    <label>{{trans('global.forgot_password')}}</label>
                    <input type="email" class="form-control" name="email_forgot" id="email_forgot">
                    <i class="icon_mail_alt"></i>
                </div>
                <p>{{trans('global.forgot_password_msg')}}</p>
                <div class="text-center"><input type="submit" value="{{trans('global.reset_password')}}" class="btn_1">
                </div>
            </div>
            </form>
        </div>
    <!--form -->
</div>
<!-- /Sign In Popup -->

<div id="toTop"></div><!-- Back to top button -->


{{--@include('generic::Front.pages.__ads_training')--}}

<!-- COMMON SCRIPTS -->
<script src="{{asset('resources/assets/front/js/jquery-2.2.4.min.js')}}"></script>
<script src="{{asset('resources/assets/front/js/common_scripts.min.js')}}"></script>
@if((request()->is($lang.'/asks*')) || (request()->is($lang.'/ask*')))
<script>
    sticky_nav= $('.ask-href');
    sticky_nav.on('click', function(e){
        var href = e.attr('href');
        return true;
    });
</script>
@endif
<script src="{{asset('resources/assets/front/js/main_rtl.min.js')}}"></script>
<script src="{{asset('resources/assets/front/assets/validate.js')}}"></script>


<!-- DATEPICKER  -->
<script>
    function addFavorite(id, type) {
        @if(!$currentUser)
        document.getElementById('login_btn').click();
        return true;
        @else
        $.ajax({
            url: '{{route('addFavoriteByAjax')}}',
            method: "POST",
            data: {id: id, type: type, _token: "{{csrf_token()}}"},
            dataType: "text",
            success: function (data) {
                document.getElementById("favorite_" + type + "_" + id).setAttribute("onClick", "removeFavorite(" + id + ", " + type + ")");
                document.getElementById("favorite_" + type + "_" + id).classList.add('liked');
                // removeFavoriteText(id, type);

            }
        });
        @endif
    }

    function removeFavorite(id, type) {
        $.ajax({
            url: '{{route('removeFavoriteByAjax')}}',
            method: "POST",
            data: {id: id, type: type, _token: "{{csrf_token()}}"},
            dataType: "text",
            success: function (data) {
                document.getElementById("favorite_" + type + "_" + id).setAttribute("onClick", "addFavorite(" + id + ", " + type + ")");
                document.getElementById("favorite_" + type + "_" + id).classList.remove('liked');
                // addFavoriteText(id, type);
            }
        });
    }
    function removeFavoriteText(id, type) {
        $("#favorite_" + type + "_" + id).html("<i class='icon-heart-empty'></i> {{trans('global.add_to_favourites')}}");
    }
    function addFavoriteText(id, type) {
        $("#favorite_" + type + "_" + id).html("<i class='icon_heart'></i> {{trans('global.remove_from_favourites')}}");
    }

    $(function () {
        'use strict';
        $('input[name="dates"]').daterangepicker({
            autoUpdateInput: false,
            opens: 'left',
            locale: {
                direction: 'rtl',
                cancelLabel: 'Clear'
            }
        });
        $('input[name="dates"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MM-DD-YY') + ' > ' + picker.endDate.format('MM-DD-YY'));
        });
        $('input[name="dates"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    });
</script>


<script>


    $(document).ready(function () {

        // get your select element and listen for a change event on it
        $('#change_language').change(function () {
            // set the window's location property to the value of the option the user has selected
            change_language = $('#change_language').val();
            //alert(city_agents);
            if (change_language == 'ar')
                window.location = "{{preg_replace('/'.request()->segment(1).'/', 'ar', strtolower(request()->fullUrl()),1)}}";
            else
                window.location = "{{preg_replace('/'.request()->segment(1).'/', 'en', strtolower(request()->fullUrl()),1)}}";
        });

    });
</script>

<!-- INPUT QUANTITY  -->
<script src="{{asset('resources/assets/front/js/input_qty.js')}}"></script>

<!-- implementation for web notification -->
@include('generic::layouts.fcm-web-notification')

@yield('script')

</body>

</html>
