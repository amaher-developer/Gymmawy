
@extends('generic::Front.layouts.construction_master')
@section('style')

@endsection
@section('content')
    <div id="wrapper">
        <div id="main">
            <div class="container">

                <div class="row countdown">
                    <div class="col-md-12">
                        <div id="logo"><img src="img/logo.png" width="150" height="36" alt="" data-retina="true"></div>
                        <h1>We will be Back soon!</h1>
                        <h2>Meanwhile, you can make leave your email. We will advice when we will be online!</h2>
                    </div>
                    <div id="countdown_wp">
                        <div class="container_count"><div id="days">00</div>days</div>
                        <div class="container_count"><div id="hours">00</div>hours</div>
                        <div class="container_count"><div id="minutes">00</div>minutes</div>
                        <div class="container_count last"><div id="seconds">00</div>seconds</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div id="newsletter_wp" >
                            <form method="post" action="{{asset('resources/assets/front/assets/newsletter.php')}}" id="newsletter" name="newsletter"  autocomplete="off">
                                <div class="row">
                                    <div class="col-md-9 first-nogutter">
                                        <input name="email_newsletter" id="email_newsletter" type="email" placeholder="Your Email" class="form-control">
                                    </div>
                                    <div class="col-md-3 nogutter">
                                        <button type="submit" class="btn-check" id="submit-newsletter">Subscribe</button>
                                    </div>
                                </div>
                                <div id="message-newsletter"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="social_footer">
                    <ul>
                        <li><a href="#"><i class="icon-facebook"></i></a></li>
                        <li><a href="#"><i class="icon-twitter"></i></a></li>
                        <li><a href="#"><i class="icon-google"></i></a></li>
                        <li><a href="#"><i class="icon-vimeo"></i></a></li>
                        <li><a href="#"><i class="icon-youtube-play"></i></a></li>
                    </ul>
                    <p>© Gymmawy {{date('Y')}}</p>
                </div>
            </div><!-- End container -->
        </div><!-- End main -->
    </div>
@endsection
