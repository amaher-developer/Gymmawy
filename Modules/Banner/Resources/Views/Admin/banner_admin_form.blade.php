@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listBanner') }}">{{trans('admin.banners')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('sub_styles')

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
@section('page_body')
    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}


            <div class="form-group col-md-12 ">
                <label class="col-md-3 col-sm-6 control-label text-right">{{trans('admin.image')}} <span
                            class="required">*</span></label>
                <div class="col-md-9 col-sm-6 ">
                    <input id="image" value="{{ old('image', @$banner->image) }}"
                           name="image" type="file" class="form-control" @if(!$banner->image) required @endif>
                    <br/>
                    <label for="gym_image" style="cursor: pointer;">
                        <img id="preview1" src="{{@$banner->image ? @$banner->image : asset('resources/assets/front/img/preview_icon.png')}}"
                             style="width: 300px;height: 79px; object-fit: contain;border: 1px solid #c2cad8;"
                             alt="preview image"/>
                    </label>
                </div>

            </div>
            <div class="clearfix"></div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.title')}}</label>
                <div class="col-md-9">
                    <input id="title" value="{{ old('title', $banner->title) }}" step="any"
                           name="title" type="text" class="form-control">
                </div>
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label class="col-md-3 control-label text-right">{{trans('admin.category')}} <span class="required">*</span></label>
                <div class="col-md-9">
                    <select name="category_id" id="category_id" class="form-control" >
                        <option value="">{{trans('admin.all')}}</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}"
                                    @if($category->id == @$banner->category_id) selected @endif>{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group col-md-6 col-sm-6">
                <label class="col-md-3 control-label text-right">{{trans('admin.type')}} <span class="required">*</span></label>
                <div class="col-md-9">
                    <select name="type" id="type" class="form-control" >
                        <option value="">{{trans('admin.all')}}</option>
                            <option value="{{\App\Modules\Generic\Classes\Constants::BannerGymType}}"
                                    @if(\App\Modules\Generic\Classes\Constants::BannerGymType == @$banner->type) selected @endif>{{trans('admin.gym')}}</option>
                            <option value="{{\App\Modules\Generic\Classes\Constants::BannerTrainerType}}"
                                    @if(\App\Modules\Generic\Classes\Constants::BannerTrainerType == @$banner->type) selected @endif>{{trans('admin.trainer')}}</option>
                            <option value="{{\App\Modules\Generic\Classes\Constants::BannerHomeType}}"
                                    @if(\App\Modules\Generic\Classes\Constants::BannerHomeType == @$banner->type) selected @endif>{{trans('admin.home')}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.url')}}</label>
                <div class="col-md-9">
                    <input id="url" value="{{ old('url', $banner->url) }}" dir="ltr"
                           name="url" type="url" class="form-control">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.phone')}}</label>
                <div class="col-md-9">
                    <input id="phone" value="{{ old('phone', $banner->phone) }}"
                           name="phone" type="text" class="form-control">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.lang')}}</label>
                <div class="col-md-9">
                    <select name="lang" id="lang" class="form-control" required>
                        <option value="">{{trans('admin.choose')}}</option>
                        <option value="ar"
                                @if("ar" == @$banner['lang']) selected @endif>{{trans('admin.arabic')}}</option>
                        <option value="en"
                                @if("en" == @$banner['lang']) selected @endif>{{trans('admin.english')}}</option>
                    </select>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.date_from')}}</label>
{{--                <div class="col-md-9">--}}
{{--                    <input id="date_from" value="{{ old('date_from',  \Carbon\Carbon::parse($banner->date_from)->format('Y-m-d')) }}"--}}
{{--                           name="date_from" type="date" class="form-control">--}}
{{--                </div>--}}
                <div class="col-md-3 col-sm-3">
                    <div class="input-group input-medium date " data-date-format="dd-mm-yyyy">
                        <input id="date_from" value="{{ old('date_from', @\Carbon\Carbon::parse($banner->date_from)->toDateString()) }}" readonly
                               name="date_from" type="text" class="form-control" required>
                        <span class="input-group-btn">
                            <button class="btn default" type="button">
                                <i class="fa fa-calendar"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">{{trans('admin.date_to')}}</label>
{{--                <div class="col-md-9">--}}
{{--                    <input id="date_to" value="{{ old('date_to', \Carbon\Carbon::parse($banner->date_to)->format('Y-m-d')) }}"--}}
{{--                           name="date_to" type="date" class="form-control">--}}
{{--                </div>--}}

                <div class="col-md-3 col-sm-3">
                    <div class="input-group input-medium date " data-date-format="dd-mm-yyyy">
                        <input id="date_to" value="{{ old('date_to', @\Carbon\Carbon::parse($banner->date_to)->toDateString()) }}" readonly
                               name="date_to" type="text" class="form-control" required>
                        <span class="input-group-btn">
                            <button class="btn default" type="button">
                                <i class="fa fa-calendar"></i>
                            </button>
                        </span>
                    </div>
                </div>

            </div>

            <div class="form-group col-md-12" style="clear:both;">
            <hr/>
            </div>
            <div class="form-group col-md-6" style="clear:both;">
                <label class="col-md-3 control-label">{{trans('admin.mobile')}}</label>
                <div class="col-md-3">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" value="1" name="is_mobile"
                                    {{ $banner->is_mobile?'checked':'' }}>
                            <span></span>
                        </label>
                    </div>
                </div>
                <label class="col-md-3 control-label">{{trans('admin.web')}}</label>
                <div class="col-md-3">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" value="1" name="is_web"
                                    {{ $banner->is_web ?'checked':'' }}>
                            <span></span>
                        </label>
                    </div>
                </div>

            </div>

            <div class="form-group col-md-6" style="clear:both;">
                <label class="col-md-3 control-label">{{trans('admin.disable')}}</label>
                <div class="col-md-9">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="hidden" name="deleted_at" value="">
                            <input type="checkbox" value="{{ date('Y-m-d') }}" name="deleted_at"
                                    {{ $banner->trashed()?'checked':'' }}>
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
        $( "#date_from" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"

        });
        $( "#date_to" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"

        });
        function readURL(input, id = '') {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#preview' + id).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#image").change(function () {
            readURL(this, 1);
        });
    </script>
@endsection
