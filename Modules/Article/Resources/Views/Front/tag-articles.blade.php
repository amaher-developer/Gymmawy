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
                <div class="col-lg-9">
                    <h3 class="fadeInUp"><span></span>{{$title}}</h3>
                    <br/>
                    @if(count($articles) > 0)
                    @foreach($articles as $article)
                    <article class="blog wow fadeIn">
                        <div class="row no-gutters">
                            <div class="col-lg-7">
                                <figure>
                                    <a href="{{route('article',[$article->id, $article->slug])}}" >
                                        <img src="{{$article->image_thumbnail}}" alt="{{$article->title}}">
                                        <div class="preview"><span>{{trans('global.details')}}</span></div>
                                    </a>
                                </figure>
                            </div>
                            <div class="col-lg-5">
                                <div class="post_info">
                                    <small>{{$article->created_at}}</small>
                                    <h3><a href="{{route('article',[$article->id, $article->slug])}}">{{$article->title}}</a></h3>
                                    <p>
                                        {{\Illuminate\Support\Str::limit(strip_tags($article->description), 300, '...')}}
                                    </p>
                                    <ul>
                                        <li>
                                            <div class="thumb"><img src="{{$article->user->image ? $article->user->image :  asset('resources/assets/front/img/avatar_placeholder.png')}}" alt=""></div> {{$article->user->name}}
                                        </li>
                                        <li><i class="icon_comment_alt"></i> </li>
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
                            {{$articles->appends(Request::except('page'))->links()}}
{{--                            <li class="page-item disabled">--}}
{{--                                <a class="page-link" href="#" tabindex="-1">Previous</a>--}}
{{--                            </li>--}}
{{--                            <li class="page-item"><a class="page-link" href="#">1</a></li>--}}
{{--                            <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                            <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
{{--                            <li class="page-item">--}}
{{--                                <a class="page-link" href="#">Next</a>--}}
{{--                            </li>--}}
                        </ul>
                    </nav>
                    <!-- /pagination -->
                </div>
                <!-- /col -->

                <aside class="col-lg-3">
                    <div class="widget">
                        <form method="get" action="{{route('articles')}}">
                            <div class="form-group">
                                <input type="text" name="keyword" value="{{@request('keyword')}}" placeholder="{{trans('global.keyword')}}" id="search" class="form-control" placeholder="{{trans('global.search')}}...">
                            </div>
                            <button type="submit" id="submit" class="btn_1 rounded"> {{trans('global.search')}}</button>
                        </form>
                    </div>
                    <!-- /widget -->

                    <div class="widget">
                        <div class="widget-title">
                            <h4>{{trans('global.categories')}}</h4>
                        </div>
                        <ul class="cats">
                            @foreach($article_categories as $article_category)
                                <li><a href="{{route('articleCategory', [$article_category->id, $article_category->slug])}}">{{$article_category->name}} <!--<span>({{$article_category->articles}})</span>--></a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /widget -->
                    <div class="widget">
                        <div class="widget-title">
                            <h4>{{trans('global.news_popular')}}</h4>
                        </div>
                        <ul class="comments-list">
                            @foreach($popular_articles as $popular_article)
                            <li>
                                <div class="alignleft">
                                    <a href="{{route('article', [$popular_article->id, $popular_article->slug])}}"><img src="{{$popular_article->image_thumbnail}}" alt="{{$popular_article->title}}"></a>
                                </div>
                                <small>{{$popular_article->created_at}}</small>
                                <h3><a href="{{route('article', [$popular_article->id, $popular_article->slug])}}" >{{$popular_article->title}}</a></h3>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /widget -->
{{--                    <div class="widget">--}}
{{--                        <div class="widget-title">--}}
{{--                            <h4>Popular Tags</h4>--}}
{{--                        </div>--}}
{{--                        <div class="tags">--}}
{{--                            <a href="#">Information tecnology</a>--}}
{{--                            <a href="#">Students</a>--}}
{{--                            <a href="#">Community</a>--}}
{{--                            <a href="#">Carreers</a>--}}
{{--                            <a href="#">Literature</a>--}}
{{--                            <a href="#">Seminars</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
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
