@extends('generic::Front.layouts.master')
@section('title'){{ $title }} | @endsection
@section('style')
    <!-- SPECIFIC CSS -->
    <link href="{{asset('resources/assets/front/css/blog.css')}}" rel="stylesheet">

    @if($lang == 'ar')
        <link href="{{asset('resources/assets/front/css/blog-rtl.css')}}" rel="stylesheet">
    @endif
    <!-- YOUR CUSTOM CSS -->
    <style>
        .hero_in.hotels_detail:before {
            background: url({{asset('resources/assets/front/img/bg/trainerb.jpg')}}) center center no-repeat;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        ul.bullets2 li:before {
            display: none;
        }
    </style>
    <style>
        .modal-window {
            position: fixed;
            background-color: rgba(200, 200, 200, 0.75);
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 999;
            opacity: 0;
            pointer-events: none;
            -webkit-transition: all 0.3s;
            -moz-transition: all 0.3s;
            transition: all 0.3s;
        }

        .modal-window:target {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-window>div {
            width: 400px;
            position: relative;
            margin: 10% auto;
            padding: 2rem;
            background: #fff;
            color: #444;
        }

        .modal-window header {
            font-weight: bold;
        }

        .modal-close {
            color: #aaa;
            line-height: 50px;
            font-size: 80%;
            position: absolute;
            right: 0;
            text-align: center;
            top: 0;
            width: 70px;
            text-decoration: none;
        }

        .modal-close:hover {
            color: #000;
        }

        .modal-window h2 {
            font-size: 150%;
            margin: 15px 0 0 15px;
            text-align: center;
            direction: ltr;
            border: 1px solid #fc5b62;
            padding: 20px;
        }
    </style>
@endsection
@section('content')
    <main>
        <section class="hero_in hotels_detail">
            <div class="wrapper">
                <div class="container">
                    <h1 class="fadeInUp"><span></span>{{$trainer->name}}</h1>
                </div>
            </div>
        </section>
        <!--/hero_in-->

        <div class="bg_color_1">
            <nav class="secondary_nav sticky_horizontal">
                <div class="container">
                    <ul class="clearfix">
                        <li><a href="#details" class="active">{{trans('global.details')}}</a></li>
{{--                        @if(@count($trainer->districts) > 0)--}}
{{--                            <li><a href="#districts">{{trans('global.districts')}}</a></li>@endif--}}
                        @if(@count($trainer->categories) > 0)
                            <li><a href="#categories">{{trans('global.categories')}}</a></li>@endif
                        @if($trainer->instagram)
                            <li><a href="#instagram">{{trans('global.instagram')}}</a></li>
                        @endif
                        @if(count($articles) > 0)
                            <li><a href="#articles">{{trans('global.articles')}}</a></li>
                        @endif
                        <li><a href="#sidebar"> </a></li>
                    </ul>
                </div>
            </nav>
            <div class="container margin_60_35">
                <div class="row">
                    <div class="col-lg-8">

{{--                        <section>--}}
{{--                            <div class="sharethis-inline-share-buttons text-center" ></div>--}}
{{--                            <hr/>--}}
{{--                            <div style="clear: both;"></div>--}}
{{--                        </section>--}}

                        <section id="details">
                            <h2>{{trans('global.details')}}</h2>
                            <p>{!! $trainer->about !!}</p>
                            <div class="row">
                                <div class="col-lg-6">
                                    <ul class="bullets">
                                        @if($trainer->gym_name)
                                            <li><strong>{{trans('global.gym')}}:</strong> {{$trainer->gym_name}}
                                            </li>@endif
                                        @if($trainer->age)
                                            <li><strong>{{trans('global.age')}}:</strong> {{$trainer->age}}</li>@endif
                                    </ul>
                                </div>
                                <div class="col-lg-6">
                                    <ul class="bullets">
                                        @if($trainer->gender)
                                            <li><strong>{{trans('global.gender')}}:</strong> {{$trainer->gender_name}}</li>
                                        @endif
                                        @if($trainer->experience)
                                            <li><strong>{{trans('global.experience')}}
                                                    :</strong> {{$trainer->experience}}</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </section>

                        <!-- Banner Section -->
                        @if(isset($banner))
                        <section id="banner">
                            <div class="text-center" style="margin: 20px 0;">
                                @if($banner->url)
                                    <a href="{{ $banner->url }}" target="_blank" rel="noopener">
                                        <img src="{{ $banner->image }}" alt="{{ $banner->title ?? trans('global.advertisement') }}"
                                             style="max-width: 100%; height: auto; border-radius: 5px;">
                                    </a>
                                @else
                                    <img src="{{ $banner->image }}" alt="{{ $banner->title ?? trans('global.advertisement') }}"
                                         style="max-width: 100%; height: auto; border-radius: 5px;">
                                @endif
                                @if($banner->title)
                                    <p style="margin-top: 10px; font-size: 14px; color: #666;">{{ $banner->title }}</p>
                                @endif
                            </div>
                        </section>
                        @endif
                        <!-- /Banner Section -->

{{--                        @if(@count($trainer->districts) > 0)--}}
{{--                            <div class="clearfix">--}}
{{--                                <hr/>--}}
{{--                            </div>--}}
{{--                            <section id="districts">--}}
{{--                                <h2>{{trans('global.districts')}}</h2>--}}
{{--                                <div class="row">--}}
{{--                                    @foreach($trainer->districts->chunk(2) as $districts)--}}
{{--                                        @foreach($districts as $district)--}}
{{--                                            <div class="col-lg-6">--}}
{{--                                                <ul class="bullets">--}}
{{--                                                    <li>{{$district->name}}</li>--}}
{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                        @endforeach--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                                <!-- /row -->--}}
{{--                                <!-- End Map -->--}}
{{--                            </section>--}}
{{--                            <!-- /section -->--}}
{{--                        @endif--}}
                        @if(@count($trainer->categories) > 0)
                            <section id="categories">
                                <hr>
                                <h3 style="padding-bottom: 10px;">{{trans('global.categories')}}</h3>
                                <div class="row">
                                    @foreach($trainer->categories->chunk(2) as $categories)
                                        @foreach($categories as $category)
                                            <div class="col-lg-6">
                                                <ul class="bullets2">
                                                    <li><span><img src="{{$category->logo}}"></span> {{$category->name}}
                                                    </li>
                                                </ul>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>

                            </section>
                            <!-- /section -->
                        @endif
                        @if($trainer->instagram)
                            <div class="clearfix">
                                <hr/>
                            </div>
                            <section id="instagram">
                                <h3>{{trans('global.instagram_photos')}}</h3>
                                <div id="instagram-feed-hotel" class="clearfix"></div>
                            </section>
                        @endif

                        @if(count($articles) > 0)
                            <div class="clearfix">
                                <hr/>
                            </div>
                            <section id="articles">
                                <h2>{{trans('global.articles')}}</h2>
                                @foreach($articles as $article)
                                    <article class="blog wow fadeIn">
                                        <div class="row no-gutters">
                                            <div class="col-lg-7">
                                                <figure>
                                                    <a href="{{route('article',[$article->id, $article->slug])}}">
                                                        <img src="{{@$article->image}}" alt="{{$article->title}}">
                                                        <div class="preview"><span>{{trans('global.details')}}</span>
                                                        </div>
                                                    </a>
                                                </figure>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="post_info">
                                                    <small>{{$article->update_at}}</small>
                                                    <h3>
                                                        <a href="{{route('article',[$article->id, $article->slug])}}">{{$article->title}}</a>
                                                    </h3>
                                                    <p>
                                                        {{\Illuminate\Support\Str::limit(strip_tags($article->description), 300, '...')}}
                                                    </p>
                                                    <ul>
                                                        <li>
                                                            <div class="thumb"><img src="{{@$trainer->user->image ? @$trainer->user->image :  asset('resources/assets/front/img/avatar_placeholder.png')}}"
                                                                                    alt="">
                                                            </div> {{@$trainer->user->name}}
                                                        </li>
                                                        <li><i class="icon_comment_alt"></i></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                    <!-- /article -->
                                @endforeach

                            </section>
                            <!-- /section -->
                        @endif
                    </div>
                    <!-- /col -->

                    <aside class="col-lg-4" id="sidebar">
                        <div class="box_detail booking">
                            <span class="magnific-gallery">
                                <a href="{{@$trainer->image}}" class="btn_photos" title="{{@$trainer->name}}"
                                   data-effect="mfp-zoom-in">
                                    <img src="{{@$trainer->image}}" class="img-responsive" alt="{{@$trainer->name}}"
                                         style="height: 225px;object-fit: contain;width: 300px;"/>
                                </a>
                            </span>

                            {{--                            <a href="cart-1.html"--}}
                            {{--                               class=" add_top_30 btn_1 full-width purchase">{{trans('global.phone')}}</a>--}}

                            {{--                            <a href="" class="btn_1 full-width outline wishlist"><i class="icon_heart"></i>--}}
                            {{--                                {{trans('global.add_to_favourites')}}</a>--}}
                        </div>

                        @if($trainer->phone)
                            <ul class="share-buttons" style="margin-bottom: 0px;padding-bottom: 10px;">
                                <li><a class="phone-share"  href="#open-modal"
                                       onclick="getPhone();" style="padding: 7px 60px;"
                                       title=""><i class="icon_phone" aria-hidden="true"></i></a></li>
                            </ul>
                        @endif
                        <ul class="share-buttons">
                            @if($trainer->website)
                                <li><a class="website-share" href="{{$trainer->facebook}}" target="_blank"
                                       title=""><i class="icon_globe-2"
                                                   aria-hidden="true"></i></a></li>
                            @endif
                            @if($trainer->facebook)
                                <li><a class="fb-share" href="{{$trainer->facebook}}" target="_blank"
                                       title="facebook"><i class="social_facebook"
                                                           aria-hidden="true"></i></a></li>
                            @endif
                            @if($trainer->twitter)
                                <li><a class="twitter-share" href="{{$trainer->twitter}}" target="_blank"
                                       title="twitter"><i class="social_twitter" aria-hidden="true"></i></a>
                                </li>
                            @endif
                            @if($trainer->instagram)
                                <li><a class="instagram-share" href="{{$trainer->instagram}}" target="_blank"
                                       title="instagram"><i class="social_instagram"
                                                            aria-hidden="true"></i></a></li>
                            @endif
                            @if($trainer->linkedin)
                                <li><a class="linkedin-share" href="{{$trainer->linkedin}}" target="_blank"
                                       title="linkedin"><i class="social_linkedin"
                                                           aria-hidden="true"></i></a></li>
                            @endif
                        </ul>
                    </aside>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /bg_color_1 -->
    </main>
    <!--/main-->
    <div id="open-modal" class="modal-window">
        <div>
            <a href="#modal-close" title="{{trans('global.close')}}" class="modal-close">{{trans('global.close')}} &times;</a>
            <h2 id="phone_result"></h2>
            {{--                <div>The quick brown fox jumped over the lazy dog.</div>--}}
        </div>
    </div>
@endsection
@section('script')
{{--    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5f84c30dfdcf740012376d7f&product=inline-share-buttons" async="async"></script>--}}

    <!-- INSTAGRAM FEED  -->
    <script>

        function getPhone() {
            $.ajax({
                url: '{{route('getTrainerPhoneByAjax')}}',
                method: "POST",
                data: {trainer_id: "{{$trainer->id}}",_token: "{{csrf_token()}}"},
                dataType: "text",
                success: function (data) {
                    if (data != '') {
                        document.getElementById("phone_result").innerHTML = '<a href="tel:'+data+'"><i class="icon_phone" aria-hidden="true"></i>  '+data+'</a>';
                    } else {
                    }
                }
            });
        }
        @if($trainer->instagram)
        $(window).on('load', function () {
            "use strict";
            $.instagramFeed({
                'username': '{{$trainer->instagram}}',
                'container': "#instagram-feed-hotel",
                'display_profile': false,
                'display_biography': false,
                'display_gallery': true,
                'get_raw_json': false,
                'callback': null,
                'styling': true,
                'items': 12,
                'items_per_row': 6,
                'margin': 1
            });
        });
        @endif
    </script>
@endsection
