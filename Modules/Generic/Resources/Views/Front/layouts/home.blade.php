@extends('generic::Front.layouts.master')
@section('style')
<style>
    .hero_single.version_3:before {
        background: url({{asset('resources/assets/front/img/bg/home_9.jpg')}}) center center no-repeat;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
    .main_title_2 h1 {
        font-size: 2rem;
        margin: 25px 0 0 0;
    }
    @if($banner)
    .banner {
        background: url("{{$banner->image}}") center center no-repeat;
        -webkit-background-size: contain;
        -moz-background-size: contain;
        -o-background-size: contain;
        background-size: contain;
        width: 100%;
        height: 420px;
        margin-bottom: 60px;
        position: relative;
    }
    @endif
</style>

@endsection
@section('content')

    <main>
        <section class="hero_single version_3">
            <div class="wrapper">
                <div class="container">
                    <div class="main_search">
                        <h3>{{trans('global.search_title')}}</h3>
                        <p style="font-size: 13px;">{{trans('global.search_msg')}}</p>
                        <form action="{{route('searchRedirect')}}" method="get">
                            {{csrf_field()}}
                            <div >
                                <div class="form-group">
                                    <select class="form-control" style="width: 100%;height: 45px;" name="city" id="city" >
                                        <option value="">{{trans('global.all_city')}}</option>
                                        @foreach($cities as $city)
                                            <option value="{{$city['id']}}"
                                                    @if($city['id'] == @request('city')) selected="" @endif
                                            >{{$city['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group"  style="clear: both;">
                                    <select class="form-control" style="width: 100%;height: 45px;" name="district" id="district_id" >
                                        <option value="">{{trans('global.all_area')}}</option>
                                        @foreach($districts as $district)
                                            <option value="{{$district['id']}}"
                                                    class="districts_of_city_{{$district['city_id']}}"
                                                    @if($district['id'] == @request('district')) selected=""@endif
                                            >{{$district['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" style="width: 100%;height: 45px;" name="type" id="type" >
                                        <option value="1">{{trans('global.gyms')}}</option>
                                        <option value="2">{{trans('global.trainers')}}</option>
                                    </select>
                                </div>

                                <div class="custom-search-input-2 form-group text-right row col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-6 col-md-8 col-sm-6 col-xs-6" style="margin: 20px 0 0 0;text-align:@if($lang == 'ar') right @else left @endif;">
                                        <a href="{{$mainSettings->android_app}}"  target="_blank" >
                                            <img src="{{asset('resources/assets/front/img/play_store_logo.png')}}" style="width: 40px;height: 40px;object-fit: cover;">
                                        </a>
                                        <a href="{{$mainSettings->ios_app}}"  target="_blank" >
                                            <img src="{{asset('resources/assets/front/img/apple_store_logo.png')}}" style="width: 40px;height: 40px;object-fit: cover;">
                                        </a>
                                    </div>
                                    <div class="col-6 col-md-4 col-sm-6 col-xs-6">
                                    <input type="submit" class="btn_search" value="{{trans('global.search')}}">
                                    </div>
                                </div>
                            </div>
                            <!-- /row -->
                        </form>
                    </div>
                    <!-- /main_search -->
                </div>
            </div>
        </section>
        <!-- /hero_single -->

        <div class="text-center">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8099437480361514"
                crossorigin="anonymous"></script>
        <!-- daleelaqarat horizontal ad -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-8099437480361514"
             data-ad-slot="1105918368"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        </div>
        @if(count(@$features_gyms)>0)
        <div class="container container-custom margin_80_0">
            <div class="main_title_2">
                <span><em></em></span>
                <h1>{{trans('global.best_gyms')}}</h1>
                <p style="font-size: 20px;">{{trans('global.home_best_gyms_msg')}}</p>
            </div>
            <div id="reccomended" class="owl-carousel owl-theme">
                @foreach($features_gyms as $features_gym)
                <div class="item">
                    <div class="box_grid">
                        <figure>
                            <a href="#sign-in-dialog" id="favorite_1_{{$features_gym->id}}" title="{{@$features_gym->name}}" onclick="
                            @if(@$currentUser && $features_gym->favorites && @in_array($currentUser->id, $features_gym->favorites->pluck('user_id')->toArray())) removeFavorite('{{$features_gym->id}}', 1); return false;
                            @else addFavorite('{{$features_gym->id}}', 1); return false;  @endif
                               "
                               class="
                                @if(!@$currentUser) login sign-in-form @endif wish_bt
                                @if($features_gym->favorites && @in_array($currentUser->id, $features_gym->favorites->pluck('user_id')->toArray())) liked @endif
                                    "></a>
                            <a href="{{route('gym', [$features_gym->id, $features_gym->slug])}}">
                                <img src="{{$features_gym->image_thumbnail}}" class="img-fluid" alt="{{@$features_gym->name}}" width="800" height="533"><div class="read_more"><span>{{trans('global.details')}}</span></div></a>
                            <small>{{@$features_gym->categories[0]->name}}</small>
                        </figure>
                        <div class="wrapper">
                            <h3><a href="{{route('gym', [$features_gym->id, $features_gym->slug])}}">{{@$features_gym->name}}</a></h3>
{{--                            <p>{{$features_gym->gym_address}}</p>--}}
                            <span class="price">{{@$features_gym->district->name}}, {{@$features_gym->district->city->name}}</span>
                        </div>
                        <ul>
                            <li><i class="icon_clock_alt"></i> {{$features_gym->views}} {{trans('global.views')}}</li>
                            <li><div class="score"><span>{{trans('global.articles')}}</span><strong>{{(int)$features_gym->articles}}</strong></div></li>
{{--                            <li><div class="score"><span>Superb<em>350 Reviews</em></span><strong>8.9</strong></div></li>--}}
                        </ul>
                    </div>
                </div>
                <!-- /item -->
                @endforeach
            </div>
            <!-- /carousel -->
            <p class="btn_home_align text-center" ><a href="{{route('gyms')}}" class="btn_1 rounded">{{trans('global.view_all')}} </a></p>
            <hr class="large">
        </div>
        <!-- /container -->
        @endif

        <div class="text-center">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8099437480361514"
                crossorigin="anonymous"></script>
        <!-- daleelaqarat horizontal ad -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-8099437480361514"
             data-ad-slot="1105918368"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        </div>
        @if(count(@$latest_trainers)>0)
        <div class="container container-custom margin_30_95">
{{--            @if($banner)--}}
{{--                <div class="banner mb-0">--}}
{{--                    <div class="wrapper d-flex align-items-center opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.3)">--}}
{{--                        <div>--}}
{{--                            --}}{{--                    <small>Adventure</small>--}}
{{--                            --}}{{--                    <h3>{{$banner->title}}</h3>--}}
{{--                                                <p>{{$banner->title}}</p>--}}
{{--                            <a href="{{$banner->url}}" class="btn_1">{{trans('global.details')}}</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- /wrapper -->--}}
{{--                </div><br/>--}}
{{--                <!-- /banner -->--}}
{{--            @endif--}}

            <section class="add_bottom_45">
                <div class="main_title_3">
                    <span><em></em></span>
                    <h1>{{trans('global.best_trainers')}}</h1>
                    <p style="font-size: 17px;">{{trans('global.home_best_trainers_msg')}}</p>
                </div>
                <div class="row">
                    @foreach($latest_trainers as $latest_trainer)
                    <div class="col-xl-2 col-lg-6 col-md-6">
                        <a href="{{route('trainer', [$latest_trainer->id, $latest_trainer->slug])}}" class="grid_item" title="{{@$latest_trainer->name}}" >
                            <figure>
{{--                                <div class="score"><strong>8.9</strong></div>--}}
                                <img src="{{$latest_trainer->image_thumbnail}}" class="img-fluid" alt="{{@$latest_trainer->name}}">
                                <div class="info">
{{--                                    <div class="cat_star"><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i></div>--}}
                                    @if($latest_trainer->gym_name)<p>"{{$latest_trainer->gym_name}}"</p>@endif
                                    <h3>{{@$latest_trainer->name}}</h3>
                                </div>
                            </figure>
                        </a>
                    </div>
                    <!-- /grid_item -->
                    @endforeach
                </div>
                <!-- /row -->
                <a href="{{route('trainers')}}"><strong>{{trans('global.view_all')}}  @if($lang == 'ar')<i class="arrow_carrot-left"></i> @else <i class="arrow_carrot-right"></i> @endif</strong></a>
            </section>
            <!-- /section -->


        </div>
        <!-- /container -->
        @endif

        @if(count(@$calorie_categories)>0)
        <div class="container container-custom margin_30_95">
            <section class="add_bottom_45">
                <div class="main_title_3">
                    <span><em></em></span>
                    <h1>{{trans('global.calories_table')}}</h1>
                    <p style="font-size: 17px;">{{trans('global.home_calories_table_msg')}}</p>
                </div>
                <div class="row">
                    @foreach($calorie_categories as $calorie_category)
                        <div class="col-xl-3 col-lg-6 col-md-6">
                            <a href="{{route('calorieCategory', [$calorie_category->id, $calorie_category->slug])}}" class="grid_item">
                                <figure>
{{--                                    <div class="score"><strong>8.9</strong></div>--}}
                                    <img src="{{$calorie_category->image_thumbnail}}" class="img-fluid" alt="{{@$calorie_category->name}}">
                                    <div class="info">
{{--                                        <div class="cat_star"><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i></div>--}}
                                        <h3>{{@$calorie_category->name}}</h3>
                                    </div>
                                </figure>
                            </a>
                        </div>
                    <!-- /grid_item -->
                    @endforeach
                </div>
                <!-- /row -->
                <a href="{{route('calorieCategories')}}"><strong>{{trans('global.view_all')}}  @if($lang == 'ar')<i class="arrow_carrot-left"></i> @else <i class="arrow_carrot-right"></i> @endif</strong></a>
            </section>
            <!-- /section -->


        </div>
        <!-- /container -->
        @endif

        @if(count(@$latest_articles)>0)
        <div class="bg_color_1">
            <div class="container margin_80_55">
                <div class="main_title_2">
                    <span><em></em></span>
                    <h3>{{trans('global.sport_articles')}}</h3>
                    <p style="font-size:18px;">{{trans('global.home_articles_msg')}}</p>
                </div>
                <div class="row">
                    @foreach($latest_articles  as $latest_article)
                    <div class="col-lg-6">
                        <a class="box_news" href="{{route('article', [$latest_article->id, $latest_article->slug])}}" title="{{$latest_article->title}}">
                            <figure><img src="{{$latest_article->image_thumbnail}}" alt="{{$latest_article->title}}">
{{--                                <figcaption><strong>28</strong>Dec</figcaption>--}}
                            </figure>
                            <ul>
                                <li>{{@$latest_article->user->name}}</li>
                                <li>{{@$latest_article->created_at}}</li>
                            </ul>
                            <h4>{{$latest_article->title}}</h4>
                            <p>{{\Illuminate\Support\Str::limit(strip_tags($latest_article->description), 300, '...')}}</p>
                        </a>
                    </div>
                    <!-- /box_news -->
                    @endforeach
                </div>
                <!-- /row -->
                <p class="btn_home_align"><a href="{{route('articles')}}" class="btn_1 rounded">{{trans('global.view_all')}} </a></p>
            </div>
            <!-- /container -->
        </div>
        <!-- /bg_color_1 -->
        @endif
    </main>
    <!-- /main -->






{{--    <!-- Slider Area Start Here -->--}}
{{--    <div class="slider-area slider2-area slider-overlay slider-mt">--}}
{{--        <div class="bend niceties preview-1">--}}
{{--            <div id="ensign-nivoslider-3" class="slides">--}}
{{--                <img src="https://abctechweb.net/envato/templates/boosted/rtl/light/img/slider/5-1.jpg" alt="slider" title="#slider-direction-1" />--}}
{{--                <img src="https://abctechweb.net/envato/templates/boosted/rtl/light/img/slider/5-2.jpg" alt="slider" title="#slider-direction-2" />--}}
{{--                <img src="https://abctechweb.net/envato/templates/boosted/rtl/light/img/slider/5-3.jpg" alt="slider" title="#slider-direction-3" />--}}
{{--            </div>--}}
{{--            <div id="slider-direction-1" class="t-cn slider-direction">--}}
{{--                <div class="slider-content s-tb slide-1">--}}
{{--                    <div class="title-container s-tb-c">--}}
{{--                        <div class="container text-left p-lr40">--}}
{{--                            <h1 class="title1 mb-30">The Best Yoga Center<br><span>For Fitness Body<br>and Better Mind</span></h1>--}}
{{--                            <div class="slider-btn-area">--}}
{{--                                <a href="#" class="btn-primary-fill-ghost">try a free class</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div id="slider-direction-2" class="t-cn slider-direction">--}}
{{--                <div class="slider-content s-tb slide-2">--}}
{{--                    <div class="title-container s-tb-c">--}}
{{--                        <div class="container text-right p-lr40">--}}
{{--                            <h1 class="title1 mb-30">The Best Yoga Center<br><span>For Fitness Body<br>and Better Mind</span></h1>--}}
{{--                            <div class="slider-btn-area">--}}
{{--                                <a href="#" class="btn-primary-fill-ghost">try a free class</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div id="slider-direction-3" class="t-cn slider-direction">--}}
{{--                <div class="slider-content s-tb slide-3">--}}
{{--                    <div class="title-container s-tb-c">--}}
{{--                        <div class="container text-left p-lr40">--}}
{{--                            <h1 class="title1 mb-30">The Best Yoga Center<br><span>For Fitness Body<br>and Better Mind</span></h1>--}}
{{--                            <div class="slider-btn-area">--}}
{{--                                <a href="#" class="btn-primary-fill-ghost">try a free class</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- Slider Area End Here -->--}}
{{--    <!-- About Us 3 Area Start Here -->--}}
{{--    <div class="about-us3-area body-bg">--}}
{{--        <div class="container">--}}
{{--            <div class="row no-gutters about3-wrapper d-lg-flex">--}}
{{--                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 primary-bg">--}}
{{--                        <div class="container">--}}

{{--                            <div class="row primary-bg subscribe-wrapper">--}}
{{--                                <form class="subscribe-form" method="get" action="{{route('search')}}">--}}
{{--                                    <div class="col-lg-9 col-md-9 col-sm-7 col-xs-12">--}}
{{--                                        <ul>--}}
{{--                                            <li>--}}
{{--                                                <select class="form-control" name="city" id="city"--}}
{{--                                                        data-error="Name field is required" required>--}}
{{--                                                    <option value="">{{trans('global.all_city')}}</option>--}}
{{--                                                    @foreach($cities as $city)--}}
{{--                                                        <option value="{{$city['id']}}"--}}
{{--                                                                @if($city['id'] == @$filters['city']) selected="" @endif--}}
{{--                                                        >{{$city['name']}}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <select class="form-control" name="district" id="district_id"--}}
{{--                                                        data-error="Name field is required" required>--}}
{{--                                                    <option value="">{{trans('global.all_area')}}</option>--}}
{{--                                                    @foreach($districts as $district)--}}
{{--                                                        <option value="{{$district['id']}}"--}}
{{--                                                                class="districts_of_city_{{$district['city_id']}}"--}}
{{--                                                                @if($district['id'] == @$filters['district_id']) selected=""@endif--}}
{{--                                                        >{{$district['name']}}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <input placeholder="{{trans('global.keyword')}}" name="keyword" class="form-control" type="text">--}}
{{--                                            </li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-lg-3 col-md-3 col-sm-7 col-xs-12">--}}
{{--                                        <ul>--}}
{{--                                            --}}{{--<li>--}}
{{--                                            --}}{{--<input placeholder="Email address" class="form-control" type="text">--}}
{{--                                            --}}{{--</li>--}}
{{--                                            <li><button type="submit" class="btn-fill-ghost">{{trans('global.search')}}</button></li>--}}
{{--                                        </ul>--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                </div>--}}
{{--                --}}{{--<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">--}}
{{--                    --}}{{--<div class="single-item">--}}
{{--                        --}}{{--<i class="flaticon-stop-1" aria-hidden="true"></i>--}}
{{--                        --}}{{--<h3>Happy Environment</h3>--}}
{{--                        --}}{{--<p>Our place is a lifestyle brand embracing body, beauty, mind & spirit. Your are wel come to visit our center where every. Through the practices of yoga,</p>--}}
{{--                        --}}{{--<a href="#" class="btn-ghost-round">read more</a>--}}
{{--                    --}}{{--</div>--}}
{{--                --}}{{--</div>--}}
{{--                --}}{{--<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 body-bg">--}}
{{--                    --}}{{--<div class="single-item-right ">--}}
{{--                        --}}{{--<h3>what next</h3>--}}
{{--                        --}}{{--<p>Upcoming Events</p>--}}
{{--                        --}}{{--<h4>Yoga Festival in Japan</h4>--}}
{{--                        --}}{{--<ul>--}}
{{--                            --}}{{--<li><i class="fa fa-calendar" aria-hidden="true"></i> Dec 05, 2016</li>--}}
{{--                            --}}{{--<li><i class="fa fa-clock-o" aria-hidden="true"></i> 9:00 AM - 5:00 PM</li>--}}
{{--                            --}}{{--<li><i class="fa fa-map-marker" aria-hidden="true"></i> Vellington, Vic 941, New York</li>--}}
{{--                        --}}{{--</ul>--}}
{{--                        --}}{{--<h4>Yoga Festival in America</h4>--}}
{{--                        --}}{{--<ul>--}}
{{--                            --}}{{--<li><i class="fa fa-calendar" aria-hidden="true"></i> Dec 05, 2016</li>--}}
{{--                            --}}{{--<li><i class="fa fa-clock-o" aria-hidden="true"></i> 9:00 AM - 5:00 PM</li>--}}
{{--                            --}}{{--<li><i class="fa fa-map-marker" aria-hidden="true"></i> Vellington, Vic 941, New York</li>--}}
{{--                        --}}{{--</ul>--}}
{{--                    --}}{{--</div>--}}
{{--                --}}{{--</div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- About Us 3 Area End Here -->--}}



{{--    <!-- Subscribe Area Start Here -->--}}
{{--    <div class="subscribe-area search-area">--}}
{{--        <div class="container">--}}
{{--            <h2 class="section-title"><span>{{trans('global.search_gym')}}</span></h2>--}}
{{--            <div class="section-title-bar"><i class="flaticon-dumbbell"></i></div>--}}
{{--        </div>--}}
{{--        <div class="container">--}}

{{--            <div class="row primary-bg subscribe-wrapper">--}}
{{--                <form class="subscribe-form" method="get" action="{{route('search')}}">--}}
{{--                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-12">--}}
{{--                        <ul>--}}
{{--                            <li>--}}
{{--                                <select class="form-control" name="city" id="city"--}}
{{--                                        data-error="Name field is required" required>--}}
{{--                                    <option value="">{{trans('global.all_city')}}</option>--}}
{{--                                    @foreach($cities as $city)--}}
{{--                                        <option value="{{$city['id']}}"--}}
{{--                                                @if($city['id'] == @$filters['city']) selected="" @endif--}}
{{--                                        >{{$city['name']}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <select class="form-control" name="district" id="district_id"--}}
{{--                                        data-error="Name field is required" required>--}}
{{--                                    <option value="">{{trans('global.all_area')}}</option>--}}
{{--                                    @foreach($districts as $district)--}}
{{--                                        <option value="{{$district['id']}}"--}}
{{--                                                class="districts_of_city_{{$district['city_id']}}"--}}
{{--                                                @if($district['id'] == @$filters['district_id']) selected=""@endif--}}
{{--                                        >{{$district['name']}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <input placeholder="{{trans('global.keyword')}}" name="keyword" class="form-control" type="text">--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                </div>--}}
{{--                <div class="col-lg-3 col-md-3 col-sm-7 col-xs-12">--}}
{{--                        <ul>--}}
{{--                            --}}{{--<li>--}}
{{--                                --}}{{--<input placeholder="Email address" class="form-control" type="text">--}}
{{--                            --}}{{--</li>--}}
{{--                            <li><button type="submit" class="btn-fill-ghost">{{trans('global.search')}}</button></li>--}}
{{--                        </ul>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    <!-- Subscribe Area End Here -->--}}


{{--    @if(count(@$features_gyms)>0)--}}
{{--    <!-- Popular Classes 3 Area Start Here -->--}}
{{--    <div class=" body-bg" style="padding-top: 50px;">--}}
{{--        <div class="container">--}}
{{--            <h2 class="section-title"><span>{{trans('global.gym_features')}}</span></h2>--}}
{{--            <div class="section-title-bar"><i class="flaticon-dumbbell"></i></div>--}}
{{--        </div>--}}
{{--        <div class="container menu-list-wrapper">--}}
{{--            <div class="row menu-list">--}}

{{--                @foreach($features_gyms as $features_gym)--}}
{{--                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 yoga meditation menu-item">--}}
{{--                        <div class="classes-box-layout1">--}}
{{--                            <div class="item-img-wrapper">--}}
{{--                                <a href="{{route('gym', [$features_gym->id, $features_gym->slug])}}" title="{{$features_gym->name}}"><img src="{{$features_gym->image_thumbnail}}" alt="{{$features_gym->name}}" class="img-responsive"></a>--}}
{{--                            </div>--}}
{{--                            <div class="item-content-wrapper">--}}
{{--                                @if($features_gym->price_format)--}}
{{--                                    <div class="item-price">{{$features_gym->price_format}} <span--}}
{{--                                                style="font-size: 7px;color:white;margin-bottom: 6px;">{{trans('admin.currency_unit_ex')}}</span>--}}
{{--                                    </div>@endif--}}
{{--                                --}}{{--<span><i class="fa fa-user" aria-hidden="true"></i>Olivia</span>--}}
{{--                                <h3 class="maher-text-cut"><a href="{{route('gym', [$features_gym->id, $features_gym->slug])}}" title="{{$features_gym->name}}">{{$features_gym->name}}</a></h3>--}}
{{--                                <ul>--}}
{{--                                    <li class="maher-text-cut">{{$features_gym->address}}</li>--}}
{{--                                    <li class="maher-text-cut">{{$features_gym->district->name}}, {{$features_gym->district->city->name}}</li>--}}
{{--                                </ul>--}}
{{--                                --}}{{--<div class="text-center mt-30">--}}
{{--                                --}}{{--<a href="single-class.html" class="btn-primarytext-ghost2">Read More</a>--}}
{{--                                --}}{{--</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}


{{--            </div>--}}
{{--            --}}{{--<div class="loadmore-center loadmore">--}}
{{--                --}}{{--<a href="#" class="fill-color-btn">view all classes</a>--}}
{{--            --}}{{--</div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- Popular Classes 3 Area End Here -->--}}
{{--    @endif--}}

{{--    @if(count(@$latest_articles)>0)--}}
{{--    <!-- Blog 2 Area Start Here -->--}}
{{--    <div class="section-space-all accent-light">--}}
{{--        <div class="container">--}}
{{--            <h2 class="section-title">{{trans('global.articles')}}</h2>--}}
{{--            <div class="section-title-bar"><i class="flaticon-dumbbell"></i></div>--}}
{{--        </div>--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                @foreach($latest_articles  as $latest_article)--}}
{{--                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mb-xs-list">--}}
{{--                    <div class="blog-box-layout1">--}}
{{--                        <div class="item-img-wrapper ">--}}
{{--                            <a href="{{route('article', [$latest_article->id, $latest_article->slug])}}" title="{{$latest_article->title}}" ><img src="{{$latest_article->image_thumbnail}}" alt="{{$latest_article->title}}" class="img-responsive"></a>--}}
{{--                        </div>--}}
{{--                        <div class="item-content-wrapper">--}}
{{--                            <h3><a href="{{route('article', [$latest_article->id, $latest_article->slug])}}" title="{{$latest_article->title}}">{{$latest_article->title}}</a></h3>--}}
{{--                            <span>{{$latest_article->arabic_date}}<span> | <a href="#">{{$latest_article->user->name}}</a></span></span>--}}
{{--                            <p>{{$latest_article->short_description}}</p>--}}
{{--                            <a href="{{route('article', [$latest_article->id, $latest_article->slug])}}" class="btn-primarytext-ghost">{{trans('global.more')}}</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- Blog 2 Area End Here -->--}}
{{--    @endif--}}


{{--    @if(count(@$latest_gyms)>0)--}}
{{--    <!-- Popular Classes 3 Area Start Here -->--}}
{{--    <div class="section-space-all body-bg">--}}
{{--        <div class="container">--}}
{{--            <h2 class="section-title"><span>{{trans('global.gyms')}}</span></h2>--}}
{{--            <div class="section-title-bar"><i class="flaticon-dumbbell"></i></div>--}}
{{--        </div>--}}
{{--        <div class="container menu-list-wrapper">--}}
{{--            <div class="row menu-list">--}}
{{--                @foreach($latest_gyms as $latest_gym)--}}
{{--                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 yoga meditation menu-item">--}}
{{--                        <div class="classes-box-layout1">--}}
{{--                            <div class="item-img-wrapper">--}}
{{--                                <a href="{{route('gym', [$latest_gym->id, $latest_gym->slug])}}" title="{{$latest_gym->name}}"><img src="{{$latest_gym->image_thumbnail}}" alt="{{$latest_gym->name}}" class="img-responsive"></a>--}}
{{--                            </div>--}}
{{--                            <div class="item-content-wrapper">--}}
{{--                                @if($latest_gym->price)--}}
{{--                                    <div class="item-price">{{$latest_gym->price_format}} <span--}}
{{--                                                style="font-size: 7px;color:white;margin-bottom: 6px;">{{trans('admin.currency_unit_ex')}}</span>--}}
{{--                                    </div>@endif--}}
{{--                                --}}{{--<span><i class="fa fa-user" aria-hidden="true"></i>Olivia</span>--}}
{{--                                <h3 class="maher-text-cut"><a href="{{route('gym', [$latest_gym->id, $latest_gym->slug])}}" title="{{$latest_gym->name}}">{{$latest_gym->name}}</a></h3>--}}
{{--                                <ul>--}}
{{--                                    --}}{{--<li class="maher-text-cut">{{$latest_gym->address}}</li>--}}
{{--                                    <li class="maher-text-cut">{{$latest_gym->district->name}}, {{$latest_gym->district->city->name}}</li>--}}
{{--                                </ul>--}}
{{--                                --}}{{--<div class="text-center mt-30">--}}
{{--                                --}}{{--<a href="single-class.html" class="btn-primarytext-ghost2">Read More</a>--}}
{{--                                --}}{{--</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}

{{--            </div>--}}
{{--            --}}{{--<div class="loadmore-center loadmore">--}}
{{--                --}}{{--<a href="#" class="fill-color-btn">view all classes</a>--}}
{{--            --}}{{--</div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- Popular Classes 3 Area End Here -->--}}
{{--    @endif--}}


{{--    @if(count(@$latest_trainers)>0)--}}
{{--    <!-- Team 3 Area Start Here -->--}}
{{--    <div class="section-space-all accent-color">--}}
{{--        <div class="container">--}}
{{--            <h2 class="section-title"><a href="{{route('trainers')}}">{{trans('global.trainers')}}</a></h2>--}}
{{--            <div class="section-title-bar"><i class="flaticon-dumbbell"></i></div>--}}
{{--        </div>--}}
{{--        <div class="container menu-list-wrapper">--}}
{{--            <div class="row menu-list">--}}
{{--                @foreach($latest_trainers as $latest_trainer)--}}
{{--                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 menu-item">--}}
{{--                    <div class="team-box-layout3">--}}
{{--                        <div class="item-img-wrapper">--}}
{{--                            <a href="{{route('trainer', [$latest_trainer->id, $latest_trainer->slug])}}" title="{{$latest_trainer->name}}"> <img src="{{$latest_trainer->image_thumbnail}}" alt="{{$latest_trainer->name}}" class="img-responsive img-circle maher-trainer-image"></a>--}}
{{--                        </div>--}}
{{--                        <div class="item-content-wrapper">--}}
{{--                            <h3 class="maher-text-cut"><a  href="{{route('trainer', [$latest_trainer->id, $latest_trainer->slug])}}" title="{{$latest_trainer->name}}">{{$latest_trainer->name}}</a></h3>--}}
{{--                            <span class="maher-text-cut" title="{{$latest_trainer->title}}">{{$latest_trainer->title}}</span>--}}
{{--                            <ul>--}}
{{--                                @if($latest_trainer->facebook)<li><a href="{{$latest_trainer->facebook}}" target="_blank" title="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>@endif--}}
{{--                                @if($latest_trainer->twitter)<li><a href="{{$latest_trainer->twitter}}" target="_blank" title="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>@endif--}}
{{--                                @if($latest_trainer->instagram)<li><a href="{{$latest_trainer->instagram}}" target="_blank" title="instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>@endif--}}
{{--                                @if($latest_trainer->linkedin)<li><a href="{{$latest_trainer->linkedin}}" target="_blank" title="linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>@endif--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- Team 3 Area End Here -->--}}
{{--    @endif--}}




@endsection
@section('script')
<script>

    city = $("#city").val();
    district_id = $("#district_id").val();
    $("#district_id option").hide();
    $("#district_id option:first").show();
    $("#district_id option.districts_of_city_" + city).show();

    $("#city").change(function (e) {
        var city = $("#city").val();
        $("#district_id option").hide();
        $("#district_id option:selected").removeAttr('selected');
        $("#district_id option.districts_of_city_" + city).show();
        $("#district_id option:first").show();
        $("#district_id option:first").attr('selected', true);
    });
</script>
@endsection
