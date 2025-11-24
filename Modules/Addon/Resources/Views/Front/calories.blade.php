@extends('generic::Front.layouts.master')
@section('title'){{ $title }} | @endsection
@section('style')
<style>
    .img-fluid {
        height: 100%;
        object-fit: cover;
    }

    .hero_in.general:before {
        @if($calorie_category)
        background: url({{$calorie_category->image}}) center center no-repeat;
        @else
        background: url({{asset('resources/assets/front/img/bg/healty.jpg')}}) center center no-repeat;
        @endif
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
                            @foreach($calorie_categories as $calorie_category)
                            <li><a href="{{route('calorieCategory', [$calorie_category->id, $calorie_category->slug])}}" @if($calorie_category->id == @$category_id) class="active" @endif><i class="icon_document_alt"></i>{{$calorie_category->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!--/sticky -->
                </aside>
                <!--/aside -->

                <div class="col-lg-9" id="faq">
                    <h4 class="nomargin_top">{{$title}}</h4>
                    <div role="tablist" class="add_bottom_45 accordion_2" id="gyms">
                            @foreach($calories as $calorie)
                            <div class="card">
                                <div class="card-header" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#" aria-expanded="true"><i class="indicator">{{$calorie->calories}} <span style="font-size: 9px;">{{trans('global.calorie')}}</span></i>{{$calorie->name}}<br/><div style="font-size: 12px;clear: both;padding-top: 10px;">{{trans('global.calories_for')}} {{$calorie->unit}} {{calorie_units($lang)[(int)$calorie->unit_id]}}</div></a>
                                    </h5>
                                </div>

                            </div>
                            <!-- /card -->
                            @endforeach
                    </div>
                    <!-- /accordion payment -->

                </div>
                <!-- /col -->
            </div>
            <!-- /row -->
        </div>
        <!--/container-->

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

    </main>
    <!--/main-->

@endsection
