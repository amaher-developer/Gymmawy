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


        @include('ask::Front.ask-bar')

    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-9">
                <div class="bloglist singlepost">
                    <h1>{{$question->question}}</h1>
                    <p>{!! nl2br($question->details) !!}</p>
                    <hr/>
                    <div class="postmeta">
                        <ul>
                            <li><i class="icon_folder-alt"></i> {{@$question->category->name}}</li>
                            <li><i class="icon_clock_alt"></i> {{$question->created_at}}</li>
                            <li><i class="icon_pencil-edit"></i> {{@$question->name ?? (@$question->user->name ?? trans('global.guest'))}}</li>
                            <li><i class="icon_comment_alt"></i> ({{count($answers)}}) {{trans('global.answer')}}</li>
                            @if(session('question_token') && (session('question_token') == $question->token))<li class="text-left"><a href="{{route('editQuestionAsk', @$question->token)}}" style="color: #2196f3 !important;font-size: 14px;"><i class="icon_pencil"></i> {{trans('global.edit')}}</a></li>@endif
                            @if(session('question_token') && (session('question_token') == $question->token) && ($question->published == 1))<li class="text-left"><a href="{{route('hideQuestionAsk', @$question->token)}}" style="color: #2196f3 !important;font-size: 14px;"><i class="icon-eye-off"></i> {{trans('global.hide')}}</a></li>@endif
                            @if(session('question_token') && (session('question_token') == $question->token) && ($question->published == 0))<li class="text-left"><a href="{{route('hideQuestionAsk', @$question->token)}}" style="color: #2196f3 !important;font-size: 14px;"><i class="icon-eye"></i> {{trans('global.show')}}</a></li>@endif

                        </ul>

                        <a class="btn btn-success add_answer" ><i class="icon_comment_alt"></i> {{trans('global.add_answer')}}</a>
                    </div>
                    <!-- /post meta -->
                </div>
                <!-- /single-post -->

                <div class="answer_block">
                <hr>
                <h5>{{trans('global.add_answer')}}</h5>

                    <form method="post" action="{{route('createAnswerAsk', $question->id)}}">
                    {{csrf_field()}}
                    <div class="form-group">
                        <input type="text" name="name" id="name" class="form-control" placeholder="{{trans('global.name')}}" value="{{@$currentUser->name}}">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" id="email" class="form-control" placeholder="{{trans('global.email')}}" value="{{@$currentUser->email}}">
                        <span style="font-size: 12px;">{{trans('global.email_form_msg2')}}</span>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="answer" required id="answer" rows="6" placeholder="{{trans('global.answer')}} *"></textarea>
                    </div>

                        @if(!$currentUser)<div style="font-size: 14px;">{{trans('global.ask_login_msg')}} <a id="login_btn"   href="#sign-in-dialog"  class="login sign-in-form"  title="{{trans('global.login')}}" >{{trans('global.login')}}</a></div>@endif
                        <br/>
                    <div class="form-group">
                        <button type="submit" id="submit2" class="btn_1 rounded add_bottom_30"> {{trans('global.submit')}}</button>
                    </div>
                </form>
                </div>

                @include('generic::errors')

                <div id="comments">
                    <h5 class="comments_label">{{trans('global.answers')}} ({{count($answers)}})</h5>
                    <ul>
                        @foreach($answers as $answer)
                        <li>
                            <div class="avatar">
                                <img src="{{$answer->user->image ?? asset('resources/assets/front/img/logo/default_'.$lang.'.png')}}" alt="">
                            </div>
                            <div class="comment_right clearfix">
                                <div class="comment_info">
                                    {{trans('global.by')}} {{@$answer->user->name ?? @$answer->name ?? trans('global.guest')}} <span>|</span> {{$answer->created_at}} <span>|</span> <a  class="btn btn-danger" onclick="reply_toggle({{$answer->id}})"><i class="icon_comment_alt"></i> {{trans('global.add_comment')}}</a>
                                </div>
                                <p>
                                    {!! nl2br($answer->answer) !!}
                                </p>
                            </div>

                            <div class="reply_block" id="reply_block_{{$answer->id}}">
                                <hr>
                                <h5>{{trans('global.add_comment')}}</h5>
                                    <div class="form-group">
                                        <input type="text" name="name" id="name{{$answer->id}}" class="form-control" placeholder="" value="{{@$currentUser->name}}">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="email" id="email{{$answer->id}}" class="form-control" placeholder="" value="{{@$currentUser->email}}">
                                        <span style="font-size: 12px;">{{trans('global.email_form_msg2')}}</span>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="answer" id="answer{{$answer->id}}" rows="6" placeholder=""></textarea>
                                    </div>

                                @if(!$currentUser)<div style="font-size: 14px;">{{trans('global.ask_login_msg')}} <a id="login_btn"   href="#sign-in-dialog"  class="login sign-in-form"  title="{{trans('global.login')}}" >{{trans('global.login')}}</a></div>@endif
                                <br/>
                                <div class="form-group">
                                        <button type="button" id="submit{{$answer->id}}" onclick="submit_reply('{{$answer->id}}')" class="btn_1 rounded add_bottom_30"> {{trans('global.submit')}}</button>
                                    </div>
                            </div>


                            <ul class="replied-to" id="reply_li_{{$answer->id}}">
                                @if($answer->child_answers)
                                @foreach($answer->child_answers as $child_answer)
                                <li >
                                    <div class="avatar">
                                        <img src="{{$child_answer->user->image ?? asset('resources/assets/front/img/logo/default_'.$lang.'.png')}}" alt="">
                                    </div>
                                    <div class="comment_right clearfix">
                                        <div class="comment_info">
                                            {{trans('global.by')}} {{@$child_answer->user->name}} <span>|</span> {{$child_answer->created_at}}
                                        </div>
                                        <p>
                                            {!! nl2br($child_answer->answer) !!}
                                        </p>
                                    </div>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </li>
                        @endforeach
                    </ul>
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
<script>
    $('.add_answer').on('click', function(e) {
        $('.answer_block').toggle("slow"); //you can list several class names
        e.preventDefault();
    });

    function reply_toggle(id){
        $('#reply_block_'+id).toggle("slow"); //you can list several class names
        return false;
    }
    function submit_reply(id){
        var answer = $('#answer'+id).val();
        var email = $('#email'+id).val();
        var name = $('#name'+id).val();
        $.ajax({
            url: '{{route('storeReplyAsk')}}',
            method: "POST",
            data: {answer_id: id, email: email, name: name, answer: answer,_token: "{{csrf_token()}}"},
            dataType: "text",
            success: function (data) {
                if (data == '1') {
                    var result = '<li><div class="avatar"><img src="{{$currentUser->image ?? asset('resources/assets/front/img/logo/default_'.$lang.'.png')}}" alt=""> </div>' +
                        '<div class="comment_right clearfix"> <div class="comment_info">' +
                            "{{trans('global.by')}} {{@$currentUser->name ?? trans('global.guest')}} <span>|</span> {{\Carbon\Carbon::now()->toDateTimeString()}}"
                        + '</div><p>'+ answer +'</p></div></li>';
                    $("#reply_li_"+id).append(result);
                    $('#reply_block_'+id).toggle("slow");
                    $('#answer'+id).val('');
                    alert('{{trans('global.reply_add_successfully')}}');

                }

            }
        });


    }
</script>
@endsection
