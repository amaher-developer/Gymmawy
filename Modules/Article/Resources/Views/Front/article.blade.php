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
            background: url({{@$article->image}}) center center no-repeat;
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
        .dropcaps h2, h3 {
            display: block;
            font-size: 1.5em;
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }
        .dropcaps p{
            margin-bottom: 2px;
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
                <div class="col-lg-9">
                    <div class="bloglist singlepost">
                        <div style="line-height: 1.8;margin-bottom: 30px">
                            @if(@$article->youtube)
                                <div class="grid">
                                    <ul class="magnific-gallery">
                                        <li style="width: 100%">
                                            <figure>
                                                <img src="{{@$article->youtube_image}}" class="img-fluid"
                                                     alt="{{$article->title}}"
                                                     style="height: 400px;width: 100%;object-fit: cover;">
                                                <figcaption>
                                                    <div class="caption-content">
                                                        <a href="{{$article->youtube_link}}" class="video"
                                                           title="{{$article->title}}">
                                                            <i class="pe-7s-film"></i>
                                                            <p>{{$article->title}}</p>
                                                        </a>
                                                    </div>
                                                </figcaption>
                                            </figure>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <img alt="{{$article->title}}" class="img-fluid" src="{{@$article->image}}"
                                     style="width: 100%;height: 200px;object-fit: contain;">
                            @endif
                        </div>
                        <h1>{{$article->title}}</h1>
                        <div class="postmeta">
                            <ul>
                                <li><a href="#" title="{{@$article->category->name}}"><i
                                                class="icon_folder-alt"></i> {{@$article->category->name}}</a></li>
                                <li><a href="#" title="{{$article->created_at}}"><i
                                                class="icon_clock_alt"></i> {{$article->created_at}}
                                    </a></li>
                                <li><a href="#" title="{{@$article->user->name}}"><i
                                                class="icon_pencil-edit"></i> {{@$article->user->name}}</a></li>
                                {{--                                <li><a href="#"><i class="icon_comment_alt"></i> (14) Comments</a></li>--}}
                            </ul>
                        </div>
                        <!-- /post meta -->
                        <!-- ShareThis BEGIN -->
                        <div class="sharethis-inline-share-buttons"
                             @if($lang == 'ar') style="text-align: right!important;"
                             @else style="text-align: left!important;" @endif></div>
                        <!-- ShareThis END -->


{{--                        <div class="text-center">--}}
{{--                        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8099437480361514"--}}
{{--                                crossorigin="anonymous"></script>--}}
{{--                        <!-- banner 728*90 -->--}}
{{--                        <ins class="adsbygoogle"--}}
{{--                             style="display:inline-block;width:728px;height:90px"--}}
{{--                             data-ad-client="ca-pub-8099437480361514"--}}
{{--                             data-ad-slot="2499442542"></ins>--}}
{{--                        <script>--}}
{{--                            (adsbygoogle = window.adsbygoogle || []).push({});--}}
{{--                        </script>--}}
{{--                        </div>--}}

                        <div class="post-content">
                            <div class="dropcaps">
                                <div >
                                    <p>
                                        {!! strip_tags($article->description, "<iframe></iframe><img><img/><a><br><br/><p><strong><b><h1><h2><h3><h4>"); !!}

                                    </p>
                                </div>
                            </div>
                        <div class="clearfix"></div>
                        </div>
                        <!-- /post -->
                    </div>


{{--                    <div class="text-center">--}}
{{--                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8099437480361514"--}}
{{--                            crossorigin="anonymous"></script>--}}

{{--                    <!-- banner 728*90 -->--}}
{{--                    <ins class="adsbygoogle"--}}
{{--                         style="display:inline-block;width:728px;height:90px"--}}
{{--                         data-ad-client="ca-pub-8099437480361514"--}}
{{--                         data-ad-slot="2499442542"></ins>--}}
{{--                    <script>--}}
{{--                        (adsbygoogle = window.adsbygoogle || []).push({});--}}
{{--                    </script>--}}
{{--                    </div>--}}
                    @if(@$article->calculates['ibw'] == 1) @include('addon::Front.article_calculates.ibw') @endif
                    @if(@$article->calculates['calories'] == 1) @include('addon::Front.article_calculates.calories') @endif
                    @if(@$article->calculates['bmi'] == 1) @include('addon::Front.article_calculates.bmi') @endif
                    @if(@$article->calculates['water'] == 1) @include('addon::Front.article_calculates.water') @endif


                    <!-- /single-post -->
                    <div class="clearfix" style="clear:both; float: none"></div>
                    <div id="comments">
                        <h5>{{trans('global.comments')}}</h5>
                        <div class="fb-comments" data-width="100%"
                             data-href="{{route('article', [$article->id, $article->slug])}}" data-numposts="5"></div>
                    </div>

                </div>
                <!-- /col -->


                <aside class="col-lg-3">
                    <div class="widget">
                        <form method="get" action="{{route('articles')}}">
                            <div class="form-group">
                                <input type="text" name="keyword" value="{{@request('keyword')}}"
                                       placeholder="{{trans('global.keyword')}}" id="search" class="form-control"
                                       placeholder="{{trans('global.search')}}...">
                            </div>
                            <button type="submit" id="submit" class="btn_1 rounded"> {{trans('global.search')}}</button>
                        </form>
                    </div>
                    <!-- /widget -->


{{--                    <div class="text-center">--}}
{{--                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8099437480361514"--}}
{{--                            crossorigin="anonymous"></script>--}}
{{--                    <!-- side-menu 250*250 -->--}}
{{--                    <ins class="adsbygoogle"--}}
{{--                         style="display:inline-block;width:250px;height:250px"--}}
{{--                         data-ad-client="ca-pub-8099437480361514"--}}
{{--                         data-ad-slot="5794839666"></ins>--}}
{{--                    <script>--}}
{{--                        (adsbygoogle = window.adsbygoogle || []).push({});--}}
{{--                    </script>--}}
{{--                    </div>--}}

                    <div class="widget col-md-12">
                        <a href="https://demo.gymmawy.com" title="{{trans('global.gym_managment_system')}}" target="_blank" >
                            <img style="width: 100%" src="{{asset('resources/assets/front/img/ads/system.png')}}" class="img-responsive">
                        </a>
                    </div>
                    
                    <div class="widget">
                        <div class="widget-title">
                            <h4>{{trans('global.news_popular')}}</h4>
                        </div>
                        <ul class="comments-list">
                            @foreach($popular_articles as $popular_article)
                                <li>
                                    <div class="alignleft">
                                        <a href="{{route('article', [$popular_article->id, $popular_article->slug])}}"
                                           title="{{$popular_article->title}}"><img
                                                    src="{{$popular_article->image_thumbnail}}"
                                                    alt="{{$popular_article->title}}"></a>
                                    </div>
                                    <small>{{$popular_article->created_at}}</small>
                                    <h3><a href="{{route('article', [$popular_article->id, $popular_article->slug])}}"
                                           title="{{$popular_article->title}}">{{$popular_article->title}}</a></h3>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                    <!-- /widget -->

                    <div class="widget col-md-12">
                        <a href="https://onelink.to/gymmawy" title="{{trans('global.app_gymmawy')}}" target="_blank" >
                            <img style="width: 100%" src="{{asset('resources/assets/front/img/ads/trainer.png')}}" class="img-responsive">
                        </a>
                    </div>
                    <div class="widget">
                        <div class="widget-title">
                            <h4>{{trans('global.categories')}}</h4>
                        </div>
                        <ul class="cats">

                            @foreach($article_categories as $article_category)
                                <li>
                                    <a href="{{route('articleCategory', [$article_category->id, $article_category->slug])}}">{{$article_category->name}}
                                        {{--                                        <span>({{$article_category->articles}})</span>--}}
                                    </a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /widget -->
                    @if(count($article->tags) > 0)
                        <div class="widget">
                            <div class="widget-title">
                                <h4>{{trans('global.tags')}}</h4>
                            </div>
                            <div class="tags">
                                @foreach($article->tags as $tag)
                                    <a href="{{route('articles.tag', trim($tag->slug))}}">{{$tag->name}}</a>
                                @endforeach
                            </div>
                        </div>
                        <!-- /widget -->
                    @endif
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
