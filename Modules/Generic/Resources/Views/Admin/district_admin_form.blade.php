@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
             <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listDistrict') }}">Districts</a>
             <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('sub_styles')
    <style>
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
    </style>
@endsection
@section('page_body')
    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
     <div class="form-body">
        {{csrf_field()}}

         <div class="form-group col-md-6">
             <label class="col-md-3 control-label">Name AR</label>
             <div class="col-md-9">
                 <input id="name_ar" value="{{ old('name_ar', $district->name_ar) }}"
                        name="name_ar" type="text" class="form-control" >
             </div>
         </div>
         <div class="form-group col-md-6">
             <label class="col-md-3 control-label">Name EN</label>
             <div class="col-md-9">
                 <input id="name_en" value="{{ old('name_en', $district->name_en) }}"
                        name="name_en" type="text" class="form-control" >
             </div>
         </div>
            <div class="form-group col-md-6">
    <label class="col-md-3 control-label">City Id</label>
    <div class="col-md-9">
        <select id="city_id" name="city_id" class="bs-select form-control" data-live-search="true" >
            @foreach($cities as $item)
                <option
                        {{ old('city_id', $district->city_id)==$item->id?'selected':'' }}
                        value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
</div>
            

    <div class="form-group col-md-6" style="clear:both;">
        <label class="col-md-3 control-label">{{trans('admin.disable')}}</label>
        <div class="col-md-9">
            <div class="mt-checkbox-list">
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="hidden" name="deleted_at" value="">
                    <input type="checkbox" value="{{ date('Y-m-d') }}" name="deleted_at"
                            {{ $district->trashed()?'checked':'' }}>
                    <span></span>
                </label>
            </div>
        </div>

    </div>


         <div class="form-group col-md-12">
             <label class="col-md-3 control-label">Address</label>
             <div class="col-md-9">
                 <input  value="{{ $district->name_en.' '.$district->city->name_en.' '.'Egypt' }}"
                        type="text" class="form-control" >
             </div>
         </div>
         <div class="form-group col-md-12">
             <label class="col-md-3 control-label">Address</label>
             <div class="col-md-9">
                 <input  value="{{ $district->name_ar.' '.$district->city->name_ar.' '.'مصر' }}"
                        type="text" class="form-control" >
             </div>
         </div>
         <div style="clear: both;"></div>
         <h5 class="form-section"><i class="fa fa-info-circle"></i> أختر موقعك علي الخريطة <span
                     class="required">*</span></h5>

         <div class="row">
             <div class="form-group col-md-12">
                 <input type="text" id="mapsearch" value="{{ $district->name_ar.' '.$district->city->name_ar.' '.'مصر' }}" size="50" placeholder="ابحث عن موقعك">
                 <div id="map-canvas"></div>
             </div>
         </div>

         <input name="lat" id="latitude" value="{{ old('lat', $district->lat) }}"
                type="hidden"/>
         <input name="lng" id="longitude" value="{{ old('lng', $district->lng) }}"
                type="hidden"/>
         <div style=" clear: both;"></div>

    <div class="form-actions" style="clear:both;">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <button type="submit" class="btn green">{{trans('admin.submit')}}</button>
                <input type="reset" class="btn default" value="{{trans('admin.reset')}}">
            </div>
        </div>
    </div>
    </div>
    </form>
@endsection
@section('sub_scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUtpFU1OSQwyfjIdsUdKgzRAdedm5Atmg&libraries=places"
            type="text/javascript"></script>
    <script>

        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            center: {
            },
            zoom: 8
        });
        var marker = new google.maps.Marker({
            position: {
            },
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
            marker.setZoom(8);
        });
    </script>
@endsection
