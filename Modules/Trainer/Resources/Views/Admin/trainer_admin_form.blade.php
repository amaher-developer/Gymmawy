@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listTrainer') }}">Trainers</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('styles')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .district_city_li {display: none;}
        .maher-gender-input {
            width: 8%;
            float: right;
        }
        .maher-gender-span {
            padding: 10px 20px 0 0;
            float: right;
        }
        .required {
            color: #e02222;
        }
    </style>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('page_body')
    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}

            @foreach($systemLanguages as $language)
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Name {{$language}}</label>
                <div class="col-md-9">
                    <input id="name_{{$language}}" value="{{ old('name_'.$language, $trainer['name_'.$language]) }}"
                           name="name_{{$language}}" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">About {{$language}}</label>
                <div class="col-md-9">
                 <textarea id="about_{{$language}}"
                           name="about_{{$language}}" class="form-control">{{ old('about_'.$language, $trainer['about_'.$language]) }}</textarea>
                </div>
            </div>
            @endforeach

            <div style="clear: both;"><hr/></div>
            <div class="form-group col-md-12  col-sm-12">
                <label class="col-md-3  col-sm-3 control-label">{{trans('admin.gym_name')}}</label>
                <div class="col-md-9  col-sm-9">
                    <input id="gym_name" value="{{ old('gym_name', @$trainer->gym_name) }}"
                           name="gym_name" type="text" class="form-control" >
                </div>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label class="col-md-3  col-sm-3 control-label">{{trans('admin.birthday')}} <span class="required">*</span></label>
                <div class="col-md-3 col-sm-3">
                    <div class="input-group input-medium date " data-date-format="dd-mm-yyyy">
                        <input id="birthday" value="{{ old('birthday', @$trainer->birthday) }}" readonly
                               name="birthday" type="text" class="form-control" required>
                        <span class="input-group-btn">
                            <button class="btn default" type="button">
                                <i class="fa fa-calendar"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group col-md-12  col-sm-12">
                <label class="col-md-3  col-sm-3 control-label">{{trans('global.gender')}} <span class="required">*</span></label>
                <div class="col-md-3  col-sm-3">
                    <input id="gender" value="1" @if(@$trainer->gender == 1) checked @endif step="any" required
                           name="gender" type="radio" class="form-control  maher-gender-input" > <span class="maher-gender-span">{{trans('global.male')}}</span>
                </div>
                <div class="col-md-3 col-sm-3">
                    <input id="gender" value="2"  @if(@$trainer->gender == 2) checked @endif step="any" required
                           name="gender" type="radio" class="form-control  maher-gender-input" > <span class="maher-gender-span">{{trans('global.female')}}</span>
                </div>
            </div>
            <div class="form-group col-md-12  col-sm-12">
                <label class="col-md-3  col-sm-3 control-label">{{trans('admin.experience_years')}}</label>
                <div class="col-md-3 col-sm-3">
                    <input id="experience" value="{{ old('experience', @$trainer->experience ?? 0) }}" step="any"
                           name="experience" type="number" class="form-control" min="0" max="60">
                </div>
            </div>
            <div class="form-group col-md-12 col-sm-12">

                <label class="col-md-3  col-sm-3 control-label">{{trans('admin.personal_image')}} <span class="required">*</span></label>
                <div class="col-md-8  col-sm-8">
                    <input id="image" value="{{ old('image', @$trainer->image) }}"
                           name="image" type="file" class="form-control" >
                    <br/>
                    <img id="preview" src="@if(@$trainer->image) {{@$trainer->image}} @else {{asset('resources/assets/front/img/preview_icon.png')}} @endif" style="height: 120px;object-fit: contain;border: 1px solid #c2cad8;" alt="preview image" />

                </div>
                {{--@if(!empty(@$trainer->image))--}}
                {{--<label class="col-md-1 control-label">--}}
                {{--<a href="{{ @$trainer->image }}" class="fancybox-button" data-rel="fancybox-button">--}}
                {{--view--}}
                {{--</a>--}}
                {{--</label>--}}
                {{--@endif--}}
            </div>

            <div style="clear: both;"></div>
            <h5 class="form-section">{{trans('admin.contact_info')}}</h5>
            <div class="form-group col-md-12 col-sm-12">
                <label class="col-md-3  col-sm-3 control-label">{{trans('admin.phone')}} <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9">
                    <input id="phone" value="{{ old('phone', @$trainer->phone) }}" dir="ltr" required
                           name="phone" type="text" class="form-control" >
                </div>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label class="col-md-3  col-sm-3 control-label">{{trans('admin.website')}}</label>
                <div class="col-md-9  col-sm-9">
                    <input id="website" value="{{ old('website', @$trainer->website) }}" dir="ltr"
                           name="website" type="text" class="form-control" >
                </div>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label class="col-md-3  col-sm-3 control-label">{{trans('admin.facebook')}}</label>
                <div class="col-md-9  col-sm-9">
                    <input id="facebook" value="{{ old('facebook', @$trainer->facebook) }}" dir="ltr"
                           name="facebook" type="text" class="form-control" >
                </div>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label class="col-md-3  col-sm-3 control-label">{{trans('admin.twitter')}}</label>
                <div class="col-md-9  col-sm-9">
                    <input id="twitter" value="{{ old('twitter', @$trainer->twitter) }}" dir="ltr"
                           name="twitter" type="text" class="form-control" >
                </div>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label class="col-md-3  col-sm-3 control-label">{{trans('admin.instagram')}}</label>
                <div class="col-md-9 col-sm-9">
                    <input id="instagram" value="{{ old('instagram', @$trainer->instagram) }}" dir="ltr"
                           name="instagram" type="text" class="form-control" >
                </div>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label class="col-md-3 col-sm-3 control-label">{{trans('admin.snapchat')}}</label>
                <div class="col-md-9 col-sm-9">
                    <input id="snapchat" value="{{ old('snapchat', @$trainer->snapchat) }}" dir="ltr"
                           name="snapchat" type="text" class="form-control" >
                </div>
            </div>


            <div style="clear: both;"></div>
            <h5 class="form-section">{{trans('admin.categories')}} <span class="required">*</span></h5>

            <div class="row" style="clear:both;" >
                <div class="form-group col-md-12 col-sm-12">
                    <label ></label>
                    <ul style="list-style: none;">
                        @foreach($categories as $category)
                            <li class="col-lg-4  col-md-4  col-sm-4"  >
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-10">
                                        <label class="mt-checkbox mt-checkbox-outline">
                                            <input type="checkbox"  name="categories[]" value="{{$category->id}}" @if(in_array($category->id, $trainer_category_ids)) checked @endif required> {{$category->name}}
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

{{--            <div style="clear: both;"></div>--}}
{{--            <h5 class="form-section">{{trans('admin.coverage_districts')}} <span class="required">*</span></h5>--}}
{{--            <div class="row"  style="clear: both;">--}}

{{--                <div class="form-group col-md-1 col-sm-1">--}}
{{--                </div>--}}
{{--                <div class="form-group col-md-3 col-sm-3">--}}
{{--                    <select name="city_id" id="city_id" class="form-control" required>--}}
{{--                        <option value="">{{trans('admin.choose_city')}}</option>--}}
{{--                        @foreach($cities as $city)--}}
{{--                            <option value="{{$city->id}}" @if($city->id == @$trainer->city_id) selected @endif>{{$city->name}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="row" style="clear:both;" id="district_div">--}}
{{--                <div class="form-group col-md-12 col-sm-12">--}}
{{--                    <label id="city_name"></label>--}}
{{--                    <ul style="list-style: none;">--}}
{{--                        @foreach($districts as $district)--}}
{{--                            <li class="district_city_li col-lg-4  col-md-4 col-sm-6 district_city_{{$district->city_id}}" id="district_city_{{$district->id}}" @if(@$trainer->city_id == $district->city_id) style="display: block;" @endif>--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="col-sm-offset-1 col-sm-10">--}}
{{--                                        <label class="mt-checkbox mt-checkbox-outline">--}}
{{--                                            <input type="checkbox" name="districts[]" value="{{$district->id}}" @if(in_array($district->id, $trainer_district_ids)) checked @endif required> {{$district->name}}--}}
{{--                                            <span></span>--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="form-group col-md-12" style="clear:both;">
                <hr/>
            </div>

            <div class="form-group col-md-12" style="clear:both;">
                <label class="col-md-2 control-label" style="padding-top: 14px;">{{trans('admin.enable')}}</label>
                <div class="col-md-10">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" value="1" name="published"
                                   @if(@$trainer->published) {{ $trainer->published?'checked':'' }} @else checked @endif>
                            {{--                            <input type="hidden" name="published" value="0">--}}
                            <span></span>
                        </label>
                    </div>
                </div>

            </div>

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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $( function() {
            $( "#birthday" ).datepicker({
                changeMonth: true,
                changeYear: true,
                minDate: "-60Y",
                maxDate: "-10Y",
                dateFormat: "yy-mm-dd"

            });
        } );
    </script>
    <script>
        $( "#city_id" ).change(function() {
            var city_id = $( this ).val();
            $('.district_city_li').hide();
            $('.district_city_'+city_id).show();
        });
        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#image").change(function() {
            readURL(this);
        });
    </script>
    @endsection
