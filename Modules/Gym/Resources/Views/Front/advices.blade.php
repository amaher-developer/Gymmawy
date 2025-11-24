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

        <div class="container margin_60_35">
            <div class="row">
                <div class="col-lg-9">
                    @if(count($advices) > 0)
                    @foreach($advices as $advice)
                            <article class="blog wow fadeIn">
                                <div class="row no-gutters">
                                    <div class="col-lg-12">
                                        <div class="post_info">
                                            <small>{{$advice->title}}</small>
                                            <h3><a href="{{route('advice',[$advice->id, $advice->slug])}}">{{$advice->content}}</a></h3>
                                            <ul>
                                                <li>
                                                    <div class="thumb"><img src="{{@asset('./resources/assets/front/img/logo/default.png')}}" alt=""></div>  {{trans('global.gymmawy')}}
                                                </li>
                                                <li><div class="score"><span> <em><br/></em></span></div></li>
                                                {{--                                        <li><i class="icon_comment_alt"></i> </li>--}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                    <!-- /article -->
                    @endforeach
                    @else
                        <h3>"{{@request('keyword')}}"</h3>
                        <div style="font-size: 16px;padding-top: 25px;">{{trans('global.not_found_msg')}}</div>
                    @endif

                    <nav aria-label="...">
                        <ul class="pagination pagination-sm">
                            {{$advices->appends(Request::except('page'))->links()}}
                        </ul>
                    </nav>
                    <!-- /pagination -->
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
                    <!-- /widget -->

                    <!-- /widget -->
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
                    <!-- /widget -->
                </aside>
                <!-- /aside -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </main>
    <!--/main-->























{{--    --}}
{{--    <!-- Inner Page Banner Area Start Here -->--}}
{{--    <div class="inner-page-banner-area  search-area" style="background-image: url('img/banner/inner-banner.jpg');">--}}
{{--        <div class="container">--}}
{{--            <div class="pagination-area">--}}
{{--                <h2 class="inner-section-title-textprimary">{{trans('global.articles')}}</span></h2>--}}
{{--                <div class="section-title-bar"><i class="flaticon-dumbbell"></i></div>--}}
{{--                <ul>--}}
{{--                    <li><a href="{{route('home')}}">{{trans('global.home')}}</a> -</li>--}}
{{--                    @if(request('category_id'))--}}
{{--                        <li><a href="{{route('articles')}}">{{trans('global.articles')}}</a> -</li>--}}
{{--                    @endif--}}
{{--                    <li>{{$title}}</li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- Inner Page Banner Area End Here -->--}}
{{--    <!-- Blog Page 3 Area Start Here -->--}}
{{--    <div class="section-space-all body-bg">--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">--}}
{{--                    @foreach($articles as $article)--}}
{{--                    <div class="blog-box-layout4">--}}
{{--                        <div class="blog4-img-holder">--}}
{{--                            <a href="{{route('article',[$article->id, $article->slug])}}" ><img src="{{$article->image_thumbnail}}" class="img-responsive maher-image-cover" alt="{{$article->title}}" ></a>--}}
{{--                        </div>--}}
{{--                        <div class="blog4-content-holder">--}}
{{--                            <ul class="blog-comments">--}}
{{--                                <li><a href="#"><i class="fa fa-user" aria-hidden="true"></i>{{@$article->user->name}}</a></li>--}}
{{--                                --}}{{--<li><a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i>05</a></li>--}}
{{--                                --}}{{--<li><a href="#"><i class="fa fa-comment-o" aria-hidden="true"></i>02</a></li>--}}
{{--                            </ul>--}}
{{--                            <h2><span>{{date('j', $article->update_at)}}<span> {{month_to_ar(date('F', $article->update_at))}}</span></span><a href="{{route('article',[$article->id, $article->slug])}}">{{$article->title}}</a></h2>--}}
{{--                            <p>{{\Illuminate\Support\Str::limit(strip_tags($article->description), 300, '...')}}</p>--}}
{{--                            <a href="{{route('article',[$article->id, $article->slug])}}" class="ghost-btn2">{{trans('global.more')}}</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    @endforeach--}}

{{--                        <div class="col-lg-12 text-center">{{$articles->appends(Request::except('page'))->links()}}</div>--}}

{{--                        --}}{{--<ul class="pagination-left">--}}
{{--                        --}}{{--<li><i class="fa fa-arrow-left" aria-hidden="true"></i></li>--}}
{{--                        --}}{{--<li class="active"><a href="#">1</a></li>--}}
{{--                        --}}{{--<li><a href="#">2</a></li>--}}
{{--                        --}}{{--<li><a href="#">3</a></li>--}}
{{--                        --}}{{--<li><a href="#">4</a></li>--}}
{{--                        --}}{{--<li><i class="fa fa-arrow-right" aria-hidden="true"></i></li>--}}
{{--                    --}}{{--</ul>--}}
{{--                </div>--}}
{{--                <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">--}}
{{--                    <div class="boosted-sidebar">--}}
{{--                        <div class="sidebar-box">--}}
{{--                            <div class="sidebar-search-area">--}}
{{--                                <form class="subscribe-form" method="get" action="{{route('articles')}}">--}}

{{--                                <div class="input-group stylish-input-group">--}}
{{--                                    <input type="text" name="keyword" value="{{@request('keyword')}}" class="form-control" placeholder="{{trans('global.keyword')}}">--}}
{{--                                    <span class="input-group-addon">--}}
{{--                                                <button type="submit">--}}
{{--                                                    <span class="glyphicon glyphicon-search"></span>--}}
{{--                                        </button>--}}
{{--                                        </span>--}}
{{--                                </div>--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="sidebar-box">--}}
{{--                            <div class="categories">--}}
{{--                                <h3 class="title-sidebar">{{trans('admin.categories')}}</h3>--}}
{{--                                <ul>--}}
{{--                                    @foreach($article_categories as $article_category)--}}
{{--                                    <li><a href="{{route('articleCategory', [$article_category->id, $article_category->slug])}}">{{$article_category->name}}</a></li>--}}
{{--                                    @endforeach--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="sidebar-box">--}}
{{--                            <div class="popular-post">--}}
{{--                                <ul>--}}
{{--                                    <li class="active"><a href="#Popular" data-toggle="tab" aria-expanded="false" class="btn-accent-to-primary">{{trans('global.news_popular')}}</a></li>--}}
{{--                                    <li><a href="#Latest" data-toggle="tab" aria-expanded="false" class="btn-accent-to-primary">{{trans('global.news_last')}}</a></li>--}}
{{--                                </ul>--}}
{{--                                <div class="tab-content">--}}
{{--                                    <div class="popular-post tab-pane fade active in" id="Popular">--}}
{{--                                        @foreach($popular_articles as $popular_article)--}}
{{--                                        <div class="media">--}}
{{--                                            <a href="{{route('article', [$popular_article->id, $popular_article->slug])}}" title="{{$popular_article->title}}" class="pull-left">--}}
{{--                                                <img alt="{{$popular_article->title}}" src="{{$popular_article->image_thumbnail}}" class="img-responsive maher-article-side-image">--}}
{{--                                            </a>--}}
{{--                                            <div class="media-body">--}}
{{--                                                <a href="{{route('article', [$popular_article->id, $popular_article->slug])}}"  class="maher-text-cut" title="{{$popular_article->title}}">{{$popular_article->title}}</a>--}}
{{--                                                <ul class="post-info">--}}
{{--                                                    <li>{{@$popular_article->user->name}}<br></li>--}}
{{--                                                    <li>{{date('j', $popular_article->update_at)}} {{month_to_ar(date('F', $popular_article->update_at))}}</li>--}}

{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}
{{--                                    <div class="popular-post tab-pane fade" id="Latest">--}}
{{--                                        @foreach($last_articles as $last_article)--}}
{{--                                        <div class="media">--}}
{{--                                            <a href="{{route('article', [$last_article->id, $last_article->slug])}}"  title="{{$last_article->title}}" class="pull-left ">--}}
{{--                                                <img alt="{{$last_article->title}}" src="{{$last_article->image_thumbnail}}" class="img-responsive maher-article-side-image">--}}
{{--                                            </a>--}}
{{--                                            <div class="media-body">--}}
{{--                                                <a href="{{route('article', [$last_article->id, $last_article->slug])}}" title="{{$last_article->title}}"  class="maher-text-cut">{{$last_article->title}}</a>--}}
{{--                                                <ul class="post-info">--}}
{{--                                                    <li>{{@$last_article->user->name}}<br></li>--}}
{{--                                                    <li>{{date('j', $popular_article->update_at)}} {{month_to_ar(date('F', $popular_article->update_at))}}</li>--}}
{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="sidebar-box">--}}
{{--                            <div class="product-tags">--}}
{{--                                <h3 class="title-sidebar">{{trans('admin.categories')}}</h3>--}}
{{--                                <ul>--}}
{{--                                    @foreach($article_categories as $article_category)--}}
{{--                                        <li><a href="{{route('articleCategory', [$article_category->id, $article_category->slug])}}">{{$article_category->name}}</a></li>--}}
{{--                                    @endforeach--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="sidebar-box">--}}
{{--                            <div class="sidebar-subscription">--}}
{{--                                <h3>Subscription</h3>--}}
{{--                                <form>--}}
{{--                                    <fieldset>--}}
{{--                                        <div class="form-group has-error has-danger">--}}
{{--                                            <input type="email" placeholder="Enter your email here" class="form-control" name="email" id="form-email" data-error="Email field is required" required="">--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group">--}}
{{--                                            <button type="submit" class="btn-fill-ghost2 disabled">subscribe</button>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-response"></div>--}}
{{--                                    </fieldset>--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- Blog Page 3  Area End Here -->--}}

@endsection
