@extends('generic::Front.layouts.master')
@section('title'){{ $title }} | @endsection
@section('style')
<style>
    .img-fluid {
        height: 100%;
        object-fit: cover;
    }

    .hero_in.general:before {
        background: url({{asset('resources/assets/front/img/bg/articles.jpg')}}) center center no-repeat;
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

        <div class="container margin_60_35">
            <div class="row">
                <aside class="col-lg-3" id="sidebar">
                    <div class="box_style_cat" id="faq_box">
                        <ul id="cat_nav">
                            <li><a href="#gyms" class="active"><i class="icon_document_alt"></i>{{trans('global.gyms')}}</a></li>
                            <li><a href="#trainers"><i class="icon_document_alt"></i>{{trans('global.trainers')}}</a></li>
{{--                            <li><a href="#reccomendations"><i class="icon_document_alt"></i>Reccomendations</a></li>--}}
{{--                            <li><a href="#terms"><i class="icon_document_alt"></i>Terms&amp;conditons</a></li>--}}
{{--                            <li><a href="#booking"><i class="icon_document_alt"></i>Booking</a></li>--}}
                        </ul>
                    </div>
                    <!--/sticky -->
                </aside>
                <!--/aside -->

                <div class="col-lg-9" id="faq">
                    <h4 class="nomargin_top">{{trans('global.gyms')}}</h4>
                    <div role="tablist" class="add_bottom_45 accordion_2" id="gyms">
                        <div class="row">
                            @foreach($gyms as $gym)
                                <div class="item col-lg-6">
                                    <div class="box_grid">
                                        <figure>
                                            <a href="#sign-in-dialog" id="favorite_1_{{$gym->id}}" onclick="
                                            @if(@$currentUser && $gym->favorites && @in_array($currentUser->id, $gym->favorites->pluck('user_id')->toArray())) removeFavorite('{{$gym->id}}', 1); return false;
                                            @else addFavorite('{{$gym->id}}', 1); return false;  @endif
                                                    "
                                               class="
                                @if(!@$currentUser) login sign-in-form @endif wish_bt
                                @if($gym->favorites && @in_array($currentUser->id, $gym->favorites->pluck('user_id')->toArray())) liked @endif
                                                       "></a>
                                            <a href="{{route('gym', [$gym->id, $gym->slug])}}">
                                                <img src="{{$gym->image}}" class="img-fluid" alt="" width="800" height="533"><div class="read_more"><span>{{trans('global.details')}}</span></div></a>
                                            <small>{{@$gym->categories[0]->name}}</small>
                                        </figure>
                                        <div class="wrapper">
                                            <h3><a href="{{route('gym', [$gym->id, $gym->slug])}}">{{$gym->name}}</a></h3>
                                            <p>{{$gym->gym_address}}</p>
                                            <span class="price">{{@$gym->district->name}}, {{@$gym->district->city->name}}</span>
                                        </div>
                                        <ul>
                                            <li><i class="icon_clock_alt"></i> {{$gym->views}} {{trans('global.views')}}</li>
                                            <li><div class="score"><span>{{trans('global.articles')}}</span><strong>{{(int)$gym->articles}}</strong></div></li>
                                            {{--                            <li><div class="score"><span>Superb<em>350 Reviews</em></span><strong>8.9</strong></div></li>--}}
                                        </ul>
                                    </div>
                                </div>
                                <!-- /item -->
                            @endforeach
                        </div>
                    </div>
                    <!-- /accordion payment -->
                    <h4 class="nomargin_top">{{trans('global.trainers')}}</h4>
                    <div role="tablist" class="add_bottom_45 accordion_2" id="trainers">
                        <div class="row">
                            @foreach($trainers as $key => $trainer)
                                <div class="col-lg-4 isotope-item @if(($key+1) % 3 == 0) latest @else popular @endif">
                                    <div class="box_grid">
                                        <figure>
                                            <a href="#sign-in-dialog"
                                               id="favorite_2_{{$trainer->id}}" onclick="
                                            @if(@$currentUser && $trainer->favorites && @in_array($currentUser->id, @$trainer->favorites->pluck('user_id')->toArray())) removeFavorite('{{$trainer->id}}', 2); return false;
                                            @else addFavorite('{{$trainer->id}}', 2); return false;  @endif
                                                    "
                                               class="
                                                    @if(!@$currentUser) login sign-in-form @endif wish_bt
                                                    @if($trainer->favorites && @in_array($currentUser->id, @(array)$trainer->favorites->pluck('user_id')->toArray())) liked @endif
                                                       "
                                            ></a>
                                            <a href="{{route('trainer', [$trainer->id, $trainer->slug])}}">
                                                <img src="{{$trainer->image}}" class="img-fluid" alt=""
                                                     width="800" height="533">
                                                <div class="read_more"><span>{{trans('global.details')}}</span></div>
                                            </a>
                                            <small>{{$trainer->gym_name}}</small>
                                        </figure>
                                        <div class="wrapper">
                                            <h3>
                                                <a href="{{route('trainer', [$trainer->id, $trainer->slug])}}">{{$trainer->name}}</a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <!-- /box_grid -->
                            @endforeach
                        </div>
                        <!-- /row -->
                    </div>
                    <!-- /accordion suggestions -->

                </div>
                <!-- /col -->
            </div>
            <!-- /row -->
        </div>
        <!--/container-->
    </main>
    <!--/main-->

@endsection