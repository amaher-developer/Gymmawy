@extends('generic::Front.layouts.master')
@section('title'){{ $title }} | @endsection
@section('style')
<style>
    .hero_in.contacts:before {
        background: url({{asset('resources/assets/front/img/bg/contact.jpg')}}) center center no-repeat;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
</style>
@endsection
@section('content')


    <main>
        <section class="hero_in contacts">
            <div class="wrapper">
                <div class="container">
                    <h1 class="fadeInUp"><span></span>{{trans('global.contact_us')}}</h1>
                </div>
            </div>
        </section>
        <!--/hero_in-->

        <div class="contact_info">
            <div class="container">
                <ul class="clearfix">
                    <li>
{{--                        <i class="pe-7s-map-marker"></i>--}}
                        <h4> </h4>
                        <span> </span>
                    </li>
                    <li>
                        <i class="pe-7s-mail-open-file"></i>
                        <h4>{{trans('global.email')}}</h4>
                        <span>{{$mainSettings->support_email}}</span>

                    </li>
                    <li>
{{--                        <i class="pe-7s-phone"></i>--}}
                        <h4> </h4>
                        <span> </span>
                    </li>
                </ul>
            </div>
        </div>
        <!--/contact_info-->

        <div class="bg_color_1">
            <div class="container margin_80_55">
                <div class="row justify-content-between">
{{--                    <div class="col-lg-5">--}}
{{--                        <div class="map_contact">--}}
{{--                        </div>--}}
{{--                        <!-- /map -->--}}
{{--                    </div>--}}
                    <div class="col-lg-10">
                        <h4>{{trans('global.send_message')}}</h4>
                        <p>{{trans('global.contact_msg')}}</p>
                        <div id="message-contact"></div>

                        @include('generic::errors')

                        <form method="post" action="{{route('contact')}}"  autocomplete="off">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{trans('global.name')}} <span class="required">*</span></label>
                                        <input class="form-control" type="text" id="name_contact" name="name" required>
                                    </div>
                                </div>
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Last name</label>--}}
{{--                                        <input class="form-control" type="text" id="lastname_contact" name="lastname_contact">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                            <!-- /row -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('global.email')}} <span class="required">*</span></label>
                                        <input class="form-control" type="email" id="email_contact" name="email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('global.phone')}} <span class="required">*</span></label>
                                        <input class="form-control" type="text" id="phone_contact" name="phone" required>
                                    </div>
                                </div>
                            </div>
                            <!-- /row -->
                            <div class="form-group">
                                <label>{{trans('global.msg')}} <span class="required">*</span></label>
                                <textarea class="form-control" id="message_contact" name="message" style="height:150px;" required></textarea>
                            </div>
{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Are you human? 3 + 1 =</label>--}}
{{--                                        <input class="form-control" type="text" id="verify_contact" name="verify_contact">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <p class="add_top_30"><input type="submit" value="{{trans('global.send')}}" class="btn_1 rounded"></p>
                        </form>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /bg_color_1 -->
    </main>
    <!--/main-->




@endsection

@section('script')
    <!-- Validator js -->
    {{--<script src="{{asset('resources/assets/front/js/validator.min.js')}}" type="text/javascript"></script>--}}

@endsection