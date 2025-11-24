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

@endsection
@section('page_body')


    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}

            <div style="clear: both"></div>
            <h5 class="form-section"><i class="fa fa-info-circle"></i> {{trans('admin.gym_info')}}</h5>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.gym_name')}} <span class="required">*</span></label>
                <div class="col-md-9">
                    <input id="name" value="{{ old('name', @$gym->name) }}"
                           name="name_{{$lang}}" type="text" class="form-control" required>
                </div>
            </div>



            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.about_gym')}} <span class="required">*</span></label>
                <div class="col-md-9">
                 <textarea id="description" required
                           name="description_{{$lang}}" class="form-control" rows="6">{{ old('description', @$gym->description) }}</textarea>
                </div>
            </div>


            <div class="form-group col-md-12 col-sm-12">
                <label class="col-md-3  col-sm-6  control-label text-right">{{trans('admin.gym_logo')}}</label>
                <div class="col-md-3  col-sm-6 ">
                    <input id="logo" value="{{ old('logo', @$gym->logo) }}"
                           name="logo" type="file" class="form-control" >
                    <br/>
                    <label for="logo" style="cursor: pointer;">
                        <img id="preview2" src="@if(@$gym->logo) {{$gym->logo}} @else {{asset('resources/assets/front/img/preview_icon.png')}} @endif" style="height: 120px;width: 120px;object-fit: contain;border: 1px solid #c2cad8;" alt="preview image" />
                    </label>
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
                           name="socials[website]" type="text" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.facebook')}}</label>
                <div class="col-md-9">
                    <div class="input-icon">
                        <i class="fa fa-facebook"></i>
                    <input id="facebook" value="{{ old('socials[facebook]', @$gym->socials['facebook']) }}" dir="ltr"
                           name="socials[facebook]" type="text" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.twitter')}}</label>
                <div class="col-md-9">
                    <div class="input-icon">
                        <i class="fa fa-twitter"></i>
                    <input id="twitter" value="{{ old('socials[twitter]', @$gym->socials['twitter']) }}" dir="ltr"
                           name="socials[twitter]" type="text" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.instagram')}}</label>
                <div class="col-md-9">
                    <div class="input-icon">
                        <i class="fa fa-instagram"></i>
                    <input id="instagram" value="{{ old('socials[instagram]', @$gym->socials['instagram']) }}" dir="ltr"
                           name="socials[instagram]" type="text" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.linkedin')}}</label>
                <div class="col-md-9">
                    <div class="input-icon">
                        <i class="fa fa-linkedin"></i>
                    <input id="linkedin" value="{{ old('socials[linkedin]', @$gym->socials['linkedin']) }}" dir="ltr"
                           name="socials[linkedin]" type="text" class="form-control" >
                    </div>
                </div>
            </div>


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
    <script>
        $("#logo").change(function () {
            readURL(this, 2);
        });
    </script>
@endsection