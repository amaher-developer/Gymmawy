@extends('generic::layouts.user_form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('showUserFront') }}">{{trans('admin.my_info')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            {{ $title }}
        </li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('styles')
<style>
    .district_city_li {display: none;}
</style>
@endsection
@section('page_body')


    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.name')}}</label>
                <div class="col-md-9">
                    <input id="name" value="{{ old('name', @$user->name) }}"
                           name="name" type="text" class="form-control" >
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.phone')}}</label>
                <div class="col-md-9">
                    <input id="phone" value="{{ old('phone', @$user->phone) }}"
                           name="phone" type="text" class="form-control" >
                </div>
            </div>



            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.about_your')}}</label>
                <div class="col-md-9">
                 <textarea id="about"
                           name="about" class="form-control" rows="6">{{ old('about', @$user->about) }}</textarea>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.personal_image')}}</label>
                <div class="col-md-8">
                    <input id="image" value="{{ old('image', @$user->image) }}"
                           name="image" type="file" class="form-control" >
                    <br/>
                    <img id="preview" src="@if($user->image) {{$user->image}} @else {{asset('resources/assets/front/img/preview_icon.png')}} @endif" style="height: 120px;object-fit: contain;border: 1px solid #c2cad8;" alt="preview image" />

                </div>
                {{--@if(!empty(@$trainer->image))--}}
                {{--<label class="col-md-1 control-label">--}}
                {{--<a href="{{ @$trainer->image }}" class="fancybox-button" data-rel="fancybox-button">--}}
                {{--view--}}
                {{--</a>--}}
                {{--</label>--}}
                {{--@endif--}}
            </div>


            <div class="form-group col-md-12" style="padding: 20px 0;">
                <label class="col-md-3 control-label"> </label>
                <div class="col-md-9" >
                    <a href="{{route('editUserUpdatePassword')}}"> {{trans('global.reset_password')}} </a>
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
    {{--<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>    --}}
    {{--<script src="{{asset('resources/assets/admin/global/plugins/fancybox/source/jquery.fancybox.js')}}"></script>--}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
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
            $('#district_city_'+city_id).show();
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