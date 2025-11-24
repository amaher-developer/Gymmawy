@extends('generic::Front.layouts.master')
@section('title'){{ $title }} | @endsection
@section('style')
    <!-- SPECIFIC CSS -->
    <link href="{{asset('resources/assets/front/css/blog.css')}}" rel="stylesheet">
    @if($lang == 'ar')
        <link href="{{asset('resources/assets/front/css/blog-rtl.css')}}" rel="stylesheet">
    @endif

    <style>
        .hero_in.general:before {
            background: url({{asset('resources/assets/front/img/bg/ask.jpg')}}) center center no-repeat;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        .img-w-100 {
            width: 100%;
        }
        .dropcaps ul li{
            list-style: inside;
        }
        .avatar img{
            width: 68px;
            height: 68px;
            object-fit: cover;
        }
        .postmeta li {
            color: #999;
        }
        .postmeta li>a {
            color: #fff !important;
        }
        .comment_info a {
            color: #fff !important;
        }
        .answer_block, .reply_block {
            display: none;
        }
        .add_answer {
            color: #fff !important;
        }
        .comments_label {
            padding: 20px;
            background-color: #fff;
        }
        .singlepost h1 {
            font-size: 26px !important;
        }
    </style>
@endsection
@section('content')

    <main>
        <section class="hero_in general">
            <div class="wrapper">
                <div class="container">
                    {{--                    <h1 class="fadeInUp"><span></span>{{$title}}</h1>--}}
                </div>
            </div>
        </section>
        <!--/hero_in-->


        <div class="container margin_60_35">
            <div class="row">
                <div class="col-lg-9">
                    <div class="bloglist singlepost">
                        <h1>{{$advice->title}}</h1>
                        <p>{!! nl2br($advice->content) !!}</p>
                        <!-- /post meta -->
                    </div>
                    <!-- /single-post -->

                    <!-- ShareThis BEGIN -->
                    <div class="sharethis-inline-share-buttons"
                         @if($lang == 'ar') style="text-align: center!important;"
                         @else style="text-align: center!important;" @endif></div>
                    <!-- ShareThis END -->


                    <!-- /single-post -->
                    <div class="clearfix" style="clear:both; float: none"></div>
{{--                    <div id="comments">--}}
{{--                        <h5>{{trans('global.comments')}}</h5>--}}
{{--                        <div class="fb-comments" data-width="100%"--}}
{{--                             data-href="{{route('advice', [$advice->id, $advice->slug])}}" data-numposts="5"></div>--}}
{{--                    </div>--}}
                </div>
                <!-- /col -->
                <aside class="col-lg-3">
                    <div class="widget">
                        <form method="get" action="{{route('advices')}}">
                            <div class="form-group">
                                <input type="text" name="keyword" value="{{@request('keyword')}}" placeholder="{{trans('global.keyword')}}" id="search" class="form-control" placeholder="{{trans('global.search')}}...">
                            </div>
                            <button type="submit" id="submit" class="btn_1 rounded"> {{trans('global.search')}}</button>
                        </form>
                    </div>
                    <div class="widget">
                        <div class="widget-title">
                            <h4>{{trans('global.news_popular')}}</h4>
                        </div>
                        <ul class="comments-list">
                            @foreach($popular_advices as $popular_advice)
                                <li>
                                    <h3><a href="{{route('advice', [$popular_advice->id, $popular_advice->slug])}}" ><i class="icon_document_alt"></i> {{$popular_advice->title}}</a></h3>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
                <!-- /aside -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->

    </main>
    <!--/main-->
@endsection

@section('script')
    <script type='text/javascript'
            src='https://platform-api.sharethis.com/js/sharethis.js#property=615e105ee35b180013fb27af&product=sop'
            async='async'></script>
    <div id="fb-root"></div>
    <script>(function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            url = 'https://connect.facebook.net/{{$lang}}_{{strtoupper($lang)}}/sdk.js#xfbml=1&version=v3.2&appId=1438969386322576&autoLogAppEvents=1';

            js.src = url;
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

@endsection


