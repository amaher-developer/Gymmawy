@extends('generic::Front.layouts.master')
@section('style')

    <!-- SPECIFIC CSS -->
    <link href="{{asset('resources/assets/front/css/blog.css')}}" rel="stylesheet">

    @if($lang == 'ar')
        <link href="{{asset('resources/assets/front/css/blog-rtl.css')}}" rel="stylesheet">
    @endif
    <!-- YOUR CUSTOM CSS -->
    <style>
        .hero_in.general:before {
            background: url({{asset('resources/assets/front/img/bg/ask.jpg')}}) center center no-repeat;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        article.blog .post_info ul {
            bottom: unset;
        }
        article.blog {
            min-height: auto;
            padding-bottom: 36px;
        }
        article.blog .post_info h3 {
            line-height: 1.6;
        }
        .score_with_answers{
            background-color: #009688 !important;
        }
        .score_without_answers{
            background-color: #9e9e9e !important;
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

        @include('ask::Front.ask-bar')
        <div class="container margin_60_35">
            <div class="row">
                <div class="col-lg-9">
                    @if(($asks->count()) > 0)
                    @foreach($asks as $ask)
                    <article class="blog wow fadeIn">
                        <div class="row no-gutters">
                            <div class="col-lg-12">
                                <div class="post_info">
                                    <small>{{$ask->created_at}}</small>
                                    <h3><a href="{{route('ask',[$ask->id, $ask->slug])}}">{{$ask->question}}</a></h3>
                                    <ul>
                                        <li>
                                            <div class="thumb"><img src="{{@$ask->user->image ? $ask->user->image :  asset('resources/assets/front/img/avatar_placeholder.png')}}" alt=""></div>  {{@$ask->name ?? (@$ask->user->name ?? trans('global.guest'))}}
                                        </li>
                                        <li><div class="score"><span>{{trans('global.answers')}}<em><br/></em></span><strong class="{{$ask->answers->count() ? 'score_with_answers' : 'score_without_answers'}}">{{$ask->answers->count()}}</strong></div></li>
{{--                                        <li><i class="icon_comment_alt"></i> </li>--}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </article>
                    <!-- /article -->
                    @endforeach
                    @else
                        <h3>"{{@request('search') ?? @request('tag') ?? @request('slug') }}"</h3>
                        <div style="font-size: 16px;padding-top: 25px;">{{trans('global.not_found_msg')}}</div>
                    @endif

                    <nav aria-label="...">
                        <ul class="pagination pagination-sm">
                            {{$asks->appends(Request::except('page'))->links()}}

                        </ul>
                    </nav>
                    <!-- /pagination -->
                </div>
                <!-- /col -->

                <aside class="col-lg-3">
                    @include('ask::Front.ask-side')
                </aside>
                <!-- /aside -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </main>
    <!--/main-->
















@endsection
