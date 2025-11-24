<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Site Launch Coming soon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicons-->
    <link rel="shortcut icon" href="{{asset('resources/assets/front/img/favicon.ico')}}" type="image/x-icon"/>
    <link rel="apple-touch-icon" type="image/x-icon" href="{{asset('resources/assets/front/img/apple-touch-icon-57x57-precomposed.png')}}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{asset('resources/assets/front/img/apple-touch-icon-72x72-precomposed.png')}}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{asset('resources/assets/front/img/apple-touch-icon-114x114-precomposed.png')}}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{asset('resources/assets/front/img/apple-touch-icon-144x144-precomposed.png')}}">

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800" rel="stylesheet">

    <!-- CSS -->
    <link href="{{asset('resources/assets/front/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('resources/assets/front/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('resources/assets/front/fontello/css/fontello.css')}}" rel="stylesheet" >
    <link href="{{asset('resources/assets/front/fontello/css/animation.css')}}" rel="stylesheet" >

</head>
<body>

@yield('content')

<div id="slides">
    <ul class="slides-container">
        <li><img src="{{asset('resources/assets/front/img/slide_2.jpg')}}" alt=""></li>
        <li><img src="{{asset('resources/assets/front/img/slide_1.jpg')}}" alt=""></li>
    </ul>
</div><!-- End background slider -->

<!-- JQUERY -->
<script src="{{asset('resources/assets/front/js/jquery-2.2.4.min.js')}}"></script>
<script src="{{asset('resources/assets/front/js/jquery.easing.1.3.min.js')}}"></script>
<script src="{{asset('resources/assets/front/js/jquery.animate-enhanced.min.js')}}"></script>
<script src="{{asset('resources/assets/front/js/jquery.superslides.min.js')}}"></script>
<script  type="text/javascript">
    $('#slides').superslides({
        play: 6000,
        pagination:false,
        animation_speed: 800,
        animation: 'fade'
    });
</script>
<!-- OTHER JS -->
<script src="{{asset('resources/assets/front/js/retina.min.js')}}"></script>
<script  src="{{asset('resources/assets/front/js/functions.js')}}"></script>
<script src="{{asset('resources/assets/front/assets/validate.js')}}"></script>
</body>
</html>