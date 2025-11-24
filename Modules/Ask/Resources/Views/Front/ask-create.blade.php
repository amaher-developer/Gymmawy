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
            background: url({{asset('resources/assets/front/img/bg/asks.jpg')}}) center center no-repeat;
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
        .qa-ask-similar {
            background-color: #ecf0f1;
            padding: 10px;
            margin-bottom: 5px;
        }
        .qa-ask-similar-title {
            margin: -10px -10px 10px;
            padding: 10px;
            background: #7f8c8d;
            color: #fff;
        }
        .qa-q-title-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .qa-q-title-item {
            padding: 2px 0;
            border-bottom: 1px solid #d9dcde;
        }
        .qa-q-title-item a {
            color: #ff6c3d;
            text-decoration: none;
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
                    <h1>{{trans('global.add_question')}}</h1>
                    <!-- /post meta -->
                    <div class="post-content">
                        @include('generic::errors')

                        <form method="post" action="{{route('createQuestionAsk')}}">
                            {{csrf_field()}}
                            <br/><br/>
                            <div class="form-group">
                                <label class="control-label">{{trans('global.ask_question')}} <span class="required">*</span></label>
                                <input type="text" name="question" id="question" class="form-control" maxlength="255" value="{{old('question')}}" placeholder="" required>
                            </div>
                            <div id="div_related_questions" class="clearfix"></div>
                            <div class="form-group">
                                <label class="control-label">{{trans('global.question_details')}}</label>
                                <textarea  name="details" id="details" maxlength="12000" rows="5" class="form-control">{{old('details')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{trans('global.email')}} <span style="font-size: 14px;">({{trans('global.email_form_msg')}})</span></label>
                                <input type="email" name="email" id="email" class="form-control"  value="{{old('email', @$currentUser->email)}}" placeholder="">
                                <span style="font-size: 12px;">{{trans('global.email_form_msg2')}}</span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{trans('admin.category')}}</label>
                                <select name="category_id" id="category_id" class="form-control" style="padding-top: 4px !important;">
                                    <option value="">{{trans('global.no_classification')}}</option>
                                    @foreach($article_categories as $article_category)
                                        <option value="{{$article_category->id}}" @if(@old('category_id') == $article_category->id) selected="" @endif>{{$article_category->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{trans('global.tags')}} <span style="font-size: 14px;">({{trans('global.tag_form_msg')}})</span></label>
                                <input type="hidden" id="select2_sample5" name="tags" class="form-control select2" value="{{old('tags')}}">
                            </div>
                            @if(!$currentUser)<div style="font-size: 14px;">{{trans('global.ask_login_msg')}} <a id="login_btn"   href="#sign-in-dialog"  class="login sign-in-form"  title="{{trans('global.login')}}" >{{trans('global.login')}}</a></div>@endif
                            <br/>
                            <div class="form-group">
                                <button type="submit" id="submit2" class="btn_1 rounded add_bottom_30"> {{trans('global.submit')}}</button>
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

    $('#question').keyup(function () {
        let question = $('#question').val();
        if(question.length > 4){
            $.post("{{route('getRelatedQuestionsAjax')}}", {  question: question, _token: '{{csrf_token()}}' },
                function(result){
                    if(result){
                        let get_result = JSON.parse(result);
                        let q = '<div class="qa-ask-similar"><p class="qa-ask-similar-title">{{trans('global.related_questions_msg')}}:</p>'
                            + '<ul class="qa-q-title-list">';
                        for(let i = 0; i < get_result.length; i++){
                            let question_link = '{{route('ask', ['id' => '::id', 'slug' => '::slug'])}}';
                            question_link = question_link.replace("::id", get_result[i].id);
                            question_link = question_link.replace("::slug", get_result[i].slug);
                            q+='<li class="qa-q-title-item"><a href="'+question_link+'" target="_blank">'+get_result[i].question+'</a></li>';
                        }
                        q+='</ul></div>';
                        $('#div_related_questions').html(q);
                    }else{
                        $('#div_related_questions').html('');
                    }
                }
            );
        }
    });

</script>
@endsection
