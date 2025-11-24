@extends('generic::Front.layouts.master')
@section('title'){{ $title }} | @endsection
@section('style')
<style>
    .img-fluid {
        height: 100%;
        object-fit: cover;
    }

    .hero_in.adventure_detail:before {
{{--        background: url({{asset('resources/assets/front/img/bg/bg_adventure_detail.jpg')}}) center center no-repeat;--}}
        background: url({{$bodybuilder->cover_image}}) center center no-repeat;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
    .pl-lg-4 h2{
        font-size: 1.0rem;
        padding: 5px 0;
    }
    .lead {
        padding-top: 15px;
        font-size: 1.0rem;
        line-height: 2;
    }
    .pl-lg-4 {
        padding-top: 20px;
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
        <section class="hero_in adventure_detail">
            <div class="wrapper opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.5)">
                <div class="container">
                    <div class="main_info">
                        <div class="row">
                            <div class="col-md-4">
{{--                                <div class="d-flex align-items-center justify-content-between mb-3"><em>3 Day Trip</em><div class="score"><span>Superb<em>350 Reviews</em></span><strong>8.9</strong></div></div>--}}
                                <h1>{{$bodybuilder->name}}</h1>
                                <p><img src="{{@$bodybuilder->country->flag}}" class="flag"> {{$bodybuilder->country->name}}</p>
                            </div>
                        </div>
                        <!-- /row -->
                    </div>
                    <!-- /main_info -->
                </div>
            </div>
        </section>
        <!--/hero_in-->

        <div class="bg_color_1">

            <div class="container margin_60_35 adventure_description">
                <div class="row mb-5">
                    <div class="col-md-4 fixed_title">
{{--                        <h2>{{trans('global.details')}}</h2>--}}
                        <h2><img src="{{$bodybuilder->image}}" style="width: 100%;"></h2>
                        <!-- ShareThis BEGIN -->
                        <div class="sharethis-inline-share-buttons" style="text-align: center!important;"></div>
                        <!-- ShareThis END -->
                    </div>
                    <div class="col-md-8">
                        <div class="pl-lg-4">
                            <h2><b>{{trans('global.name')}}:</b> {{$bodybuilder->name}}</h2>
                            @if(@$bodybuilder->district)<h2><b>{{trans('global.district')}}:</b> {{@$bodybuilder->district->name}}</h2>@endif
                            @if(@$bodybuilder->district)<h2><b>{{trans('global.city')}}:</b> {{@$bodybuilder->district->city->name}}</h2>@endif
                            <h2><b>{{trans('global.country')}}:</b><img src="{{@$bodybuilder->country->flag}}" class="flag"> {{@$bodybuilder->country->name}}</h2>
                            @if(@$bodybuilder->birthday)<h2><b>{{trans('global.birthday')}}:</b> {{@$bodybuilder->birthday}}</h2>@endif
                            <p class="lead">
                                {!! $bodybuilder->description !!}
                            </p>
                            <div style="clear: both;"></div>
                            @if(count($bodybuilder->competitions) >0)
                            <h2><b>{{trans('global.championships')}}</b></h2>
                            <div style="clear: both;padding-top: 20px;"></div>
                            <div class="timeline">
                                @foreach($bodybuilder->competitions as $competition)
                                <div class="mb-5">
                                    <h3>{{$competition->name}} - {{$competition->year}}</h3>
                                </div>
                                @endforeach

                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /row -->



            </div>
            <!-- /container -->
        </div>
        <!-- /bg_color_1 -->
    </main>
    <!--/main-->

@endsection
@section('script')
    <script type='text/javascript'
            src='https://platform-api.sharethis.com/js/sharethis.js#property=615e105ee35b180013fb27af&product=sop'
            async='async'></script>
@endsection
