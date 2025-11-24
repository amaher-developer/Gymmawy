@extends('generic::Front.layouts.master')
@section('title'){{ $title }} | @endsection
@section('style')
    <style>
        .hero_in.general:before {
            background: url({{asset('resources/assets/front/img/bg/contact.jpg')}}) center center no-repeat;
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


        <div class="bg_color_1">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-12">
                        <p>{!! $content !!}</p>
                    </div>
                </div>
                <!--/row-->
            </div>
            <!--/container-->
        </div>
    </main>


@endsection
