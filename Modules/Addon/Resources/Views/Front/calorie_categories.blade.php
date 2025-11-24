@extends('generic::Front.layouts.master')
@section('style')
<style>
    .img-fluid {
        height: 100%;
        object-fit: cover;
    }

    .hero_in.general:before {
        background: url({{asset('resources/assets/front/img/bg/healty.jpg')}}) center center no-repeat;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
</style>
@endsection

@section('content')


    <main>
        <section class="hero_in general">
            <div class="wrapper">
                <div class="container">
                    <h1 class="fadeInUp"><span></span>{{$title}}</h1>
                </div>
            </div>
        </section>
        <!--/hero_in-->
        @if(count(@$calorie_categories)>0)
            <div class="container container-custom margin_30_95">
                <section class="add_bottom_45">
                    <div class="main_title_3">
                        <span><em></em></span>
                        <h1>{{trans('global.calorie_categories')}}</h1>
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
                    {{--                <a href="{{route('trainers')}}"><strong>{{trans('global.view_all')}}  @if($lang == 'ar')<i class="arrow_carrot-left"></i> @else <i class="arrow_carrot-right"></i> @endif</strong></a>--}}
                </section>
                <!-- /section -->


            </div>
            <!-- /container -->
        @endif


        <div class="bg_color_1">
            <div class="container margin_60_35">
                <div class="main_title_3">
                    <span><em></em></span>
                    <h2>{{trans('global.calculates_title')}}</h2>
                    <p>{{trans('global.calculates_msg')}}</p>
                </div>
                <div class="list_articles add_bottom_30 clearfix">
                    <ul>

                        @include('addon::Front.calculates.calculate_side_bar')
                    </ul>
                </div>
                <!-- /list_articles -->
            </div>
            <!-- /container -->
        </div>
        <!-- /bg_color_1 -->

        @if(isset($latest_articles) &&count(@$latest_articles)>0)
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

@endsection
