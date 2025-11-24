@extends('generic::Front.layouts.master')
@section('title'){{ $title }} | @endsection
@section('style')
    <style>
        #seemoreCategories, #seemoreServices {
            display: none;
        }

        .hero_in.hotels:before {
            background: url({{asset('resources/assets/front/img/bg/gyms.jpg')}}) center center no-repeat;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
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
                    <h1 class="fadeInUp"><span></span>{{$title}} </h1>
                </div>
            </div>
        </section>
        <!--/hero_in-->

        <div class="filters_listing ">
            <div class="container">
                <ul class="clearfix">
                    <li>
                        <div class="switch-field">
                            <a href="{{route('gyms')}}" id="all" name="listing_filter" data-filter="*"
                               class="@if(@!request()->get('order')) selected @endif">
                                <label for="all">{{trans('global.latest')}}</label></a>
                            <a href="{{route('gyms')}}?order=oldest" id="all" name="listing_filter" data-filter="oldest"
                               class="@if(@request()->get('order') == 'oldest') selected @endif">
                                <label for="all">{{trans('global.oldest')}}</label></a>

                            <a href="{{route('gyms')}}?order=alphabet" id="all" name="listing_filter"
                               data-filter=".latest"
                               class="@if(@request()->get('order') == 'alphabet') selected @endif ">
                                <label for="all">{{trans('global.alphabet')}}</label></a>
                        </div>
                    </li>
                    {{--                                        <li>--}}
                    {{--                                            <div class="layout_view">--}}
                    {{--                                                <a href="#0" class="active"><i class="icon-th"></i></a>--}}
                    {{--                                                <a href="hotels-list-sidebar.html"><i class="icon-th-list"></i></a>--}}
                    {{--                                            </div>--}}
                    {{--                                        </li>--}}
                    <li>
                        <a class="btn_map" data-toggle="collapse" href="#collapseMap" aria-expanded="false"
                           aria-controls="collapseMap" data-text-swap="{{trans('global.hide_map')}}"
                           data-text-original="{{trans('global.view_map')}}">{{trans('global.view_map')}}</a>
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
                                                <label onclick="search_form({{$category->id}})"
                                                       id="label_category_{{$category->id}}">
                                                    <input type="checkbox" value="{{$category['id']}}"
                                                           class="icheck input_categories"
                                                           id="input_category_{{$category->id}}"
                                                           @if(in_array($category['id'], $checked_categories)) checked @endif>{{@$category['name']}}
                                                </label>
                                            </li>
                                    @endforeach
                                        </span>
                                </ul>
                            </div>
                            <div><a onclick="seeMoreCategories();" id="mySeeMoreBtnCategories"
                                    style="float: left; cursor: pointer;color: red;">{{trans('global.more')}} +</a>
                            </div>

                            <div class="filter_type" style="clear: both;">
                                <h6>{{trans('admin.gym_services')}}</h6>
                                <ul>
                                    @foreach($services as $key => $service)
                                        @if($key == 3) <span id="seemoreServices"> @endif
                                            <li>
                                                <label onclick="search_form({{$service->id}})"
                                                       id="label_service_{{$service->id}}">
                                                    <input type="checkbox" value="{{$service['id']}}"
                                                           class="icheck input_services"
                                                           id="input_service_{{$service->id}}"
                                                           @if(in_array($service['id'], $checked_services)) checked @endif>{{@$service['name']}}
                                                </label>
                                            </li>
                                    @endforeach
                                        </span>
                                </ul>
                            </div>
                            <div><a onclick="seeMoreServices();" id="mySeeMoreBtnServices"
                                    style="float: left; cursor: pointer;color: red;">{{trans('global.more')}} +</a>
                            </div>


                            <div class="filter_type" style="clear: both;">
                                <h6>{{trans('global.city')}}</h6>
                                <div>
                                    <select class="form-control wide" onchange="search_form();" name="city_id"
                                            id="city_id"
                                            style="width: 100%;height: 45px;">
                                        <option value="">{{trans('global.all_city')}}</option>
                                        @foreach($cities as $city)
                                            <option value="{{$city['id']}}"
                                                    @if($city['id'] == @request('city')) selected="" @endif
                                            >{{$city['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="filter_type" style="clear: both;">
                                <h6>{{trans('global.district')}}</h6>
                                <div>
                                    <select class="form-control wide" onchange="search_form();" name="district_id"
                                            id="district_id"
                                            style="width: 100%;height: 45px;">
                                        <option value="">{{trans('global.all_area')}}</option>
                                        @foreach($districts as $district)
                                            <option value="{{$district['id']}}"
                                                    class="districts_of_city_{{$district['city_id']}}"
                                                    @if($district['id'] == @@request('district')) selected=""@endif
                                            >{{$district['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="filter_type" style="clear:both;">
                                <h6>{{trans('global.keyword')}}</h6>
                                <input type="text" placeholder="{{trans('global.keyword')}}" name="keyword"
                                       onkeyup="keyword_form();"
                                       style="height: 40px;width: 100%" value="{{@request('keyword')}}"
                                       id="keyword">
                            </div>
                            {{--                            <div class="filter_type">--}}
                            {{--                                <h6>Star Category </h6>--}}
                            {{--                                <ul>--}}
                            {{--                                    <li>--}}
                            {{--                                        <label>--}}
                            {{--                                            <input type="checkbox" class="icheck"><span class="cat_star"><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i></span> <small>(25)</small>--}}
                            {{--                                        </label>--}}
                            {{--                                    </li>--}}
                            {{--                                    <li>--}}
                            {{--                                        <label>--}}
                            {{--                                            <input type="checkbox" class="icheck"><span class="cat_star"><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i></span> <small>(26)</small>--}}
                            {{--                                        </label>--}}
                            {{--                                    </li>--}}
                            {{--                                    <li>--}}
                            {{--                                        <label>--}}
                            {{--                                            <input type="checkbox" class="icheck"><span class="cat_star"><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i></span> <small>(25)</small>--}}
                            {{--                                        </label>--}}
                            {{--                                    </li>--}}
                            {{--                                </ul>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="filter_type">--}}
                            {{--                                <h6>Rating</h6>--}}
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
                        <div style="display: none" id="noMore">0</div>
                        <div class="row" id="load-data">
                            @foreach($records as $key =>$record)
                                <div class="col-md-4 isotope-item @if(($key+1) % 2 == 0) latest @else popular @endif">
                                    <div class="box_grid">
                                        <figure>
                                            <a href="#sign-in-dialog"
                                               id="favorite_1_{{$record->id}}" onclick="
                                            @if(@$currentUser && $currentUser->gym_favorites &&  @in_array($record->id, $currentUser->gym_favorites->pluck('gym_id')->toArray())) removeFavorite('{{$record->id}}', 1); return false;
                                            @else addFavorite('{{$record->id}}', 1); return false;  @endif
                                                    "
                                               class="
                                                    @if(!@$currentUser) login sign-in-form @endif wish_bt
                                                    @if( @$currentUser->gym_favorites && @in_array($record->id, $currentUser->gym_favorites->pluck('gym_id')->toArray())) liked @endif
                                                       "
                                            ></a>
                                            <a href="{{route('gym', [$record->id, $record->slug])}}">
                                                <img src="{{$record->image_thumbnail}}" class="img-fluid" alt="{{$record->name}}"
                                                     width="800" height="533">
                                                <div class="read_more"><span>{{trans('global.details')}}</span></div>
                                            </a>
                                            <small>{{@$record->categories[0]->name}}</small>
                                        </figure>
                                        <div class="wrapper">
{{--                                            <div class="cat_star">--}}
{{--                                                <i class="icon_star"></i>--}}
{{--                                                <i class="icon_star"></i>--}}
{{--                                                <i class="icon_star"></i>--}}
{{--                                                <i class="icon_star"></i>--}}
{{--                                            </div>--}}
                                            <h3>
                                                <a href="{{route('gym', [$record->id, $record->slug])}}">{{$record->name}}</a>
                                            </h3>
                                            <span class="price">{{@$record->district->name}}, {{@$record->district->city->name}}</span>
                                        </div>
                                        <ul>
                                            <li><i class="ti-eye"></i> {{$record->views}} {{trans('global.views')}}</li>
                                            <li>
                                                <div class="score">
                                                    <span>{{trans('global.articles')}}</span><strong>{{(int)$record->articles}}</strong>
                                                </div>
                                                {{--                                            <div class="score"><span>Superb<em>350 Reviews</em></span><strong>8.9</strong></div>--}}
                                            </li>
                                        </ul>
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
                        <a href="#0" onclick="loadData();return false;"
                           class="btn-more btn_1 rounded add_top_30">{{trans('global.load_more')}}</a>
                    </p>
                </div>
                <!-- /col -->
            </div>
        </div>
        <!-- /container -->

    {{--        <div class="bg_color_1">--}}
    {{--            <div class="container margin_60_35">--}}
    {{--                <div class="row">--}}
    {{--                    <div class="col-md-4">--}}
    {{--                        <a href="#0" class="boxed_list">--}}
    {{--                            <i class="pe-7s-help2"></i>--}}
    {{--                            <h4>Need Help? Contact us</h4>--}}
    {{--                            <p>Cum appareat maiestatis interpretaris et, et sit.</p>--}}
    {{--                        </a>--}}
    {{--                    </div>--}}
    {{--                    <div class="col-md-4">--}}
    {{--                        <a href="#0" class="boxed_list">--}}
    {{--                            <i class="pe-7s-wallet"></i>--}}
    {{--                            <h4>Payments</h4>--}}
    {{--                            <p>Qui ea nemore eruditi, magna prima possit eu mei.</p>--}}
    {{--                        </a>--}}
    {{--                    </div>--}}
    {{--                    <div class="col-md-4">--}}
    {{--                        <a href="#0" class="boxed_list">--}}
    {{--                            <i class="pe-7s-note2"></i>--}}
    {{--                            <h4>Cancel Policy</h4>--}}
    {{--                            <p>Hinc vituperata sed ut, pro laudem nonumes ex.</p>--}}
    {{--                        </a>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <!-- /row -->--}}
    {{--            </div>--}}
    {{--            <!-- /container -->--}}
    {{--        </div>--}}
    <!-- /bg_color_1 -->

    </main>
    <!--/main-->

@endsection

@section('script')
    <script>

        $(window).scroll(function () {
            // alert($(window).scrollTop()+ ' - ' + ($(document).height() - $(window).height()));
            if ((($(window).scrollTop() + 10) >= $(document).height() - $(window).height())) {
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

        $('input').on('ifChanged', function (event) {
            search_form();
        });


        function seeMoreServices() {
            var moreText = document.getElementById("seemoreServices");
            var btnText = document.getElementById("mySeeMoreBtnServices");

            if (btnText.innerHTML === "{{trans('global.disable_more')}} -") {
                btnText.innerHTML = "{{trans('global.more')}} +";
                moreText.style.display = "none";
            } else {
                btnText.innerHTML = "{{trans('global.disable_more')}} -";
                moreText.style.display = "inline";
            }
        }


        function search_form() {
            // alert('search');
            document.getElementById("load-data").innerHTML = "";
            $(".pager").val("0");
            loadData();
        }

        function keyword_form() {
            // alert('keyword');
            var keyword = document.getElementById("keyword").value.length;
            if (keyword > 4 || (keyword == 0))
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
            // alert('ss');
            pager = $('.pager:last').val();
            var city_id = $('#city_id').val();
            var district_id = $('#district_id').val();
            var keyword = $('#keyword').val();
            var order = '{{@request()->get('order')}}';
            var categories = [];
            var checkboxesCategories = $('.input_categories:checkbox:checked');

            for (var i = 0; i < checkboxesCategories.length; i++) {
                categories.push(checkboxesCategories[i].value)
            }
            var services = [];
            var checkboxesServices = $('.input_services:checkbox:checked');

            for (var i = 0; i < checkboxesServices.length; i++) {
                services.push(checkboxesServices[i].value)
            }

            $(".btn-more").html("{{trans('global.loading')}}....");
            $.ajax({
                url: '{{route('searchByAjax')}}',
                method: "POST",
                data: {
                    pager: pager,
                    categories: categories,
                    services: services,
                    city_id: city_id,
                    district_id: district_id,
                    keyword: keyword,
                    order: order,
                    _token: "{{csrf_token()}}"
                },
                dataType: "text",
                success: function (data) {
                    if (data != '') {
                        $("#noMore").html('0');
                        $(".btn-more").html("{{trans('global.load_more')}}");

                        // $('#pager').remove();
                        $('#load-data').append(data);
                        load_new_posts();
                        // $('body,html').animate({
                        //     scrollTop: ($(".aa").last().position().top - 900)
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

        function repostionTop() {
            $('body,html').animate({
                scrollTop: ($(".aa").last().position().top)
            }, 500);
            return true;
        }
    </script>

    <!-- Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUtpFU1OSQwyfjIdsUdKgzRAdedm5Atmg"></script>
    <script src="{{asset('resources/assets/front/js/markerclusterer.js')}}"></script>
    <script>

        var lat = '30.047918765189475';//data[0].locations[0].lat;
        var lng = '31.233673213500005';//data[0].locations[0].lng;

        $.ajax(
            {
                type: 'POST',
                url: "{{route('gymsByJson')}}",
                dataType: "json",
                data: {
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    var items = [];
                    var i = 0;
                    $.each( data, function( key, gym ) {

                        // $.each(gym.locations, function (key2, location){
                        url  = '{{route('gym', [':id', '/'])}}/' + gym.slug;
                        url = url.replace(":id", gym.id);
                        dataa = {
                                'name':gym.slug.replace("-", " ").toUpperCase(),
                                'type_point':'Paris Centre',
                                'location_latitude':gym.lat,
                                'location_longitude':gym.lng,
                                'map_image_url':'url('+ gym.image_thumbnail +')',
                                'rate':'Superb | 7.5',
                                'name_point':'Park Hyatt Paris',
                                // 'phone':gym.main_phone,
                                'url_point': url,
                            };
                            i++;
                            items.push(dataa);

                        // });
                    });

                    var markersData = {
                        'Marker': items
                    };


                    $('#collapseMap').on('shown.bs.collapse', function(e){
                        (function(A) {

                            if (!Array.prototype.forEach)
                                A.forEach = A.forEach || function(action, that) {
                                    for (var i = 0, l = this.length; i < l; i++)
                                        if (i in this)
                                            action.call(that, this[i], i, this);
                                };

                        })(Array.prototype);
                        var
                            mapObject,
                            markers = [];
                        var mapOptions = {
                            zoom: 12,
                            center: new google.maps.LatLng(lat, lng),
                            mapTypeId: google.maps.MapTypeId.ROADMAP,

                            mapTypeControl: false,
                            mapTypeControlOptions: {
                                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
                                position: google.maps.ControlPosition.LEFT_CENTER
                            },
                            panControl: false,
                            panControlOptions: {
                                position: google.maps.ControlPosition.TOP_RIGHT
                            },
                            zoomControl: true,
                            zoomControlOptions: {
                                style: google.maps.ZoomControlStyle.LARGE,
                                position: google.maps.ControlPosition.TOP_LEFT
                            },
                            scrollwheel: false,
                            scaleControl: false,
                            scaleControlOptions: {
                                position: google.maps.ControlPosition.TOP_LEFT
                            },
                            streetViewControl: true,
                            streetViewControlOptions: {
                                position: google.maps.ControlPosition.LEFT_TOP
                            },
                            styles: [
                                {
                                    "featureType": "administrative.country",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "administrative.province",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "administrative.locality",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "administrative.neighborhood",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "administrative.land_parcel",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "landscape.man_made",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "simplified"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "landscape.natural.landcover",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "on"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "landscape.natural.terrain",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "poi.attraction",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "poi.business",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "poi.government",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "poi.medical",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "poi.park",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "on"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "poi.park",
                                    "elementType": "labels",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "poi.place_of_worship",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "poi.school",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "poi.sports_complex",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "road.highway",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "road.highway",
                                    "elementType": "labels",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "road.highway.controlled_access",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "road.arterial",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "simplified"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "road.local",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "simplified"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "transit.line",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "transit.station.airport",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "transit.station.bus",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "transit.station.rail",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "water",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "on"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "water",
                                    "elementType": "labels",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                }
                            ]
                        };
                        var
                            marker;
                        mapObject = new google.maps.Map(document.getElementById('map'), mapOptions);
                        for (var key in markersData)
                            markersData[key].forEach(function (item) {
                                marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(item.location_latitude, item.location_longitude),
                                    map: mapObject,
                                    icon: '{{asset('resources/assets/front/img/pins/')}}/' + key + '.png',
                                });

                                if ('undefined' === typeof markers[key])
                                    markers[key] = [];
                                markers[key].push(marker);
                                google.maps.event.addListener(marker, 'click', (function () {
                                    closeInfoBox();
                                    getInfoBox(item).open(mapObject, this);
                                    mapObject.setCenter(new google.maps.LatLng(item.location_latitude, item.location_longitude));
                                }));

                            });

                        new MarkerClusterer(mapObject, markers[key]);

                        function hideAllMarkers () {
                            for (var key in markers)
                                markers[key].forEach(function (marker) {
                                    marker.setMap(null);
                                });
                        };

                        function closeInfoBox() {
                            $('div.infoBox').remove();
                        };

                        function getInfoBox(item) {
                            console.log('itml', item);
                            return new InfoBox({
                                content:
                                    '<div class="marker_info" id="marker_info">' +
                                    '<a href="'+item.url_point+'" >'+ '<div style="background-image: ' + item.map_image_url + ';object-fit: contain;width: 100%;height: 130px;" /></div></a>' +
                                    '<span>'+
                                    // '<span class="infobox_rate">'+ item.rate +'</span>' +
                                    '<h3><a href="'+item.url_point+'" >'+ item.name +'</a></h3>' +
                                    // '<em>'+ item.type_point +'</em>' +
                                    // '<strong>'+ item.description_point +'</strong>' +
                                    '<a href="'+ item.url_point + '" class="btn_infobox_detail"></a>' +
                                    '<form action="https://maps.google.com/maps" method="get" target="_blank"><input name="saddr" value="'+ item.get_directions_start_address +'" type="hidden"><input type="hidden" name="daddr" value="'+ item.location_latitude +',' +item.location_longitude +'"><button type="submit" value="Get directions" class="btn_infobox_get_directions">Get directions</button></form>' +
                                    // '<a href="tel://'+ item.phone +'" class="btn_infobox_phone">'+ item.phone +'</a>' +
                                    '</span>' +
                                    '</div>',
                                disableAutoPan: false,
                                maxWidth: 0,
                                pixelOffset: new google.maps.Size(10, 92),
                                closeBoxMargin: '',
                                closeBoxURL: "{{asset('resources/assets/front/img/close_infobox.png')}}",
                                isHidden: false,
                                alignBottom: true,
                                pane: 'floatPane',
                                enableEventPropagation: true
                            });


                        };

                    });

                }
            });


    </script>
{{--    <script src="{{asset('resources/assets/front/js/map_hotels.js')}}"></script>--}}
    <script src="{{asset('resources/assets/front/js/infobox.js')}}"></script>

@endsection
