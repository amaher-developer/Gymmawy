<!doctype html>
<!--[if lte IE 9]>
<html class="lte-ie9" lang="{{ config('app.locale') }}" dir="rtl"  <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="{{ config('app.locale') }}" dir="rtl"> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if(isset($title)) {{$title}}  |@endif {{ @$mainSettings->name }} </title>
    <meta name="keywords" content="{{@$mainSettings->name}}, {!!  @$mainSettings->keywords !!}" />
    <meta name="description" content="{{@$mainSettings->name}}, {{ @$mainSettings->description }}" />

    @yield('meta')


    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/logo_icon.ico') }}">
    {{--<link rel="icon" type="image/png" href="{{ asset('resources/assets/img/favicon-16x16.png')}}" sizes="16x16">--}}
    {{--<link rel="icon" type="image/png" href="{{ asset('resources/assets/img/favicon-32x32.png')}}" sizes="32x32">--}}


    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500' rel='stylesheet' type='text/css'>

    <!-- uikit rtl -->
    <link rel="stylesheet" href="{{ asset('resources/assets/css/uikit.rtl.css')}}" media="all">

    <!-- altair admin login page -->
    <link rel="stylesheet" href="{{ asset('resources/assets/css/login_page.css')}}"/>

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    @yield('style')

</head>
<body class="login_page">

@if (count($errors) > 0)
    <div class="uk-width-1-1">
        <div class="uk-alert uk-alert-danger" data-uk-alert>
            <a href="#" class="uk-alert-close uk-close"></a>

            <ol>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ol>
        </div>
    </div>
@elseif(session('success'))
    <div class="uk-width-1-1">
        <div class="uk-alert uk-alert-success" data-uk-alert>
            <a href="#" class="uk-alert-close uk-close"></a>
            {{ session('success') }}
        </div>
    </div>
@endif

@foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(request()->session()->has('alert-' . $msg))
        <div class="alert alert-{{ $msg }}">{{ request()->session()->get('alert-' . $msg) }}
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        </div>
    @endif
@endforeach

@yield('content')


{{--@yield('footer')--}}
@section('footer')
@show

<link href="{{ asset('js/app.js') }}" rel="stylesheet">

@yield('script')


<!-- common functions -->
<script src="{{ asset('resources/assets/js/common.min.js')}}"></script>
<!-- uikit functions -->
<script src="{{ asset('resources/assets/js/uikit_custom.min.js')}}"></script>
<!-- altair core functions -->
<script src="{{ asset('resources/assets/js/altair_admin_common.min.js')}}"></script>

<!-- altair login page functions -->
<script src="{{ asset('resources/assets/js/pages/login.min.js')}}"></script>

<script>
    // check for theme
    if (typeof(Storage) !== "undefined") {
        var root = document.getElementsByTagName('html')[0],
            theme = localStorage.getItem("altair_theme");
        if (theme == 'app_theme_dark' || root.classList.contains('app_theme_dark')) {
            root.className += ' app_theme_dark';
        }
    }
</script>

</body>
</html>
