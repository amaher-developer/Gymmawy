@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
    <li>
        <a href="{{ url('/operate') }}">Dashboard</a>
    <i class="fa fa-circle"></i>
    </li>
    <li>
        <a href="{{ route('editSetting',1) }}">{{ $title }}</a>
    </li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('sub_styles')

@endsection
@section('page_body')

    <form action="" method="post" enctype='multipart/form-data' id="settings_form">
        {{ csrf_field() }}
        <div class="form-body">
            <div class="tabbable-custom nav-justified">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a href="#info" data-toggle="tab">Web Site Info</a></li>
                    <li><a href="#social" data-toggle="tab">Links</a></li>
                    <li><a href="#contacts" id="contacts_tab_button" data-toggle="tab">Contacts</a></li>
                    <li><a href="#meta" data-toggle="tab">Meta Tags</a></li>
                    <li><a href="#pages" data-toggle="tab">Pages</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="info">

                        <hr>
                        <div class="form-group">
                            <label class="control-label">System is Under Maintenance?</label>
                            <input type="checkbox" name="under_maintenance" value="0" checked hidden>
                            <input type="checkbox" name="under_maintenance" value="1" @if($mainSettings->under_maintenance) checked @endif class="form-control">
                        </div>
                        <hr>

                        <div class="form-group">
                            <label class="control-label">Title Ar</label>
                            <input type="text" class="form-control  input-data" dir="rtl"
                                   value="{{$mainSettings->name_ar}}" name="name_ar" required
                                   data-bv-trigger="keyup change"
                                   data-bv-notempty-message="{{trans('generic::global.required')}}"/>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Title En</label>
                            <input type="text" class="form-control  input-data" dir="ltr"
                                   value="{{$mainSettings->name_en}}" name="name_en" required
                                   data-bv-trigger="keyup change"
                                   data-bv-notempty-message="{{trans('generic::global.required')}}"/>
                        </div>


                        <div class="form-group">

                            @if($mainSettings->logo_ar!='')
                                <div style="text-align: center">
                                    <img width="200" src="{{$mainSettings->logo_ar}}"/>
                                </div>
                            @endif
                            <label class="control-label">Logo Ar</label>
                            <input type="file" class="form-control  input-data"
                                   name="logo_ar" @if($mainSettings->logo_ar=='')required
                                   data-bv-notempty-message="{{trans('generic::global.required')}}"
                                    @endif/>
                        </div>


                        <div class="form-group">

                            @if($mainSettings->logo_en!='')
                                <div style="text-align: center">
                                    <img width="200" src="{{$mainSettings->logo_en}}"/>
                                </div>
                            @endif
                            <label class="control-label">Logo En</label>
                            <input type="file" class="form-control  input-data"
                                   name="logo_en" @if($mainSettings->logo_en=='')required
                                   data-bv-notempty-message="{{trans('generic::global.required')}}"
                                    @endif/>
                        </div>


                        <div class="form-group">

                            @if($mainSettings->logo_white_ar!='')
                                <div style="text-align: center">
                                    <img width="200" src="{{$mainSettings->logo_white_ar}}"/>
                                </div>
                            @endif
                            <label class="control-label">Logo White Ar</label>
                            <input type="file" class="form-control  input-data"
                                   name="logo_white_ar" @if($mainSettings->logo_white_ar=='')required
                                   data-bv-notempty-message="{{trans('generic::global.required')}}"
                                    @endif/>
                        </div>


                        <div class="form-group">

                            @if($mainSettings->logo_white_en!='')
                                <div style="text-align: center">
                                    <img width="200" src="{{$mainSettings->logo_white_en}}"/>
                                </div>
                            @endif
                            <label class="control-label">Logo White En</label>
                            <input type="file" class="form-control  input-data"
                                   name="logo_white_en" @if($mainSettings->logo_white_en=='')required
                                   data-bv-notempty-message="{{trans('generic::global.required')}}"
                                    @endif/>
                        </div>
                    </div>

                    <div class="tab-pane" id="social">

                        <div class="form-group">
                            <label class="control-label">Facebook</label>
                            <input type="url" class="form-control  input-data" dir="ltr"
                                   value="{{$mainSettings->facebook}}" name="facebook"
                                   data-bv-uri-message="{{trans('generic::generic::global.valid_url')}}"/>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Twitter</label>
                            <input type="url" class="form-control  input-data" dir="ltr"
                                   value="{{$mainSettings->twitter}}" name="twitter"/>
                        </div>


                        <div class="form-group">
                            <label class="control-label">Google Plus</label>
                            <input type="url" class="form-control  input-data" dir="ltr"
                                   value="{{$mainSettings->google_plus}}" name="google_plus"
                                   data-bv-uri-message="{{trans('generic::global.valid_url')}}"/>
                        </div>


                        <div class="form-group">
                            <label class="control-label">YouTube</label>
                            <input type="url" class="form-control  input-data" dir="ltr"
                                   value="{{$mainSettings->youtube}}" name="youtube"
                                   data-bv-uri-message="{{trans('generic::global.valid_url')}}"/>
                        </div>


                        <div class="form-group">
                            <label class="control-label">Instagram</label>
                            <input type="url" class="form-control  input-data" dir="ltr"
                                   value="{{$mainSettings->instagram}}" name="instagram"
                                   data-bv-uri-message="{{trans('generic::global.valid_url')}}"/>
                        </div>


                        <div class="form-group">
                            <label class="control-label">Android App </label>
                            <input type="url" class="form-control  input-data" dir="ltr"
                                   value="{{$mainSettings->android_app}}" name="android_app"
                                   data-bv-uri-message="{{trans('generic::global.valid_url')}}"/>
                        </div>

                        <div class="form-group">
                            <label class="control-label">ios App </label>
                            <input type="url" class="form-control  input-data" dir="ltr"
                                   value="{{$mainSettings->ios_app}}" name="ios_app"
                                   data-bv-uri-message="{{trans('generic::global.valid_url')}}"/>
                        </div>

                    </div>

                    <div class="tab-pane" id="contacts">

                        <div class="form-group">
                            <label class="control-label">Address Ar</label>
                            <textarea required data-bv-trigger="keyup change" dir="rtl"
                                      data-bv-notempty-message="{{trans('generic::global.required')}}"
                                      class="form-control  input-data "
                                      name="address_ar">{{$mainSettings->address_ar}}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Address En</label>
                            <textarea required data-bv-trigger="keyup change"
                                      data-bv-notempty-message="{{trans('generic::global.required')}}"
                                      class="form-control  input-data "
                                      name="address_en">{{$mainSettings->address_en}}</textarea>
                        </div>


                        <div class="form-group ">
                            <label class="control-label">Location on Map</label>
                            <input name="latitude" id="latitude" value="{{$mainSettings->latitude}}"
                                   type="hidden"/>
                            <input name="longitude" id="longitude" value="{{$mainSettings->longitude}}"
                                   type="hidden"/>

                            <div class="form-group map_container" style="width: 500px;height: 300px;">
                                <div class="map" id="googleMap" style="width: 500px;height: 300px;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Phone</label>
                            <input type="text" class="form-control  input-data" dir="ltr"
                                   value="{{$mainSettings->phone}}" name="phone"/>
                        </div>


                        <div class="form-group">
                            <label class="control-label">Support Email</label>
                            <input type="text" class="form-control  input-data" dir="ltr"
                                   value="{{$mainSettings->support_email}}" name="support_email"
                                   required data-bv-trigger="keyup change"
                                   data-bv-notempty-message="{{trans('generic::global.required')}}"
                                   data-bv-emailaddress-message="{{trans('generic::global.valid_email')}}"/>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Noreply Email</label>
                            <input type="text" class="form-control  input-data" dir="ltr"
                                   value="{{$mainSettings->noreply_email}}" name="noreply_email"
                                   required data-bv-trigger="keyup change"
                                   data-bv-notempty-message="{{trans('generic::global.required')}}"
                                   data-bv-emailaddress-message="{{trans('generic::global.valid_email')}}"/>
                        </div>

                    </div>

                    <div class="tab-pane" id="meta">

                        <div class="form-group">
                            <label>Meta Key Words Ar</label>
                            <select class="form-control js-tags-multi-ar" name="meta_keywords_ar[]"
                                    style="width: 100% !important" dir="rtl"
                                    multiple required
                                    data-bv-notempty-message="{{trans('generic::global.required')}}">
                                @if(count($mainSettings->meta_keywords_ar )>0)
                                    @foreach($mainSettings->meta_keywords_ar as $keyword)
                                        <option selected="selected">{{$keyword}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Meta Description Ar</label>
                            <textarea required class="form-control  input-data" dir="rtl"
                                      data-bv-trigger="keyup change"
                                      data-bv-notempty-message="{{trans('generic::global.required')}}"
                                      name="meta_description_ar"
                                      id="meta_description_ar">{{old('meta_description_ar') ?old('meta_description_ar'):($mainSettings->meta_description_ar ? $mainSettings->meta_description_ar: '')}}</textarea>
                        </div>


                        <div class="form-group">
                            <label>Meta Key Words En</label>
                            <select class="form-control js-tags-multi" name="meta_keywords_en[]"
                                    multiple style="width: 100% !important"
                                    required data-bv-notempty-message="{{trans('generic::global.required')}}">
                                @if(count($mainSettings->meta_keywords_en )>0)
                                    @foreach($mainSettings->meta_keywords_en as $keyword)
                                        <option selected="selected">{{$keyword}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Meta Description En</label>
                            <textarea required class="form-control  input-data"
                                      data-bv-trigger="keyup change"
                                      data-bv-notempty-message="{{trans('generic::global.required')}}"
                                      name="meta_description_en"
                                      id="meta_description_en">{{old('meta_description_en') ?old('meta_description_en'):($mainSettings->meta_description_en ? $mainSettings->meta_description_en: '')}}</textarea>
                        </div>

                    </div>
                    <div class="tab-pane" id="pages">
                        <h1>About</h1>
                        <div class="form-group">
                            <label class="control-label">About Ar</label>
                            <textarea required data-bv-trigger="keyup change" dir="rtl"
                                      data-bv-notempty-message="{{trans('generic::global.required')}}"
                                      class="form-control  input-data summernote-textarea-ar"
                                      name="about_ar">{{$mainSettings->about_ar}}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="control-label">About En</label>
                            <textarea required data-bv-trigger="keyup change"
                                      data-bv-notempty-message="{{trans('generic::global.required')}}"
                                      class="form-control  input-data summernote-textarea"
                                      name="about_en">{{$mainSettings->about_en}}</textarea>
                        </div>
                        <hr>
                        <h1>Terms</h1>
                        <div class="form-group">
                            <label>Terms Ar</label>
                            <textarea required class="form-control  input-data summernote-textarea-ar"
                                      dir="rtl"
                                      data-bv-trigger="keyup change"
                                      data-bv-notempty-message="{{trans('generic::global.required')}}"
                                      name="terms_ar"
                                      id="terms_ar">{{old('terms_ar') ?old('terms_ar'):($mainSettings->terms_ar ? $mainSettings->terms_ar: '')}}</textarea>
                        </div>


                        <div class="form-group">
                            <label>Terms En</label>
                            <textarea required class="form-control  input-data summernote-textarea"
                                      data-bv-trigger="keyup change"
                                      data-bv-notempty-message="{{trans('generic::global.required')}}"
                                      name="terms_en"
                                      id="terms_en">{{old('terms_en') ?old('terms_en'):($mainSettings->terms_en ? $mainSettings->terms_en: '')}}</textarea>
                        </div>
                        <hr>
                        <h1>Policy</h1>
                        <div class="form-group">
                            <label>Policy Ar</label>
                            <textarea required class="form-control  input-data summernote-textarea-ar"
                                      dir="rtl"
                                      data-bv-trigger="keyup change"
                                      data-bv-notempty-message="{{trans('generic::global.required')}}"
                                      name="policy_ar"
                                      id="policy_ar">{{old('policy_ar') ?old('policy_ar'):($mainSettings->policy_ar ? $mainSettings->policy_ar: '')}}</textarea>
                        </div>


                        <div class="form-group">
                            <label>Policy En</label>
                            <textarea required class="form-control  input-data summernote-textarea"
                                      data-bv-trigger="keyup change"
                                      data-bv-notempty-message="{{trans('generic::global.required')}}"
                                      name="policy_en"
                                      id="policy_en">{{old('policy_en') ?old('policy_en'):($mainSettings->policy_en ? $mainSettings->policy_en: '')}}</textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="form-actions">
            <div class="row">
                <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn green">Submit</button>
                    <input type="reset" class="btn default" value="Reset">
                </div>
            </div>
        </div>
    </form>


@endsection


@section('sub_scripts')
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBUtpFU1OSQwyfjIdsUdKgzRAdedm5Atmg"
            type="text/javascript"></script>
    <script>

        $("#contacts_tab_button").click(function () {
            var marker;
            var map;

            function initialize(lat, lng) {

                var mapProp = {
                    center: new google.maps.LatLng(lat, lng),
                    zoom: 8,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById("googleMap"), mapProp);


                placeMarker(new google.maps.LatLng(lat, lng));

                //Add listener
                google.maps.event.addListener(map, "click", function (event) {
                    placeMarker(event.latLng);
                }); //end addListener

                function placeMarker(location) {
                    if (marker == undefined) {
                        marker = new google.maps.Marker({
                            position: location,
                            map: map,
                            animation: google.maps.Animation.DROP
                        });
                    }
                    else {
                        marker.setPosition(location);
                    }
                    var latitude = location.lat();
                    var longitude = location.lng();
                    $('#latitude').val(latitude);
                    $('#longitude').val(longitude);
                    console.log(latitude + ', ' + longitude);
                }
            }

            @if($mainSettings->latitude&& $mainSettings->longitude )
google.maps.event.addDomListener(window, 'load', initialize('{{$mainSettings->latitude}}', '{{$mainSettings->longitude}}'));
            @else
google.maps.event.addDomListener(window, 'load', initialize(23.725011735951796, 45.615234375));
            @endif

        });
    </script>
@endsection
