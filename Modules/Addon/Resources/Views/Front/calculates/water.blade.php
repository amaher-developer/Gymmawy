@extends('generic::Front.layouts.master')
@section('title'){{ $title }} | @endsection
@section('style')
    <style>
        .img-fluid {
            height: 100%;
            object-fit: cover;
        }

        .hero_in.general:before {
            background: url({{asset('resources/assets/front/img/bg/healty.jpg')}}) center center no-repeat;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        .m-checkbox{
            vertical-align: middle;
        }
        input[type="radio"] {
            -ms-transform: scale(1.5); /* IE 9 */
            -webkit-transform: scale(1.5); /* Chrome, Safari, Opera */
            transform: scale(1.5);
        }
        .label {
            font-weight: bolder;
        }
        .btn_1 {
            background-color: #32a067;
        }
        .m-select {
            border-color: #d2d8dd;
            outline: 0;
            box-shadow: none;
            display: block !important;
            font-weight: 500;
            height: 45px;
        }
        .add_top_20{
            padding-top: 20px !important;
        }
        .form-check-label{
            padding-right: 1.25rem;
            padding-left: inherit;
        }
        .form-check {
            padding-left: 0;
        }
        .m-unit{
            font-weight: normal;
            font-size: 10px;
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
                <aside class="col-lg-3" id="sidebar">
                    <div class="box_style_cat" id="faq_box">
                        <ul id="cat_nav">
                            @include('addon::Front.calculates.calculate_side_bar')
                        </ul>
                    </div>
                    <!--/sticky -->
                </aside>
                <!--/aside -->

                <div class="col-lg-9" id="faq">
{{--                    <h4 class="nomargin_top" style="padding-bottom: 20px">{{$title}}</h4>--}}

                    <div class="box_detail booking" style="background-color: white">
                        <div class="price">
                            <h5 class="d-inline">{{$title}}</h5>
{{--                                                        <div class="score"><span>Good<em>350 Reviews</em></span><strong>7.0</strong></div>--}}
                        </div>
                        <form id="submit_calculate_water_form"  autocomplete="off">

                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label class="label">{{trans('global.weight')}} <span class="m-unit">({{trans('global.kg')}})</span> <span class="required">*</span></label>
                                    <input type="number" value="60" name="water_weight" id="water_weight" class="form-control" required>
                                </div>
                                <div class="form-group col-md-12 add_top_20">
                                    <input type="submit" value="{{trans('global.calculate')}}" class=" btn_1 "
                                           id="submit_calculate_water">
                                </div>
                            </div>

                        </form>
                    </div>

                    <div id="water_result"></div>
                    <!-- /accordion payment -->

                </div>
                <!-- /col -->
            </div>
            <!-- /row -->
        </div>
        <!--/container-->
    </main>
    <!--/main-->

@endsection
@section('script')
    <script>
        $("#submit_calculate_water_form").submit(function(){
            water_weight = $('#water_weight').val();
                $.ajax({
                    url: "{{route('calculateWaterResult')}}",
                    type: 'POST',
                    data: {
                        water_weight: water_weight,
                        _token: "{{csrf_token()}}"
                    },
                    dataType: "text",
                    success: function (response) {
                        document.getElementById("water_result").innerHTML = response;

                    },
                    error: function (request, error) {

                        console.error("Request: " + JSON.stringify(request));
                        console.error("Error: " + JSON.stringify(error));
                    }
                });

            return false;
        });
    </script>
@endsection
