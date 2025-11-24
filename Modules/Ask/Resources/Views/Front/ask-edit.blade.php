@extends('generic::Front.layouts.master')
@section('title'){{ $title }} | @endsection
@section('style')
    <!-- SPECIFIC CSS -->
    <link href="{{asset('resources/assets/front/css/blog.css')}}" rel="stylesheet">
    @if($lang == 'ar')
        <link href="{{asset('resources/assets/front/css/blog-rtl.css')}}" rel="stylesheet">
    @endif


    <link rel="stylesheet" type="text/css" href="{{asset('resources/assets/admin/')}}/global/plugins/select2/select2.css"/>
    <link href="{{asset('resources/assets/admin/')}}/global/css/plugins-rtl.css" rel="stylesheet" type="text/css"/>

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

        .comment_info a {
            color: #fff !important;
        }

        .control-label {
            margin-bottom: 14px;
            font-size: large;
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
                <!-- /single-post -->

                <div class="bloglist singlepost">
                    <h1>{{$title}}</h1>
                    <!-- /post meta -->
                    <div class="post-content">
                        @include('generic::errors')

                        <form method="post" action="{{route('editQuestionAsk', $question->token)}}">
                            {{csrf_field()}}
                            <br/><br/>
                            <div class="form-group">
                                <label class="control-label">{{trans('global.ask_question')}} <span class="required">*</span></label>
                                <input type="text" name="question" id="question" class="form-control" maxlength="255" value="{{old('question', $question->question)}}" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{trans('global.question_details')}}</label>
                                <textarea  name="details" id="details" maxlength="12000" rows="5" class="form-control">{{old('details', $question->details)}}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{trans('admin.category')}}</label>
                                <select name="category_id" id="category_id" class="form-control" style="padding-top: 4px !important;">
                                    <option value="">{{trans('global.no_classification')}}</option>
                                    @foreach($article_categories as $article_category)
                                        <option value="{{$article_category->id}}" @if(@old('category_id', $question->category_id) == $article_category->id) selected="" @endif>{{$article_category->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{trans('global.tags')}} <span style="font-size: 14px;">({{trans('global.tag_form_msg')}})</span></label>
                                <input type="hidden" id="select2_sample5" name="tags" class="form-control select2" value="{{old('tags', @$questionTags)}}">
                            </div>
                            @if(!$currentUser)<div style="font-size: 14px;">{{trans('global.ask_login_msg')}} <a id="login_btn"   href="#sign-in-dialog"  class="login sign-in-form"  title="{{trans('global.login')}}" >{{trans('global.login')}}</a></div>@endif
                            <br/>
                            <div class="form-group">
                                <button type="submit" id="submit2" class="btn_1 rounded add_bottom_30"> {{trans('global.save')}}</button>
                            </div>
                        </form>

                    </div>
                    <!-- /post -->
                </div>

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
@section('script')
<script type="text/javascript" src="{{asset('resources/assets/admin/')}}/global/plugins/select2/select2.min.js"></script>
<script>
    $("#select2_sample5").select2({
        tags: [{!! $tags !!}]
    });

</script>
@endsection
