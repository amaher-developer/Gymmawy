@extends('generic::layouts.user_form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('showUserGymBrand') }}">{{trans('admin.gym_show')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            {{ $title }}
        </li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('styles')

    <link rel="stylesheet" type="text/css" href="https://unpkg.com/dropzone@5.7.0/dist/dropzone.css">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/cropperjs/dist/cropper.css">
    <link rel="stylesheet" type="text/css"
          href="{{asset('/')}}resources/assets/admin/global/css/file-uploaders/dropzone.min.css">
    <link rel="stylesheet" type="text/css"
          href="{{asset('/')}}resources/assets/admin/global/css/vendors/file-uploaders/dropzone.min.css">
    <link rel="stylesheet" type="text/css"
          href="{{asset('/')}}resources/assets/admin/global/css/cropper/cropper.min.css">

    <link rel="stylesheet" type="text/css" href="{{asset('/')}}resources/assets/admin/global/plugins/jquery-tags-input/jquery.tagsinput-rtl.css"/>

    <style>

        .district_city_li {
            display: none;
        }

        .text-right {
            text-align: right !important;
        }

        .maher-main-image-span {
            float: right;
            padding: 10px;
        }

        .maher-main-image-input {
            width: 8%;
            float: right;
        }

        #map-canvas {
            width: 100%;
            height: 400px;
        }

        #mapsearch {
            height: 30px;
            position: absolute;
            z-index: 999;
            margin: 15px 60px 0 0;
        }

        .bootstrap-tagsinput .tag {
            margin: 2px 0;
        }

        .rootfolder {
            background-position: 0 0;
        }

        .folder {
            background-position: 0 -16px;
        }

        .pdf {
            background-position: 0 -32px;
        }

        .html {
            background-position: 0 -48px;
        }

        .image {
            background-position: 0 -64px;
        }

        .trigger-btn {
            display: inline-block;
            margin: 100px auto;
        }

        .dropzone {
            min-height: 150px;
        }

        .dropzone .dz-default.dz-message:before {
            display: none;
        }

        .dropzone .dz-preview .dz-image img {
            display: block;
            object-fit: cover;
            width: 100%;
            height: 100px;
        }
    </style>
@endsection
@section('page_body')

    {{--    <a href="javascripts::void(0);" data-toggle="modal" data-target="#myModal"><img--}}
    {{--                src="http://localhost/azbase/public/assets/front-new/img/camera-icon.png" style="height: 160px;"></a>--}}
    <h5 class="form-section"><i class="fa fa-camera"></i> {{trans('admin.gym_images')}}</h5>


    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"></h4>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">
                        <form method="post"
                              action="{{route('uploadUserGymImages')}}"
                              class="dropzone" id="dpz-multiple-files" multiple=""
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="clearfix">
    </div>

    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}


            <input name="images" id="attached_files" value="" type="hidden"/>


            <h5 class="form-section"><i class="fa fa-camera"></i> {{trans('admin.gym_images')}}</h5>


            <div class="form-group col-md-12 col-sm-12">
                <label class="col-md-3 col-sm-6 control-label text-right">{{trans('admin.gym_main_image')}} <span
                            class="required">*</span></label>
                <div class="col-md-3 col-sm-6 ">
                    <input id="gym_image" value="{{ old('image', @$gym->image) }}"
                           name="image" type="file" class="form-control" required>
                    <br/>
                    <label for="gym_image" style="cursor: pointer;">
                        <img id="preview1" src="{{asset('resources/assets/front/img/preview_icon.png')}}"
                             style="height: 120px;width: 120px;object-fit: contain;border: 1px solid #c2cad8;"
                             alt="preview image"/>
                    </label>
                </div>

                <label class="col-md-3  col-sm-6  control-label text-right">{{trans('admin.gym_logo')}}</label>
                <div class="col-md-3  col-sm-6 ">
                    <input id="logo" value="{{ old('logo', @$gym->logo) }}"
                           name="logo" type="file" class="form-control">
                    <br/>
                    <label for="logo" style="cursor: pointer;">
                        <img id="preview2" src="{{asset('resources/assets/front/img/preview_icon.png')}}"
                             style="height: 120px;width: 120px;object-fit: contain;border: 1px solid #c2cad8;"
                             alt="preview image"/>
                    </label>
                </div>
            </div>


            <div class="form-group col-md-12 col-sm-12">
                <label class="col-md-3 col-sm-6  control-label text-right">{{trans('admin.gym_cover_image')}}</label>
                <div class="col-md-3 col-sm-6 ">
                    <input id="gym_cover_image" value="{{ old('cover_image', @$gym->cover_image) }}"
                           name="cover_image" type="file" class="form-control">
                    <br/>
                    <label for="gym_cover_image" style="cursor: pointer;">
                        <img id="preview3" src="{{asset('resources/assets/front/img/preview_icon.png')}}"
                             style="height: 120px;width: 320px;object-fit: contain;border: 1px solid #c2cad8;object-fit: cover"
                             alt="preview image"/>
                    </label>
                </div>
            </div>

            <div style="clear: both;"></div>
            <h5 class="form-section"><i class="fa fa-info-circle"></i> {{trans('admin.gym_info')}}</h5>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.gym_name')}} <span
                            class="required">*</span></label>
                <div class="col-md-9">
                    <input id="name" value="{{ old('name', @$gym->name) }}"
                           name="name_{{$lang}}" type="text" class="form-control" required>
                </div>
            </div>


            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.about_gym')}} <span
                            class="required">*</span></label>
                <div class="col-md-9">
                 <textarea id="description" required
                           name="description_{{$lang}}" class="form-control"
                           rows="6">{{ old('description', @$gym->description) }}</textarea>
                </div>
            </div>
{{--            <div class="form-group col-md-12">--}}
{{--                <label class="col-md-3 control-label text-right">{{trans('admin.work_hours')}}</label>--}}
{{--                <div class="col-md-9">--}}
{{--                 <textarea id="work_hours"--}}
{{--                           name="work_hours" class="form-control"--}}
{{--                           rows="2">{{ old('work_hours', @$gym->work_hours) }}</textarea>--}}
{{--                </div>--}}
{{--            </div>--}}


{{--            <div class="form-group col-md-12">--}}
{{--                <label class="col-md-3 control-label text-right">{{trans('admin.subscription_price')}}</label>--}}
{{--                <div class="col-md-6">--}}
{{--                    <div class="input-icon">--}}
{{--                        <i style="margin: 4px 6px 4px 2px;">{{trans('admin.currency_unit_ex')}}</i>--}}
{{--                        <input id="price" value="{{ old('price', @$gym->price) }}" step="any"--}}
{{--                               name="price" type="number" class="form-control">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div style="clear: both;"></div>
            <h5 class="form-section">{{trans('admin.gym_categories')}} <span class="required">*</span></h5>

            <div class="row" style="clear:both;">
                <div class="form-group col-md-12">
                    <label></label>
                    <ul style="list-style: none;">
                        @foreach($categories as $category)
                            <li class="col-lg-4  col-md-4  col-sm-4 ">
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-10">
                                        <label class="mt-checkbox mt-checkbox-outline">
                                            <input type="checkbox" name="categories[]" value="{{$category->id}}"
                                                   required> {{$category->name}}
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div style="clear: both;"></div>
            <h5 class="form-section"><i class="fa fa-list-ul"></i> {{trans('admin.gym_services')}} <span
                        class="required">*</span></h5>

            <div class="row">
                <div class="form-group col-md-12">
                    @foreach($services->chunk(3) as $gymservices)
                        <ul style="list-style: none;" class="col-lg-4  col-md-4  col-sm-4">
                            @foreach($gymservices as $service)
                                <li>
                                    <div class="form-group">
                                        <div class="col-sm-offset-1 col-sm-10">
                                            <label class="mt-checkbox mt-checkbox-outline">
                                                <input type="checkbox" name="services[]" value="{{$service->id}}"
                                                       required> {{$service->name}}
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach

                </div>
            </div>
            <div style="clear: both;"></div>
            <h5 class="form-section"><i class="fa fa-info-circle"></i> {{trans('admin.contact_info')}}</h5>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.main_phone')}} <span class="required">*</span></label>
                <div class="col-md-9">
                    <div class="input-icon">
                        <i class="fa fa-phone"></i>
                        <input id="main_phone" value="{{ old('main_phone', @$gym->main_phone) }}"
                               name="main_phone" type="text" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.website')}}</label>
                <div class="col-md-9">
                    <div class="input-icon">
                        <i class="fa fa-globe"></i>
                        <input id="website" value="{{ old('socials[website]', @$gym->socials['website']) }}" dir="ltr"
                               name="socials[website]" type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.facebook')}}</label>
                <div class="col-md-9">
                    <div class="input-icon">
                        <i class="fa fa-facebook"></i>
                        <input id="facebook" value="{{ old('socials[facebook]', @$gym->socials['facebook']) }}" dir="ltr"
                               name="socials[facebook]" type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.twitter')}}</label>
                <div class="col-md-9">
                    <div class="input-icon">
                        <i class="fa fa-twitter"></i>
                        <input id="twitter" value="{{ old('socials[twitter]', @$gym->socials['twitter']) }}" dir="ltr"
                               name="socials[twitter]" type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.instagram')}}</label>
                <div class="col-md-9">
                    <div class="input-icon">
                        <i class="fa fa-instagram"></i>
                        <input id="instagram" value="{{ old('socials[instagram]', @$gym->socials['instagram']) }}" dir="ltr"
                               name="socials[instagram]" type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.linkedin')}}</label>
                <div class="col-md-9">
                    <div class="input-icon">
                        <i class="fa fa-linkedin"></i>
                        <input id="linkedin" value="{{ old('socials[linkedin]', @$gym->socials['linkedin']) }}" dir="ltr"
                               name="socials[linkedin]" type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="control-label col-md-3">{{trans('admin.other_phones')}}</label>
                <div class="col-md-9">
                    <input id="tags_1" name="phones" type="text" class="form-control tags" value="" placeholder="{{trans('admin.phones')}}"/>
                </div>
            </div>


            <div style="clear: both;"></div>
            <h5 class="form-section"><i class="fa fa-info-circle"></i> {{trans('admin.place_info')}}</h5>

            <div class="form-group col-md-6 col-sm-6">
                <label class="col-md-3 control-label text-right">{{trans('global.address')}} <span
                            class="required">*</span></label>
                <div class="col-md-9">
                    <div class="input-icon">
                        <i class="fa fa-map-marker"></i>
                        <input class="form-control" type="text" name="address" required
                               placeholder="{{trans('global.address')}}">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-6 col-sm-6">

            </div>
            <div style="clear: both"></div>
            <div class="form-group col-md-6 col-sm-6">
                <label class="col-md-3 control-label text-right">{{trans('admin.city')}} <span class="required">*</span></label>
                <div class="col-md-9">
                    <select name="city_id" id="city" class="form-control" required>
                        <option value="">{{trans('admin.choose_city')}}</option>
                        @foreach($cities as $city)
                            <option value="{{$city->id}}"
                                    @if($city->id == @$gym->district->city_id) selected @endif>{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label class="col-md-3 control-label text-right">{{trans('admin.district')}} <span
                            class="required">*</span></label>
                <div class="col-md-9">
                    <select name="district_id" id="district_id" class="form-control" required>
                        <option value="">{{trans('admin.choose_district')}}</option>
                        @foreach($districts as $district)
                            <option value="{{$district->id}}" class="districts_of_city_{{$district->city_id}}"
                                    @if($district->id == @$gym->district_id) selected @endif>{{$district->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="clear: both;"></div>
            <h5 class="form-section"><i class="fa fa-info-circle"></i> {{trans('admin.choose_map_location')}} <span
                        class="required">*</span></h5>

            <div class="row">
                <div class="form-group col-md-12">
                    <input type="text" id="mapsearch" size="50" placeholder="{{trans('admin.search_location')}}">
                    <div id="map-canvas"></div>
                </div>
            </div>

            <input name="lat" id="latitude" value="{{@$gym->lat ? $gym->lat : $mainSettings->longitude}}"
                   type="hidden"/>
            <input name="lng" id="longitude" value="{{@$gym->lng ? $gym->lng : $mainSettings->longitude}}"
                   type="hidden"/>
            <div style=" clear: both;"></div>


            <div class="form-actions" style="clear:both;">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">

                        <button type="submit" class="btn green">{{trans('global.save')}}</button>
                        <input type="reset" class="btn default" value="{{trans('admin.reset')}}">
                    </div>
                </div>
            </div>
        </div>
    </form>


@endsection

@section('sub_scripts')

    <script src="{{asset('/')}}resources/assets/admin/global/plugins/jquery-tags-input/jquery.tagsinput.min.js" type="text/javascript"></script>
    <script src="{{asset('/')}}resources/assets/admin/global/scripts/dropzone.min.js"
            type="text/javascript"></script>
    <script
            src="https://unpkg.com/dropzone@5.7.0/dist/dropzone.js"
            type="text/javascript"></script>
    <script
            src="https://unpkg.com/cropperjs@1.5.6/dist/cropper.js"
            type="text/javascript"></script>

    <script>
        // images
        jQuery("#dpz-multiple-files").dropzone({
            addRemoveLinks: true,
            maxFiles: 20,
            acceptedFiles: ".jpg,.jpeg,.png",
            init: function () {
                myDropzone = this;
                        @if(@$getImages)
                        @foreach($getImages as $image)
                var mockFile = {name: '{{$image['name']}}', size: '{{$image['size']}}'};

                myDropzone.emit("addedfile", mockFile);
                myDropzone.emit("thumbnail", mockFile, '{{$image['path'].'/'.$image['name']}}');
                myDropzone.emit("complete", mockFile);
                @endforeach
                @endif
            },
            success: function (file, response) {
                if (response['target_file'] != '') {
                    var currentValue = jQuery("#attached_files").val();
                    if (currentValue == '') {
                        jQuery("#attached_files").val(response['target_file']);
                    } else {
                        jQuery("#attached_files").val(currentValue + "," + response['target_file']);
                    }
                }
            }, removedfile: function (file) {
                file.previewElement.remove();
                x = confirm('{{trans('admin.are_you_sure')}}');
                if (!x) return false;

                var file_up_names = $('#attached_files').val();
                var attached_files = '';
                file_up_names = file_up_names.split(",");

                for (var i = 0; i < file_up_names.length; ++i) {
                    filename = file_up_names[i].trim();
                    filename = filename.split("-");

                    file_name = file.name.split('.').slice(0, -1).join('.');
                    file_name = file_name.split("-");
                    // alert(filename[0].toString().trim()+ ' - ' + file_name[0].toString().trim());
                    if (filename[0].toString().trim() != file_name[0].toString().trim()) {
                        attached_files += file_up_names[i].trim() + ',';
                    }
                }
                $('#attached_files').val(attached_files);

            }
        });

        @if(@$getImages)
        jQuery("#attached_files").val('{{implode(',', collect($getImages)->pluck('name')->toArray())}}');
        @endif

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUtpFU1OSQwyfjIdsUdKgzRAdedm5Atmg&libraries=places"
            type="text/javascript"></script>
    <script>

        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            {{--center: {--}}
                    {{--    lat: {{@$gym->lat ? $gym->lat : $mainSettings->latitude}},--}}
                    {{--    lng: {{@$gym->lng ? $gym->lng : $mainSettings->longitude}}--}}
                    {{--},--}}
            zoom: 12
        });
        var marker = new google.maps.Marker({
            {{--position:{--}}
                    {{--    lat: {{@$gym->lat ? $gym->lat : $mainSettings->latitude}},--}}
                    {{--    lng: {{@$gym->lng ? $gym->lng : $mainSettings->longitude}}--}}
                    {{--},--}}
            map: map,
            draggable: true
        });
        google.maps.event.addListener(marker, 'dragend', function (evt) {
            $('#latitude').val(evt.latLng.lat());
            $('#longitude').val(evt.latLng.lng());
            // document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
        });
        // $('#latitude').val(place.geometry.location.lat());
        // $('#longitude').val(place.geometry.location.lng());
        var searchBox = new google.maps.places.SearchBox(document.getElementById('mapsearch'));

        google.maps.event.addListener(searchBox, 'places_changed', function () {
            // console.log(searchBox.getPlaces());
            var places = searchBox.getPlaces();

            var bounds = new google.maps.LatLngBounds();

            var i, place;
            for (i = 0; place = places[i]; i++) {
                console.log(place.geometry.location.lat());
                bounds.extend(place.geometry.location);
                marker.setPosition(place.geometry.location);
                $('#latitude').val(place.geometry.location.lat());
                $('#longitude').val(place.geometry.location.lng());
            }
            map.fitBounds(bounds);
            marker.setZoom(12);
        });

        // placeMarker(marker);
        // function placeMarker(location) {
        //     console.log('ddd',marker);
        //     marker.setPosition(location);
        //     var latitude = location.lat();
        //     var longitude = location.lng();
        //     $('#latitude').val(latitude);
        //     $('#longitude').val(longitude);
        //     console.log(latitude + ', ' + longitude);
        // }


        var city = $("#city").val();
        var district_id = $("#district_id").val();
        $("#district_id option").hide();
        $("#district_id option:first").show();
        $("#district_id option.districts_of_city_" + city).show();

        $("#city").change(function (e) {
            var city = $("#city").val();
            $("#district_id option").hide();
            $("#district_id option:selected").removeAttr('selected');
            $("#district_id option.districts_of_city_" + city).show();
            $("#district_id option:first").show();
            $("#district_id option:first").attr('selected', true);
        });


    </script>

    <script>
        $(function () {
            $("#birthday").datepicker({
                changeMonth: true,
                changeYear: true,
                minDate: "-60Y",
                maxDate: "-10Y",
                dateFormat: "yy-mm-dd"

            });
        });
    </script>
    <script>


        function readURL(input, id = '') {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#preview' + id).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#gym_image").change(function () {
            readURL(this, 1);
        });

        $("#logo").change(function () {
            readURL(this, 2);
        });

        $("#gym_cover_image").change(function () {
            readURL(this, 3);
        });


        $('.dz-remove').html("{{trans('admin.delete_file')}}");

        $('#tags_1').tagsInput({
            width: 'auto',
            defaultText: '{{trans('admin.add_phones')}}',
            'onAddTag': function () {
                //alert(1);
            },
        });
    </script>
@endsection