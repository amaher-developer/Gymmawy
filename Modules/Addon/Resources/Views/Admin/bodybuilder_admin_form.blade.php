@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listBodybuilder') }}">{{trans('admin.bodybuilders')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('sub_styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .district_city_li {
            display: none;
        }
    </style>
@endsection
@section('page_body')
    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.name')}} En</label>
                <div class="col-md-9">
                    <input id="name_en" value="{{ old('name_en', $bodybuilder->name_en) }}"
                           name="name_en" type="text" class="form-control" required>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.name')}} Ar</label>
                <div class="col-md-9">
                    <input id="name_ar" value="{{ old('name_ar', $bodybuilder->name_ar) }}"
                           name="name_ar" type="text" class="form-control" required>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.description')}} En</label>
                <div class="col-md-9">
                 <textarea id="description_en"
                           name="description_en" class="form-control" rows="10" dir="ltr"
                           required>{{ old('description_en', strip_tags($bodybuilder->description_en)) }}</textarea>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.description')}} Ar</label>
                <div class="col-md-9">
                 <textarea id="description_ar"
                           name="description_ar" class="form-control  " rows="10"
                           required>{{ old('description_ar', strip_tags($bodybuilder->description_ar)) }}</textarea>
                </div>
            </div>
            <div style="clear: both;"></div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.image')}}</label>
                <div class="col-md-8">
                    <input id="image" value="{{ old('image', $bodybuilder->image) }}"
                           name="image" type="file" class="form-control">
                </div>
                @if(!empty($bodybuilder->image))
                    <label class="col-md-1 control-label">
                        <a href="{{ $bodybuilder->image }}" class="fancybox-button" data-rel="fancybox-button">
                            view
                        </a>
                    </label>
                @endif
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.cover_image')}}</label>
                <div class="col-md-8">
                    <input id="cover_image" value="{{ old('cover_image', $bodybuilder->cover_image) }}"
                           name="cover_image" type="file" class="form-control">
                </div>
                @if(!empty($bodybuilder->cover_image))
                    <label class="col-md-1 control-label">
                        <a href="{{ $bodybuilder->cover_image }}" class="fancybox-button" data-rel="fancybox-button">
                            view
                        </a>
                    </label>
                @endif
            </div>

            <div class="clearfix" style="clear: both;float: none"><br/></div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.birthday')}}</label>

                <div class="col-md-3 col-sm-3">
                    <div class="input-group input-medium date " data-date-format="dd-mm-yyyy">
                        <input id="birthday" value="{{ old('birthday', $bodybuilder->birthday) }}" readonly
                               name="birthday" type="text" class="form-control" >
                        <span class="input-group-btn">
                            <button class="btn default" type="button">
                                <i class="fa fa-calendar"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div style="clear: both"></div>
            <div class="form-group col-md-6 col-sm-6">
                <label class="col-md-3 control-label text-right">{{trans('admin.city')}}</label>
                <div class="col-md-9">
                    <select name="city_id" id="city" class="form-control" >
                        <option value="">{{trans('admin.choose_city')}}</option>
                        @foreach($cities as $city)
                            <option value="{{$city->id}}"
                                    @if($city->id == @$bodybuilder->district->city_id) selected @endif>{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label class="col-md-3 control-label text-right">{{trans('admin.district')}} </label>
                <div class="col-md-9">
                    <select name="district_id" id="district_id" class="form-control" >
                        <option value="">{{trans('admin.choose_district')}}</option>
                        @foreach($districts as $district)
                            <option value="{{$district->id}}" class="districts_of_city_{{$district->city_id}}"
                                    @if($district->id == @$bodybuilder->district_id) selected @endif>{{$district->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="clear: both"></div>
            <div class="form-group col-md-6 col-sm-6">
                <label class="col-md-3 control-label text-right">{{trans('global.country')}} <span class="required">*</span></label>
                <div class="col-md-9">
                    <select name="country_id" id="country_id" class="form-control" required>
                        <option value="">{{trans('admin.choose_country')}}</option>
                        @foreach($countries as $country)
                            <option value="{{$country->id}}"
                                    @if($country->id == @$bodybuilder->country_id) selected @endif>{{$country->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="clearfix" style="clear: both;float: none"><hr/></div>

            <div id="newlink">
{{--                <div class="form-group col-md-4">--}}
{{--                    <label class="col-md-3 control-label">{{trans('admin.competition_name')}} En</label>--}}
{{--                    <div class="col-md-9">--}}
{{--                        <input  value=""--}}
{{--                                name="competition[name_en][]" type="text" class="form-control" >--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="form-group col-md-4">--}}
{{--                    <label class="col-md-3 control-label">{{trans('admin.competition_name')}} Ar</label>--}}
{{--                    <div class="col-md-9">--}}
{{--                        <input  value=""--}}
{{--                                name="competition[name_ar][]" type="text" class="form-control" >--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="form-group col-md-4">--}}
{{--                    <label class="col-md-3 control-label">{{trans('admin.year')}}</label>--}}
{{--                    <div class="col-md-9">--}}
{{--                        <input value="" name="competition[year][]" type="text" class="form-control" >--}}
{{--                    </div>--}}
{{--                </div>--}}

                @if(@$bodybuilder_competitions)
                    @foreach($bodybuilder_competitions as $i => $bodybuilder_competition)
                        <div id="{{$i+1}}">
                        <div class="clearfix" style="clear: both;float: none"><hr/></div>

                        <div class="form-group col-md-4">
                        <label class="col-md-3 control-label">{{trans('admin.competition_name')}} En</label>
                        <div class="col-md-9">
                            <input  value="{{$bodybuilder_competition['name_en']}}"
                                    name="competition[name_en][]" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="col-md-3 control-label">{{trans('admin.competition_name')}} Ar</label>
                        <div class="col-md-9">
                            <input  value="{{$bodybuilder_competition['name_ar']}}"
                                    name="competition[name_ar][]" type="text" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="col-md-3 control-label">{{trans('admin.year')}}</label>
                        <div class="col-md-9">
                            <input value="{{$bodybuilder_competition['year']}}" name="competition[year][]" type="text" class="form-control" required>
                        </div>
                    </div>

                     <div style="text-align:right;margin-right:65px"><a href="javascript:delIt({{$i+1}})">{{trans('admin.delete')}}</a></div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="clearfix" style="clear: both;float: none"><hr/></div>

            <div id="addnew"  class="form-group col-md-12">
                <a href="javascript:new_link()" class="col-md-12">{{trans('admin.add_new')}} </a>
            </div>

            <div class="clearfix" style="clear: both;float: none"><hr/></div>


{{--            <div class="form-group col-md-6" style="clear:both;">--}}
{{--                <label class="col-md-3 control-label">{{trans('admin.disable')}}</label>--}}
{{--                <div class="col-md-9">--}}
{{--                    <div class="mt-checkbox-list">--}}
{{--                        <label class="mt-checkbox mt-checkbox-outline">--}}
{{--                            <input type="checkbox" value="{{ date('Y-m-d') }}" name="deleted_at"--}}
{{--                                    {{ $bodybuilder->trashed()?'checked':'' }}>--}}
{{--                            <span></span>--}}
{{--                        </label>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}

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


    <!-- Template -->
    <div id="newlinktpl" style="display:none">

        <div class="clearfix" style="clear: both;float: none"><hr/></div>
        <div class="form-group col-md-4">
            <label class="col-md-3 control-label">{{trans('admin.competition_name')}} En</label>
            <div class="col-md-9">
                <input  value=""
                       name="competition[name_en][]" type="text" class="form-control" required>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label class="col-md-3 control-label">{{trans('admin.competition_name')}} Ar</label>
            <div class="col-md-9">
                <input  value=""
                       name="competition[name_ar][]" type="text" class="form-control" required>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label class="col-md-3 control-label">{{trans('admin.year')}}</label>
            <div class="col-md-9">
                <input value="" name="competition[year][]" type="text" class="form-control" required>
            </div>
        </div>
    </div>
@endsection
@section('sub_scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $( "#birthday" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });

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
        /*
        This script is identical to the above JavaScript function.
        */
        var ct = {{@$bodybuilder_competitions ? count($bodybuilder_competitions) : 1}};
        function new_link()
        {
            ct++;
            var div1 = document.createElement('div');
            div1.id = ct;
            // link to delete extended form elements
            var delLink = '<div style="text-align:right;margin-right:65px"><a href="javascript:delIt('+ ct +')">{{trans('admin.delete')}}</a></div>';
            div1.innerHTML = document.getElementById('newlinktpl').innerHTML + delLink;
            document.getElementById('newlink').appendChild(div1);
        }
        // function to delete the newly added set of elements
        function delIt(eleId)
        {
            d = document;
            var ele = d.getElementById(eleId);
            var parentEle = d.getElementById('newlink');
            parentEle.removeChild(ele);
        }
    </script>


@endsection
