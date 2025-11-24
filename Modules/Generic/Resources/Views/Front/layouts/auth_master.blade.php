<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

{{--    <link rel="canonical" href="{{ url()->current() }}" />--}}

    {{--    <link rel="alternate" href="{{url('/ar')}}" hreflang="x-default">--}}
    <link rel="alternate" href="{{preg_replace('/'.request()->segment(1).'/', 'ar', strtolower(request()->fullUrl()),1)}}" hreflang="ar" />
    <link rel="alternate" href="{{preg_replace('/'.request()->segment(1).'/', 'en', strtolower(request()->fullUrl()),1)}}" hreflang="en" />
    <meta http-equiv="Content-Language" content="{{$lang}}" />

    <meta name="author" content="@if(isset($title)) {{$title}}  |@endif {{ @$mainSettings->name }}">
    <title>@if(isset($title)) {{$title}}  |@endif {{ @$mainSettings->name }} </title>
    <meta name="keywords" content="{{@$mainSettings->name}}, {!!  @$mainSettings->meta_keywords !!} {{@$metaKeywords}}"/>
    <meta name="description" content="{{@$mainSettings->name}}, {{ @$metaDescription ?? @$title.', '.$mainSettings->meta_description }}"/>

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


@yield('meta')

    <!-- Favicons-->
    <link rel="shortcut icon" href="{{asset('resources/assets/front/img/logo/favicon.ico')}}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{asset('resources/assets/front/img/logo/favicon.ico')}}" type="image/x-icon"/>

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800" rel="stylesheet">

    <!-- BASE CSS -->
    @if($lang == 'ar')
        <link href="{{asset('resources/assets/front/css/bootstrap-rtl.min.css')}}" rel="stylesheet">
    @else
        <link href="{{asset('resources/assets/front/css/bootstrap.min.css')}}" rel="stylesheet">
    @endif

    <link href="{{asset('resources/assets/front/css/style.css')}}" rel="stylesheet">

    @if($lang == 'ar')
        <link href="{{asset('resources/assets/front/css/style-rtl.css')}}" rel="stylesheet">
    @endif
    <link href="{{asset('resources/assets/front/css/vendors.css')}}" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="{{asset('resources/assets/front/css/custom.css')}}" rel="stylesheet">

    <style>
        .required {
            color: #e02222;
        }
    </style>

    @yield('style')

<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-180323439-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-180323439-1');
    </script>
</head>

<body id="login_bg" class="rtl">

<nav id="menu" class="fake_menu"></nav>

<div id="preloader">
    <div data-loader="circle-side"></div>
</div>
<!-- End Preload -->

@yield('content')

<!-- COMMON SCRIPTS -->
<script src="{{asset('resources/assets/front/js/jquery-2.2.4.min.js')}}"></script>
{{--<script src="{{asset('resources/assets/front/js/common_scripts.js')}}"></script>--}}
<script src="{{asset('resources/assets/front/js/main_rtl.js')}}"></script>
<script src="{{asset('resources/assets/front/assets/validate.js')}}"></script>

@yield('script')
</body>
</html>
