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
    .flag {
        height: 25px;
        width: 25px;
        object-fit: contain;
        margin: 0 5px;
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
{{--                <aside class="col-lg-3" id="sidebar">--}}
{{--                    <div class="box_style_cat" id="faq_box">--}}
{{--                        <ul id="cat_nav">--}}
{{--                            <li><a href="#gyms" class="active"><i class="icon_document_alt"></i>{{trans('global.gyms')}}</a></li>--}}
{{--                            <li><a href="#trainers"><i class="icon_document_alt"></i>{{trans('global.trainers')}}</a></li>--}}
{{--                            <li><a href="#reccomendations"><i class="icon_document_alt"></i>Reccomendations</a></li>--}}
{{--                            <li><a href="#terms"><i class="icon_document_alt"></i>Terms&amp;conditons</a></li>--}}
{{--                            <li><a href="#booking"><i class="icon_document_alt"></i>Booking</a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                    <!--/sticky -->--}}
{{--                </aside>--}}
                <!--/aside -->

                <div class="col-lg-12" >
                    <div  class="add_bottom_45 accordion_2" >
                        <div class="row">
                            @foreach($bodybuilders as $bodybuilder)
                                <div class="item col-lg-4">
                                    <div class="box_grid">
                                        <figure>
                                            <a href="{{route('bodybuilder', [$bodybuilder->id, $bodybuilder->slug])}}">
                                                <img src="{{$bodybuilder->image}}" class="img-fluid" alt="" width="800" height="533"><div class="read_more"><span>{{trans('global.details')}}</span></div></a>
                                        </figure>
                                        <div class="wrapper">
                                            <h3><a href="{{route('bodybuilder', [$bodybuilder->id, $bodybuilder->slug])}}">{{$bodybuilder->name}}</a></h3>
                                            <span class="price"><img src="{{@$bodybuilder->country->flag}}" class="flag">  {{@$bodybuilder->country->name}}</span>
                                        </div>

                                    </div>
                                </div>
                                <!-- /item -->
                            @endforeach
                        </div>
                    </div>
                    <!-- /accordion payment -->
                    <nav aria-label="...">
                        <ul class="pagination pagination-sm">
                            {{$bodybuilders->appends(Request::except('page'))->links()}}
                        </ul>
                    </nav>

                </div>
                <!-- /col -->
            </div>
            <!-- /row -->
        </div>
        <!--/container-->
    </main>
    <!--/main-->

@endsection
