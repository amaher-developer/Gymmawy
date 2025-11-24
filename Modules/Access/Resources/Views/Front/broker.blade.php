@extends('generic::Front.layouts.master')

@section('content')
    <style>
        .benefits img {
            width: 70%;
        }

        .benefits p {
            text-align: justify;
            line-height: 30px;
            font-size: 16px;
        }

        .benefits .wrap {
            margin-top: 25%
        }
    </style>

    <section class="benefits">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center" >
                <img style="width: 100%" src="{{asset('front/img/broker_full.png')}}">
                </div>
            </div>
        </div>
    </section>
    

    {{--<section class="benefits">--}}
        {{--<div class="container">--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-6">--}}
                    {{--<div class="wrap">--}}
                        {{--<p>بتسوق عقارات لمنطقة معينة ومش عارف تسوق لبقية المناطق عشان معندكش وحدات كفاية ؟؟!</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-6">--}}
                    {{--<div>--}}
                        {{--<img src="{{asset('front/img/1.png')}}" alt="">--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-6 col-md-push-6">--}}
                    {{--<div class="wrap">--}}
                        {{--<p>ياكون من اكتر شركات سوق العقار نمو في الفترة الحالية. ياكون قدرت تكسب ثقة عملاء كتير في فترة وجيزة جدا وقدرت تجمع قاعدة بيانات متنوعة في جميع انحاء القاهرة في خلال 3 شهور فقط واللي متوقع انها تكسر ال 20 الف وحدة في اخر السنة.</p>--}}
                    {{--</div>--}}

                {{--</div>--}}
                {{--<div class="col-md-6 col-md-pull-6">--}}
                    {{--<div>--}}
                        {{--<img src="{{asset('front/img/2.png')}}" alt="">--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-6">--}}
                    {{--<div class="wrap">--}}
                        {{--<p>مع ياكون حتوسع مجال شغلك وتسوق لجميع مناطق القاهرة. حتقدر تتعامل مع ياكون مباشر وتسوق للوحدات المعروضة للبيع على ياكون،,--}}
                            {{--<b>، ومع زيادة حجم مبيعاتك على ياكون حتقدر كمان توصل انك تاخد full commission !!</b></p>--}}

                    {{--</div>--}}
                    {{--<h3>full commission!!</h3>--}}

                {{--</div>--}}
                {{--<div class="col-md-6">--}}
                    {{--<div>--}}
                        {{--<img src="{{asset('front/img/3.png')}}" alt="">--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-md-6 col-md-push-6">--}}
                    {{--<div class="wrap">--}}
                        {{--<p>بنساعدك بموقع ممتاز وسهل في التعامل عليه آلالف الوحدات اللي ممكن تحقق بيها مبيعات مع عملاءك. تقدر توصل من خلال الموقع للوحدات المطلوبة في ثواني من خلال آلية بحث وفيلتر محددين جدا </p>--}}

                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-6 col-md-pull-6">--}}
                    {{--<div>--}}
                        {{--<img src="{{asset('front/img/4.png')}}" alt="">--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-6">--}}
                    {{--<div class="wrap">--}}
                        {{--<p>مش بس كدة، الموقع مدعم ب live chat  على مدار اليوم مع فريق دعم حيزودك بكل المعلومات عن الوحدات المطلوبة عشان تقدر تعرضها على العملاء بتوعك وتبيع في اسرع وقت.</p>--}}

                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-6">--}}
                    {{--<div>--}}
                        {{--<img src="{{asset('front/img/5.png')}}" alt="">--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-6 col-md-push-6">--}}
                    {{--<div class="wrap">--}}
                        {{--<p style="font-weight: bold;"><b>سجل على ياكون دلوقتي وسرع مبيعاتك واستفيد من كل الخدمات اللي بنقدمها عشان تحقق أرقام قياسية</b></p>--}}

                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-6 col-md-pull-6">--}}
                    {{--<div>--}}
                        {{--<img src="{{asset('front/img/6.png')}}" alt="">--}}
                    {{--</div>--}}
                {{--</div>--}}


            {{--</div>--}}
        {{--</div>--}}
    {{--</section>--}}






    <section class="sign_in_section" style="padding-top: 40px;">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-12 col-xs12">
                    <div class="login_sign_up_widget">
                        {{--<h2 style="padding-top: 0px;">اعلان الفيسبوك :</h2>--}}
                        {{--<div style="font-weight: bold;">--}}
                            {{--سوق لآلاف الوحدات المعروضة للبيع على ياكون وخد full commission !!--}}
                            {{--<br/>                            ادخل على اللينك دلوقتي وسجل نفسك بروكر مع ياكون..--}}
                        {{--</div>--}}

                        {{--<h2 style="font-size: 20px !important;">التفاصيل على ركن "انت بروكر؟؟"</h2>--}}

                        {{--<div>--}}
                            {{--ياكون من اكتر شركات سوق العقار نمو في الفترة الحالية، بقاعدة بيانات تخطت ال 4000 وحدة في جميع انحاء القاهرة في خلال 3 شهور فقط.--}}
                            {{--حتقدر تتعامل مع ياكون مباشر وتسوق للوحدات المعروضة للبيع على ياكون، ومع زيادة حجم مبيعاتك على ياكون حتقدر توصل انك تاخد full commission.--}}
                        {{--<br/><br/>--}}
                            {{--بنساعدك بموقع ممتاز وسهل في التعامل، تقدر توصل منه للوحدات المطلوبة في ثواني من خلال آلية بحث محددة جدا.--}}
                            {{--<br/><br/>--}}
                            {{--مش بس كدة، الموقع مدعم ب live chat  على مدار اليوم مع فريق دعم حيزودك بكل المعلومات عن الوحدات المطلوبة عشان تقدر تعرضها على العميل بتاعك وتبيع في اسرع وقت.--}}
                            {{--<br/><br/>--}}
                            {{--سجل على ياكون دلوقتي وسرع مبيعاتك واستفيد من كل الخدمات اللي بنقدمها عشان تحقق أرقام قياسية--}}
                        {{--</div>--}}
                        <div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>تصنيف البروكر</th>
                                    <th>حجم المبيعات</th>
                                    <th>الكوميشن</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>جديد – Newbie Broker</td>
                                    <td>من صفر الى 5 مليون مبيعات</td>
                                    <td>2%</td>
                                </tr>
                                <tr>
                                    <td>عداء – Runner Broker</td>
                                    <td>فوق ال 5 مليون الى 20 مليون مبيعات</td>
                                    <td>2.5%</td>
                                </tr>
                                <tr>
                                    <td>محترف – Premium Broker</td>
                                    <td>فوق ال 20 مليون مبيعات</td>
                                    <td>3%</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-xs12 border_left">
                    <div class="login_sign_up_widget login_in_part">
                        {{--<h2>{{trans('global.login')}}</h2>--}}
                        <div class="">
                            <form method="POST" action="@if(auth()->check()) {{ route('brokerEdit') }} @else {{ route('broker') }} @endif" enctype="multipart/form-data" >
                                {{csrf_field()}}
                                <input type="hidden" name="broker" value="1"/>

                                @if(auth()->check() == false)
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name"
                                           placeholder="{{trans('global.name')}}">
                                    <span class="required_mark">*</span>
                                </div>
                                <div class="form-group">
                                    <input  class="form-control" type="email" name="email"
                                            placeholder="{{trans('global.email')}} ">
                                    <span class="required_mark">*</span>
                                </div>
                                <div class="form-group">
                                    <input  class="form-control" type="tel" name="phone" minlength="8"
                                            placeholder="{{trans('global.phone')}} "  pattern="[0-9]+"
                                    >
                                    <span class="required_mark">*</span>
                                </div>
                                @endif
                                    <div class="form-group">
                                    <input  class="form-control" type="text" name="national_id" minlength=4"
                                            placeholder="رقم البطاقة "  pattern="[0-9]+"
                                    >
                                    <span class="required_mark">*</span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlFile1">حمل صورة بطاقتك</label>
                                    <input type="file" name="national_image" style="display: block !important;" class="form-control-file" id="exampleFormControlFile1">
                                    <span class="required_mark">*</span>
                                </div>
                                {{--<div class="checkboxs">--}}
                                    {{--<div class="checkbox">--}}
                                        {{--<label>--}}
                                            {{--{{trans('global.sign_up_message')}} <a  data-toggle="modal" data-target="#myModal3" class="terms_popup_btn"><span>{{trans('global.terms')}}</span></a>--}}
                                        {{--</label>--}}
                                    {{--</div>--}}
                                    {{--<div class="checkbox">--}}
                                        {{--<label>--}}
                                            {{--<input type="hidden" name="newsletter_subscribe" value="0">--}}
                                            {{--<input type="checkbox" name="newsletter_subscribe"  value="1"> {{trans('global.newsletter_subscribe')}}--}}
                                        {{--</label>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="clearfix">
                                    <button type="submit" class="btn btn-default">
                                        {{trans('global.sign_up')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="row">--}}
                {{--<div class="col-xs-12">--}}
                    {{--<div class="or_use">--}}
                        {{--<p>--}}
                            {{--{{trans('global.or')}}--}}
                        {{--</p>--}}
                    {{--</div>--}}
                    {{--<div class="social_media">--}}
                        {{--<a class="facebook" href="{{route('socialLogin').'?provider=facebook'}}"><i class="fa fa-facebook" aria-hidden="true"></i></a>--}}
                        {{--<a class="twitter" href="{{route('socialLogin').'?provider=twitter'}}"><i class="fa fa-twitter" aria-hidden="true"></i></a>--}}
                        {{--<a class="google" href="{{route('socialLogin').'?provider=google'}}"><i class="fa fa-google-plus" aria-hidden="true"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </section>
    <div id="myModal3" class="modal fade delete_popup" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    {!! $mainSettings->terms !!}
                    {{--<p>{{$mainSettings->terms}}</p>--}}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@stop