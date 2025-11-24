@extends('generic::Front.layouts.master')
@section('style')

@stop
@section('content')
    <section class="profile_pages">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-xs-12">

                </div>

                <div class="col-md-5 col-xs-12">
                    <div class="row text-center">
                        <div class="timer">90</div>
                        <div class="col-xs-12">
                            <h2>{{trans('global.submit_verification_code')}} </h2>
                        </div>
                        <div class="col-md-11 col-md-offset-1 col-xs-12">
                            <form action="{{$route}}" method="post">
                                {{csrf_field()}}
                                @foreach($additional_inputs as $key=> $additional_input)
                                    <input type="hidden" value="{{$additional_input}}" name="{{$key}}">
                                @endforeach

                                <div class="content text-center">


                                    <p>
                                        {{trans('global.type_pin')}}
                                    </p>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="{{trans('global.code')}}" name="phone_verification_code" required>
                                    </div>
                                </div>

                                <div class="maher-sms-contact-div" style="display: none;">
                                    <p  style='font-size: 14px;color: rgba(106, 112, 120, 1);'>{{trans('global.sms_contact_us')}} </p>
                                    <p  style='font-size: 14px;color: rgba(106, 112, 120, 1);direction: ltr;'>{{@$mainSettings->call_center_numbers[array_rand(@$mainSettings->call_center_numbers,1)]}}</p>
                                </div>
                                <div class="clearfix actions_buttons">
                                    <button type="submit" id="submit_button" class="btn btn-default custom_button">{{trans('global.verify')}}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>


@endsection

@section('script')

    <script>
        var timer = 90;

        var interval = setInterval(function() {
            timer--;
            $('.timer').text(timer);
            if (timer === 0) {
                $('.maher-sms-contact-div').show();
                //$('#submit_button').prop("disabled", false);
                clearInterval(interval);
            }
        }, 1000);
    </script>

@stop
