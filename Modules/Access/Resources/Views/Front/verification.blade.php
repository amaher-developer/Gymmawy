@extends('generic::Front.layouts.master')
@section('style')
<style>
    .profile_pages h2 {
        margin-top: 20px;
        font-size: 18px;
        color: rgba(106, 112, 120, 1);
        text-align: center;
    }
    .code_widget p {
        font-size: 14px;
        color: rgba(106, 112, 120, 1);
        text-align: right;
    }
    .send_code_widget .number span, .code_widget .number span {
        background: #f36347;
        color: #fff;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: inline-block;
        text-align: center;
        padding-top: 1px;
        margin-left: 5px;
    }
    .custom_button {
        float: left;
        background-color: #f36347;
        border-radius: 8px;
        border: 0px;
        font-size: 18px;
        font-weight: normal;
        color: rgba(255, 255, 255, 1);
        text-align: right;
        padding: 7px 30px;
        margin-bottom: 50px;
    }
    .timer {
        font-size: 25px;
        font-weight: bold;
    }
    .send_code_widget .number, .code_widget .number {
        float: right;
        width: 9%;
    }
    .send_code_widget .content, .code_widget .content {
        float: right;
        width: 91%;
    }
</style>

@stop
@section('content')


    <!-- Inner Page Banner Area Start Here -->
    <div class="inner-page-banner-area" style="background-image: url('img/banner/about-banner.jpg');">
        <div class="container">
            <div class="pagination-area">
                <h2 class="inner-section-title-textprimary">{{trans('global.request_verification_code')}}</h2>
                <div class="section-title-bar"><i class="flaticon-dumbbell"></i></div>
                <ul>
                    <li><a href="{{route('home')}}">{{trans('global.home')}}</a> -</li>
                    <li><a href="{{route('login')}}">{{trans('global.login')}}</a> -</li>
                    <li>{{trans('global.request_verification_code')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Inner Page Banner Area End Here -->
    <!-- About Page Area Start Here -->
    <div class="section-space-top body-bg" style="padding: 100px; 0">
        <div class="container">


            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <div class="similar profile_aside">
                        <h3 class="title">{{$currentUser->name}}</h3>
                        <div class="widgets">
                            <h4><a class="active" href="{{route('showUserFront')}}">{{trans('global.update_profile')}}</a>
                            </h4>
                            {{--<h4><a href="{{route('savedSearchList')}}">{{trans('global.saved_search')}}</a></h4>--}}
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-xs-12">
                    <div class="row  text-center">

                        <div class="timer">90</div>

                        <div class="maher-sms-contact-div" style="display: none;float: none !important;">
                            <p  style='font-size: 14px;color: rgba(106, 112, 120, 1);'>{{trans('global.sms_contact_us')}} </p>
                            <p  style='font-size: 14px;color: rgba(106, 112, 120, 1);direction: ltr;'>{{@$mainSettings->call_center_numbers[array_rand(@$mainSettings->call_center_numbers,1)]}}</p>
                        </div>
                        <div class="col-xs-12">
                            <h2>{{trans('global.request_verification_code')}}</h2>
                        </div>
                        <div class="col-md-11 col-md-offset-1 col-xs-12">
                            <div class="send_code_widget">
                                <div class="number">
                                    <span>1</span>
                                </div>
                                <div class="content">
                                    <p>
                                        {{trans('global.click_to_send_pin')."  "}}:  {{$currentUser->phone}}
                                    </p>
                                </div>
                                <div class="clearfix">
                                    <button type="button" class="btn btn-default custom_button" id="send_button"
                                             onclick="timer(); return false;">{{trans('global.send')}}
                                    </button>
                                </div>
                            </div>
                            <div class="code_widget">
                                <div class="number">
                                    <span>2</span>
                                </div>

                                <form action="{{route('verifyPhone')}}">
                                    @if(request('itemAddedFlag'))
                                        <input type="hidden" name="itemAddedFlag" value="1">
                                        @endif
                                    <div class="content">
                                        <p>
                                            {{trans('global.type_pin')}}
                                        </p>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="{{trans('global.code')}}" name="phone_verification_code" id="phone_verification_code" required>
                                        </div>
                                    </div>
                                    <div class="clearfix actions_buttons">
                                        <button type="submit" class="btn btn-default custom_button">{{trans('global.verify')}}</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('script')

    <script>
        function timer() {


            $('<img id="loading" style="margin-top: 10px;margin-left: 15px;"  src="{{asset('resources/assets/front/img/loading.gif')}}" />').insertAfter('#send_button');
            $('#send_button').hide();
            $.ajax({
                url: "{{route('sendPhoneVerificationCode')}}",
                cache: false,
                type: 'GET',
                success: function (response) {
                    //alert(response);
                    if (response == 'true') {
                        //alert('sss');
                        var timer = 90;
                        var interval = setInterval(function () {
                            timer--;
                            $('.timer').text(timer);
                            if (timer === 0) {
                                $('.maher-sms-contact-div').show();
                                $('.timer').hide();
                                //$('#submit_button').prop("disabled", false);
                                clearInterval(interval);
                            }
                        }, 1000);

                        document.getElementById("send_button").setAttribute('disabled', 'disabled');
                        $('#send_button').show();
                        $('#loading').hide();
                    }else{
                        $('.maher-sms-contact-div').show();
                        $('.timer').hide();
                        //$('#submit_button').prop("disabled", false);
                        clearInterval(interval);
                    }
                    console.log(response);
                },
                error: function (request, error) {


                    $('#send_button').show();
                    console.log(error);
//                    swal("Operation failed", "Something went wrong.", "error");
                    console.error("Request: " + JSON.stringify(request));
                    console.error("Error: " + JSON.stringify(error));
                }
            });
            $('#loading').remove();



        }
    </script>

@stop
