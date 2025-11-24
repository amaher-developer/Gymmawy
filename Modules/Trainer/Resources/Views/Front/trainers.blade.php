@extends('generic::Front.layouts.master')
@section('title'){{ $title }} | @endsection
@section('style')
    <style>
        .img-fluid {
            height: 100%;
            object-fit: cover;
        }

        ul.share-buttons li a {
            padding: 7px 7px;
        }

        ul.share-buttons li i {
            right: 0;
        }

        .box_grid ul li {
            margin-right: 10px;
        }

        .hero_in.hotels:before {
            background: url({{asset('resources/assets/front/img/bg/trainers.jpg')}}) center center no-repeat;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        #seemoreCategories, #seemoreServices {
            display: none;
        }

        .switch-field a, label {
            @if($lang == 'ar')
                border-left: 1px solid rgba(0, 0, 0, 0.08) !important;
            @else
                border-right: 1px solid rgba(0, 0, 0, 0.08) !important;
            @endif


        }

        .selected label {
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            color: rgba(0, 0, 0, 0.9);
        }
    </style>

@endsection

@section('content')


    <main>

        <section class="hero_in hotels">
            <div class="wrapper">
                <div class="container">
                    <h1 class="fadeInUp"><span></span>{{$title}}</h1>
                </div>
            </div>
        </section>
        <!--/hero_in-->

        <div class="filters_listing
{{--        sticky_horizontal--}}
">
            <div class="container">
                <ul class="clearfix">
                    <li>
                        <div class="switch-field">
                            <a href="{{route('trainers')}}" id="all" name="listing_filter" data-filter="*"
                               class="@if(@!request()->get('order')) selected @endif">
                                <label for="all">{{trans('global.latest')}}</label></a>
                            <a href="{{route('trainers')}}?order=oldest" id="all" name="listing_filter" data-filter="oldest"
                               class="@if(@request()->get('order') == 'oldest') selected @endif">
                                <label for="all">{{trans('global.oldest')}}</label></a>

                            <a href="{{route('trainers')}}?order=alphabet" id="all" name="listing_filter"
                               data-filter=".latest"
                               class="@if(@request()->get('order') == 'alphabet') selected @endif ">
                                <label for="all">{{trans('global.alphabet')}}</label></a>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- /container -->
        </div>
        <!-- /filters -->

        <div class="collapse" id="collapseMap">
            <div id="map" class="map"></div>
        </div>
        <!-- End Map -->

        <div class="container margin_60_35">
            <div class="row">
                <aside class="col-lg-3" id="sidebar">
                    <div id="filters_col">
                        <a data-toggle="collapse" href="#collapseFilters" aria-expanded="false"
                           aria-controls="collapseFilters" id="filters_col_bt">{{trans('global.filters')}} </a>
                        <div class="collapse show" id="collapseFilters">

                            <div class="filter_type">
                                <h6>{{trans('admin.categories')}}</h6>
                                <ul>
                                    @foreach($categories as $key => $category)
                                        @if($key == 3) <span id="seemoreCategories"> @endif
                                            <li>
                                                <label onclick="search_form({{$category->id}})" id="label_category_{{$category->id}}">
                                                    <input type="checkbox"  value="{{$category['id']}}" class="icheck input_categories" id="input_category_{{$category->id}}"
                                                           @if(in_array($category['id'], $checked_categories)) checked @endif>{{@$category['name']}}
                                                </label>
                                            </li>
                                    @endforeach
                                        </span>
                                </ul>
                            </div>
                            <div><a onclick="seeMoreCategories();" id="mySeeMoreBtnCategories"
                                    style="@if($lang == 'ar') float: left; @else float: right; @endif cursor: pointer;color: red;">{{trans('global.more')}}
                                    +</a>
                            </div>

{{--                            <div class="filter_type" style="clear: both;">--}}
{{--                                <h6>{{trans('global.city')}}</h6>--}}
{{--                                <div>--}}
{{--                                    <select class="form-control wide" onchange="search_form();" name="city_id" id="city_id"--}}
{{--                                            style="width: 100%;height: 45px;">--}}
{{--                                        <option value="">{{trans('global.all_city')}}</option>--}}
{{--                                        @foreach($cities as $city)--}}
{{--                                            <option value="{{$city['id']}}"--}}
{{--                                                    @if($city['id'] == @request('city')) selected="" @endif--}}
{{--                                            >{{$city['name']}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="filter_type" style="clear: both;">--}}
{{--                                <h6>{{trans('global.district')}}</h6>--}}
{{--                                <div>--}}
{{--                                    <select class="form-control wide" onchange="search_form();" name="district_id" id="district_id"--}}
{{--                                            style="width: 100%;height: 45px;">--}}
{{--                                        <option value="">{{trans('global.all_area')}}</option>--}}
{{--                                        @foreach($districts as $district)--}}
{{--                                            <option value="{{$district['id']}}"--}}
{{--                                                    class="districts_of_city_{{$district['city_id']}}"--}}
{{--                                                    @if($district['id'] == @@request('district')) selected=""@endif--}}
{{--                                            >{{$district['name']}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="filter_type" style="clear:both;">
                                <h6>{{trans('global.keyword')}}</h6>
                                <input type="text" placeholder="{{trans('global.keyword')}}" name="keyword" onkeyup="keyword_form();"
                                       style="height: 40px;width: 100%" value="{{@request('keyword')}}"
                                       id="keyword">
                            </div>
                            {{--                            <div class="filter_type">--}}
                            {{--                                <h6>{{trans('global.services')}}</h6>--}}
                            {{--                                <ul>--}}
                            {{--                                    <li>--}}
                            {{--                                        <label>--}}
                            {{--                                            <input type="checkbox" class="icheck">Superb 9+ <small>(25)</small>--}}
                            {{--                                        </label>--}}
                            {{--                                    </li>--}}
                            {{--                                    <li>--}}
                            {{--                                        <label>--}}
                            {{--                                            <input type="checkbox" class="icheck">Very Good 8+ <small>(26)</small>--}}
                            {{--                                        </label>--}}
                            {{--                                    </li>--}}
                            {{--                                    <li>--}}
                            {{--                                        <label>--}}
                            {{--                                            <input type="checkbox" class="icheck">Good 7+ <small>(25)</small>--}}
                            {{--                                        </label>--}}
                            {{--                                    </li>--}}
                            {{--                                    <li>--}}
                            {{--                                        <label>--}}
                            {{--                                            <input type="checkbox" class="icheck">Pleasant 6+ <small>(12)</small>--}}
                            {{--                                        </label>--}}
                            {{--                                    </li>--}}
                            {{--                                </ul>--}}
                            {{--                            </div>--}}
                        </div>
                        <!--/collapse -->
                    </div>
                    <!--/filters col-->
                </aside>
                <!-- /aside -->

                <div class="col-lg-9">
                    <div class="isotope-wrapper">
                        <div class="row" id="load-data">
                            <div style="display: none" id="noMore">0</div>

                        @foreach($trainers as $key => $trainer)
                                <div class="col-md-4 isotope-item @if(($key+1) % 2 == 0) latest @else popular @endif">
                                    <div class="box_grid">
                                        <figure>
                                            <a href="#sign-in-dialog"
                                               id="favorite_2_{{$trainer->id}}" onclick="
                                            @if(@$currentUser && $currentUser->trainer_favorites && @in_array($trainer->id, @$currentUser->trainer_favorites->pluck('trainer_id')->toArray())) removeFavorite('{{$trainer->id}}', 2); return false;
                                            @else addFavorite('{{$trainer->id}}', 2); return false;  @endif
                                                    "
                                               class="
                                                    @if(!@$currentUser) login sign-in-form @endif wish_bt
                                                    @if(@$currentUser->trainer_favorites && @in_array($trainer->id, @(array)$currentUser->trainer_favorites->pluck('trainer_id')->toArray())) liked @endif
                                                       "
                                            ></a>
                                            <a href="{{route('trainer', [$trainer->id, $trainer->slug])}}">
                                                <img src="{{$trainer->image_thumbnail}}" class="img-fluid" alt="{{$trainer->name}}"
                                                     width="800" height="533">
                                                <div class="read_more"><span>{{trans('global.details')}}</span></div>
                                            </a>
                                            <small>{{$trainer->gym_name}}</small>
                                        </figure>
                                        <div class="wrapper">
                                            <h3>
                                                <a href="{{route('trainer', [$trainer->id, $trainer->slug])}}">{{$trainer->name}}</a>
                                            </h3>
{{--                                            <span class="price">{{@$trainer->city->name}}</span><br/>--}}
{{--                                            <span class="price">{{trans('global.gender')}}: {{@$trainer->gender_name}}</span>--}}
                                        </div>
                                        <ul>
                                            <li><i class="icon-location"></i>  {{@$trainer->city->name}}</li>
                                            <li><i class="icon-user"></i>  {{@$trainer->gender_name}}</li>
                                        </ul>
                                        {{--                                        <ul class="share-buttons">--}}
                                        {{--                                            @if($trainer->facebook)--}}
                                        {{--                                                <li><a class="phone-share" href="{{$trainer->facebook}}" target="_blank"--}}
                                        {{--                                                       title=""><i class="icon_phone"--}}
                                        {{--                                                                   aria-hidden="true"></i></a></li>--}}
                                        {{--                                            @endif--}}
                                        {{--                                            @if($trainer->facebook)--}}
                                        {{--                                                <li><a class="fb-share" href="{{$trainer->facebook}}" target="_blank"--}}
                                        {{--                                                       title="facebook"><i class="social_facebook"--}}
                                        {{--                                                                           aria-hidden="true"></i></a></li>--}}
                                        {{--                                            @endif--}}
                                        {{--                                            @if($trainer->twitter)--}}
                                        {{--                                                <li><a class="twitter-share" href="{{$trainer->twitter}}" target="_blank"--}}
                                        {{--                                                       title="twitter"><i class="social_twitter" aria-hidden="true"></i></a>--}}
                                        {{--                                                </li>--}}
                                        {{--                                            @endif--}}
                                        {{--                                            @if($trainer->instagram)--}}
                                        {{--                                                <li><a class="instagram-share" href="{{$trainer->instagram}}" target="_blank"--}}
                                        {{--                                                       title="instagram"><i class="social_instagram"--}}
                                        {{--                                                                            aria-hidden="true"></i></a></li>--}}
                                        {{--                                            @endif--}}
                                        {{--                                            @if($trainer->linkedin)--}}
                                        {{--                                                <li><a class="linkedin-share" href="{{$trainer->linkedin}}" target="_blank"--}}
                                        {{--                                                       title="linkedin"><i class="social_linkedin"--}}
                                        {{--                                                                           aria-hidden="true"></i></a></li>--}}
                                        {{--                                            @endif--}}
                                        {{--                                        </ul>--}}
                                    </div>
                                </div>
                                <!-- /box_grid -->
                            @endforeach

                            <input type="hidden" name="pager" id="pager" class="pager" data-page="0" value="0">


                        </div>
                        <!-- /row -->
                    </div>
                    <!-- /isotope-wrapper -->

                    <p class="text-center" id="remove-row">
                        <a href="#0"  onclick="loadData();return false;" class="btn-more btn_1 rounded add_top_30">{{trans('global.load_more')}}</a>
                    </p>
                </div>
                <!-- /col -->
            </div>
        </div>
        <!-- /container -->


    </main>
    <!--/main-->



@endsection

@section('script')
    <script>


        $(window).scroll(function () {
            // alert($(window).scrollTop()+ ' - ' + ($(document).height() - $(window).height()));

            if ($("#noMore").html() == 0 && (($(window).scrollTop() + 10) >= $(document).height() - $(window).height())) {
                onReachScrollLimit();
            }
        });
        var loading=false;
        function onReachScrollLimit(){
            if(loading){return;}
            loading=true;
            loadData();
        }
        function load_new_posts(){
            loading=false;
        }

        city_id = $("#city_id").val();
        district_id = $("#district_id").val();
        $("#district_id option").hide();
        $("#district_id option:first").show();
        $("#district_id option.districts_of_city_" + city_id).show();

        $("#city_id").change(function (e) {
            var city_id = $("#city_id").val();
            $("#district_id option").hide();
            $("#district_id option:selected").removeAttr('selected');
            $("#district_id option.districts_of_city_" + city_id).show();
            $("#district_id option:first").show();
            $("#district_id option:first").attr('selected', true);
        });
        $('input').on('ifChanged', function(event){
            search_form();
        });

        function search_form(){
            document.getElementById("load-data").innerHTML = "";
            $(".pager").val("0");
            loadData();
        }
        function keyword_form(){
            var keyword = document.getElementById("keyword").value.length;
            if(keyword > 4 || (keyword == 0))
                search_form();
        }

        function seeMoreCategories() {
            var moreText = document.getElementById("seemoreCategories");
            var btnText = document.getElementById("mySeeMoreBtnCategories");

            if (btnText.innerHTML === "{{trans('global.disable_more')}} -") {
                btnText.innerHTML = "{{trans('global.more')}} +";
                moreText.style.display = "none";
            } else {
                btnText.innerHTML = "{{trans('global.disable_more')}} -";
                moreText.style.display = "inline";
            }
        }

        function loadData() {
            pager = $('.pager:last').val();
            var city_id = $('#city_id').val();
            var district_id = $('#district_id').val();
            var keyword = $('#keyword').val();
            var order = '{{@request()->get('order')}}';

            var categories = [];
            var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');

            for (var i = 0; i < checkboxes.length; i++) {
                categories.push(checkboxes[i].value)
            }
            $(".btn-more").html("{{trans('global.loading')}}....");
            $.ajax({
                url: '{{route('trainersByAjax')}}',
                method: "POST",
                data: {
                    pager: pager,
                    categories: categories,
                    city_id: city_id,
                    district_id: district_id,
                    keyword: keyword,
                    order: order,
                    _token: "{{csrf_token()}}"},
                dataType: "text",
                success: function (data) {
                    if (data != '') {
                        // $('.pager').remove();
                        $('#load-data').append(data);
                        $(".btn-more").html("{{trans('global.load_more')}}");
                        load_new_posts();
                        // $('body,html').animate({
                        //     scrollTop: ($(".aa").last().position().top - 600)
                        // }, 500);

                    } else {
                        $("#noMore").html('1');
                        setTimeout(function () {
                        $("#load-data").append(
                            {{--'<img src="{{asset("resources/assets/front/img/no_result.png")}}"\n' +--}}
                            {{--'             style="width: 100%;height: 250px;object-fit: contain;">\n' +--}}
                            '        <div style="font-size: 16px;padding-top: 25px;">{{trans("global.not_found_msg")}}</div>\n' +
                            '        <span class="aa"></span>')
                        }, 2000);
                        // $(".btn-more").remove();
                        $('.btn-more').hide();
                        $('body,html').animate({
                            scrollTop: ($(".aa").last().position().top)
                        }, 500);

                    }
                }
            });
        }


    </script>
@endsection
