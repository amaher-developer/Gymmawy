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
        .imgg {
            height: 95px;
            width: 95px;
            padding: 2px 0;
            object-fit: cover;
        }

        section#description {
            border-bottom: none;
        }

        .box_detail {
            padding: 25px;
        }

    </style>
    <style>
        .hero_in.hotels_detail:before {
            background: url({{@$gym->cover_image ?? asset('resources/assets/front/img/bg/gyms.jpg')}}) center center no-repeat;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
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

        .modal-window > div {
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

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }

        ul.hotel_facilities {
            column-count: 3 !important;
        }
    </style>
@endsection
@section('content')

    <main>
        <section class="hero_in hotels_detail">
            <div class="wrapper">
                <div class="container">
                    <h1 class="fadeInUp"><span></span>{{@$gym->name}}</h1>
                </div>
                {{--                <span class="magnific-gallery">--}}
                {{--					<a href="{{asset('resources/assets/front/img/gallery/hotel_list_1.jpg')}}" class="btn_photos"--}}
                {{--                       title="Photo title" data-effect="mfp-zoom-in">View photos</a>--}}
                {{--					<a href="{{asset('resources/assets/front/img/gallery/hotel_list_2.jpg')}}" title="Photo title"--}}
                {{--                       data-effect="mfp-zoom-in"></a>--}}
                {{--					<a href="{{asset('resources/assets/front/img/gallery/hotel_list_3.jpg')}}" title="Photo title"--}}
                {{--                       data-effect="mfp-zoom-in"></a>--}}
                {{--				</span>--}}
            </div>
        </section>
        <!--/hero_in-->

        <div class="bg_color_1">
            <nav class="secondary_nav sticky_horizontal">
                <div class="container">
                    <ul class="clearfix">
                        {{--                        <li><a href="#images" class="active">{{trans('global.images')}}</a></li>--}}
                        <li><a href="#description" class="active">{{trans('global.details')}}</a></li>
                        @if(@count($gym->services) > 0)
                            <li><a href="#services">{{trans('global.services')}}</a></li>@endif
                        @if(@count($gym->categories) > 0)
                            <li><a href="#categories">{{trans('global.categories')}}</a></li>@endif
                        @if(@$gym->gym_brand->socials['instagram'])
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
                        <section>
{{--<div style="direction: rtl;">--}}
{{--                            <div class="col-lg-12 sharethis-inline-share-buttons text-center "  ></div>--}}
{{--                                                        <hr/>--}}

{{--</div>                                       <div style="clear: both;"></div>--}}
                        </section>
                        {{--                        <section id="images">--}}
                        {{--                            <h3>{{trans('global.images')}}</h3>--}}

                        {{--                            @if($gym->images)--}}
                        {{--                                <span class="magnific-gallery">--}}
                        {{--                                                                @foreach($gym->images as $image)--}}
                        {{--                                        <a href="{{$image->image}}"--}}
                        {{--                                           class="btn_photos"--}}
                        {{--                                           title="{{$gym->name}}" data-effect="mfp-zoom-in">--}}
                        {{--                                                                        <img class="imgg" src="{{$image->image}}"--}}
                        {{--                                                                             alt="{{$gym->name}}">--}}
                        {{--                                                                    </a>--}}
                        {{--                                    @endforeach--}}
                        {{--                                                            </span>--}}
                        {{--                            @endif--}}
                        {{--                            <hr/>--}}
                        {{--                            <div style="clear: both;"></div>--}}
                        {{--                        </section>--}}
                        <section id="description">
                            @if($gym->description)
                            <h2>{{trans('global.details')}}</h2>
                            <p>{{$gym->description}}</p>
                            <div class="clearfix"></div>
                            <!-- /row -->
                            <hr>
                            @endif


{{--                                <div class="text-center">--}}
{{--                            <!-- banner 728*90 -->--}}
{{--                            <ins class="adsbygoogle"--}}
{{--                                 style="display:inline-block;width:728px;height:90px"--}}
{{--                                 data-ad-client="ca-pub-8099437480361514"--}}
{{--                                 data-ad-slot="2499442542"></ins>--}}
{{--                            <script>--}}
{{--                                (adsbygoogle = window.adsbygoogle || []).push({});--}}
{{--                            </script>--}}
{{--                                </div>--}}

                            <h3>{{trans('admin.contact_info')}}</h3>
                            <div style="padding-top: 10px">
                                <ul style=" @if($lang == 'ar') text-align: right !important; @else  text-align: left !important; @endif margin-bottom: 10px;padding-top: 0px;">
                                    <li><span aria-hidden="true"
                                              class="icon-location-1"></span> {{@$gym->address}}</li>
                                    <li>
                                        <span class="icon-location-2"></span> {{@$gym->district->name}}
                                        ,{{@$gym->district->city->name}}
                                    </li>
                                </ul>
                                <ul style=" @if($lang == 'ar') text-align: right !important; @else  text-align: left !important; @endif margin-bottom: 0px;padding-bottom: 10px;"
                                    class="share-buttons">
                                    @if(isset($gym->phones))
                                        @foreach($gym->phones as $key => $phone)
                                            <li style="padding: 6px;">
                                                <a class="phone-share" href="#open-modal"
                                                   onclick="getLocationPhone('{{$key}}', '{{$phone}}');"
                                                   style="padding: 7px 60px; @if($lang == 'en') direction: rtl; @endif"
                                                   title=""> {{$key+1}} <i class="icon_phone"
                                                                           aria-hidden="true"></i></a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                                @if($gym->lat && $gym->lng)
                                    <iframe style="height: 400px" width="100%" height="500"
                                            id="gmap_canvas"
                                            src="https://maps.google.com/maps?q={{$gym->lat}},{{$gym->lng}}&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                            frameborder="0" scrolling="no" marginheight="0"
                                            marginwidth="0"></iframe>
                                @else
                                    <iframe width="100%" height="500" style="height: 400px"  frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{urlencode(@$gym->district->name_en)}}, {{urlencode(@$gym->district->city->name_en)}}+({{urlencode(@$gym->gym_brand->name_en)}})&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                                @endif
                            </div>
                            <!-- End Map -->
                        </section>
                        <!-- /section -->

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

{{--                        <div class="text-center">--}}
{{--                        <!-- banner 728*90 -->--}}
{{--                        <ins class="adsbygoogle"--}}
{{--                             style="display:inline-block;width:728px;height:90px"--}}
{{--                             data-ad-client="ca-pub-8099437480361514"--}}
{{--                             data-ad-slot="2499442542"></ins>--}}
{{--                        <script>--}}
{{--                            (adsbygoogle = window.adsbygoogle || []).push({});--}}
{{--                        </script>--}}
{{--                        </div>--}}
                        @if(@count($gym->services) > 0)
                            <section id="services">
                                <hr>
                                <h3 style="padding-bottom: 10px;">{{trans('global.services')}}</h3>
                                <div class="row">

                                    <ul class="hotel_facilities">
                                        @foreach($gym->services as $service)
                                            <li>
                                                                                        <span><img src="{{$service->logo}}"
                                                                                                   alt="{{@$gym->name}} | {{@$service->name}}"
                                                                                                   title="{{@$gym->name}} | {{@$service->name}}"></span> {{@$service->name}}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                            </section>
                            <!-- /section -->
                        @endif
                        @if(@count($gym->categories) > 0)
                            <section id="categories">
                                <hr>
                                <h3 style="padding-bottom: 10px;">{{trans('global.categories')}}</h3>
                                <div class="row">
                                    <ul class="hotel_facilities">
                                        @foreach($gym->categories as $category)
                                            <li>
                                                                                        <span><img src="{{$category->logo}}"
                                                                                                   alt="{{@$gym->name}} | {{@$category->name}}"
                                                                                                   title="{{@$gym->name}} | {{@$category->name}}"></span> {{$category->name}}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                            </section>
                            <!-- /section -->
                        @endif

                        @if(@$gym->gym_brand->socials['instagram'])
                            <div class="clearfix">
                                <hr/>
                            </div>
                            <section id="instagram">
                                <h3>{{trans('global.instagram_photos')}}</h3>
                                <div id="instagram-feed-hotel" class="clearfix"></div>
                            </section>
                        @endif

                        @if(count($related_gyms) > 0)
                            <div class="clearfix">
                                <hr/><br/>
                            </div>
                            <section id="related_gyms">
                                <h2>{{trans('global.related_gyms')}}</h2>
                                <br/>
                                <div class="row">
                                @foreach($related_gyms as $key => $record)
                                    <div class="col-md-4 isotope-item @if(($key+1) % 2 == 0) latest @else popular @endif">
                                        <div class="box_grid">
                                            <figure>
                                                <a href="#sign-in-dialog"
                                                   id="favorite_1_{{$record->id}}" onclick="
                                                @if(@$currentUser && $currentUser->gym_favorites &&  @in_array($record->id, $currentUser->gym_favorites->pluck('gym_id')->toArray())) removeFavorite('{{$record->id}}', 1); return false;
                                                @else addFavorite('{{$record->id}}', 1); return false;  @endif
                                                        "
                                                   class="
                                                    @if(!@$currentUser) login sign-in-form @endif wish_bt
                                                    @if( @$currentUser->gym_favorites && @in_array($record->id, $currentUser->gym_favorites->pluck('gym_id')->toArray())) liked @endif
                                                           "
                                                ></a>
                                                <a href="{{route('gym', [$record->id, $record->slug])}}">
                                                    <img src="{{$record->image_thumbnail}}" class="img-fluid" alt="{{$record->name}}"
                                                         width="800" height="533">
                                                    <div class="read_more"><span>{{trans('global.details')}}</span></div>
                                                </a>
                                                <small>{{@$record->categories[0]->name}}</small>
                                            </figure>
                                            <div class="wrapper">
                                                <h3>
                                                    <a href="{{route('gym', [$record->id, $record->slug])}}">{{$record->name}}</a>
                                                </h3>
                                                <span class="price">{{@$record->district->name}}, {{@$record->district->city->name}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /box_grid -->


                                @endforeach
                                </div>
                            </section>
                            <!-- /section -->
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
                                                        <img src="{{$article->image}}" alt="{{$article->title}}">
                                                        <div class="preview"><span>{{trans('global.details')}}</span>
                                                        </div>
                                                    </a>
                                                </figure>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="post_info">
                                                    <small>{{$article->created_at}}</small>
                                                    <h3>
                                                        <a href="{{route('article',[$article->id, $article->slug])}}">{{$article->title}}</a>
                                                    </h3>
                                                    <p>
                                                        {{\Illuminate\Support\Str::limit(strip_tags($article->description), 300, '...')}}
                                                    </p>
                                                    <ul>
                                                        <li>
                                                            <div class="thumb"><img
                                                                        src="{{$gym->gym_brand->user->image ??  asset('resources/assets/front/img/avatar_placeholder.png')}}"
                                                                        alt="{{@$gym->brand_gym->user->name}}">
                                                            </div> {{@$gym->gym_brand->user->name}}
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
                            {{--                            <div class="price">--}}
                            {{--                                <span>45$ <small>{{trans('global.per_person')}}</small></span>--}}
                            {{--                                --}}{{--                                <div class="score"><span>Good<em>350 Reviews</em></span><strong>7.0</strong></div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="panel-dropdown">--}}
                            {{--                                <a href="#">{{trans('global.person')}} <span class="qtyTotal">1</span></a>--}}
                            {{--                                <div class="panel-dropdown-content right">--}}
                            {{--                                    <div class="qtyButtons">--}}
                            {{--                                        <label>{{trans('global.number_of_persons')}}</label>--}}
                            {{--                                        <input type="text" name="qtyInput" value="1">--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}


                            {{--                            <div class="form-group clearfix">--}}
                            {{--                                <div class="custom-select-form">--}}
                            {{--                                    <select class="wide">--}}
                            {{--                                        <option>Room Type</option>--}}
                            {{--                                        <option>Single Room</option>--}}
                            {{--                                        <option>Double Room</option>--}}
                            {{--                                        <option>Suite Room</option>--}}
                            {{--                                    </select>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <a href="" class=" add_top_30 btn_1 full-width purchase">{{trans('global.buy')}}</a>--}}


                            {{--                            <a href="#sign-in-dialog"--}}
                            {{--                                    id="favorite_1_{{$gym->id}}" onclick="--}}
                            {{--                            @if(@$currentUser && $currentUser->gym_favorites &&  @in_array($gym->id, $currentUser->gym_favorites->pluck('gym_id')->toArray())) removeFavorite('{{$gym->id}}', 1); removeFavoriteText('{{$gym->id}}', 1); return false;--}}
                            {{--                            @else addFavorite('{{$gym->id}}', 1); addFavoriteText('{{$gym->id}}', 1); return false;  @endif--}}
                            {{--                                    "--}}
                            {{--                                    class=" btn_1 full-width outline wishlist--}}
                            {{--                                                    @if(!@$currentUser) login sign-in-form @endif wish_bt--}}
                            {{--                                                    @if( @$currentUser->gym_favorites && @in_array($gym->id, $currentUser->gym_favorites->pluck('gym_id')->toArray())) liked @endif--}}
                            {{--                                            "--}}
                            {{--                                    >@if( @$currentUser->gym_favorites && @in_array($gym->id, $currentUser->gym_favorites->pluck('gym_id')->toArray()))  <i class="icon_heart"></i> {{trans('global.remove_from_favourites')}}  @else <i class="icon-heart-empty"></i> {{trans('global.add_to_favourites')}} @endif</a>--}}


                            <span class="magnific-gallery">
                                    <a href="{{$gym->image}}"
                                       class="btn_photos"
                                       title="{{@$gym->name}}" data-effect="mfp-zoom-in">
                                            <img class="imgg" src="{{$gym->image}}" alt="{{@$gym->name}}">
                                        </a>

                                    @if($gym->images)
                                    @foreach($gym->images as $image)
                                        <a href="{{$image->image}}"
                                           class="btn_photos"
                                           title="{{@$gym->name}}" data-effect="mfp-zoom-in">
                                            <img class="imgg" src="{{$image->image}}" alt="{{@$gym->name}}">
                                        </a>
                                    @endforeach
                                @endif
                                </span>


                        </div>


{{--                        <div class="text-center">--}}
{{--                        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8099437480361514"--}}
{{--                                crossorigin="anonymous"></script>--}}
{{--                        <!-- side-menu 250*250 -->--}}
{{--                        <ins class="adsbygoogle"--}}
{{--                             style="display:inline-block;width:250px;height:250px"--}}
{{--                             data-ad-client="ca-pub-8099437480361514"--}}
{{--                             data-ad-slot="5794839666"></ins>--}}
{{--                        <script>--}}
{{--                            (adsbygoogle = window.adsbygoogle || []).push({});--}}
{{--                        </script>--}}
{{--                        </div>--}}

                        <ul class="share-buttons" style="margin-bottom: 0px;padding-bottom: 10px;">
                            @if($gym->gym_brand->main_phone)
                                <li><a class="phone-share" href="#open-modal"
                                       onclick="getPhone();" style="padding: 7px 60px;"
                                       title=""><i class="icon_phone" aria-hidden="true"></i></a></li>
                            @endif
                        </ul>

                        <ul class="share-buttons">
                            <ul class="share-buttons">
                                @if(isset($gym->gym_brand->socials['website']))
                                    <li><a class="website-share" href="{{$gym->gym_brand->socials['website']}}"
                                           target="_blank"
                                           title=""><i class="icon_globe-2"
                                                       aria-hidden="true"></i></a></li>
                                @endif
                                @if(isset($gym->gym_brand->socials['facebook']))
                                    <li><a class="fb-share" href="{{$gym->gym_brand->socials['facebook']}}"
                                           target="_blank"
                                           title="facebook"><i class="social_facebook"
                                                               aria-hidden="true"></i></a></li>
                                @endif
                                @if(isset($gym->gym_brand->socials['twitter']))
                                    <li><a class="twitter-share" href="{{$gym->gym_brand->socials['twitter']}}"
                                           target="_blank"
                                           title="twitter"><i class="social_twitter" aria-hidden="true"></i></a>
                                    </li>
                                @endif
                                @if(isset($gym->gym_brand->socials['instagram']))
                                    <li><a class="instagram-share" href="{{@$gym->gym_brand->socials['instagram']}}"
                                           target="_blank"
                                           title="instagram"><i class="social_instagram"
                                                                aria-hidden="true"></i></a></li>
                                @endif
                                @if(isset($gym->gym_brand->socials['linkedin']))
                                    <li><a class="linkedin-share" href="{{@$gym->gym_brand->socials['linkedin']}}"
                                           target="_blank"
                                           title="linkedin"><i class="social_linkedin"
                                                               aria-hidden="true"></i></a></li>
                                @endif
                            </ul>
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
            <a href="#modal-close" title="{{trans('global.close')}}"
               class="modal-close">{{trans('global.close')}} &times;</a>
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
                url: '{{route('getGymPhoneByAjax')}}',
                method: "POST",
                data: {gym_id: "{{$gym->gym_brand->id}}", _token: "{{csrf_token()}}"},
                dataType: "text",
                success: function (data) {
                    if (data != '') {
                        document.getElementById("phone_result").innerHTML = '<a href="tel:' + data + '"><i class="icon_phone" aria-hidden="true"></i>  ' + data + '</a>';
                    } else {
                    }
                }
            });
        }

        function getLocationPhone(id, phone) {
            document.getElementById("phone_result").innerHTML = '<a href="tel:' + phone + '"><i class="icon_phone" aria-hidden="true"></i>  ' + phone + '</a>';
        }

        // $(window).on('load', function () {
        //     "use strict";
        //     $.instagramFeed({
        //         'username': 'amaher.developer',
        //         'container': "#instagram-feed-hotel",
        //         'display_profile': false,
        //         'display_biography': false,
        //         'display_gallery': true,
        //         'get_raw_json': false,
        //         'callback': null,
        //         'styling': true,
        //         'items': 12,
        //         'items_per_row': 6,
        //         'margin': 1
        //     });
        // });
    </script>
@endsection
