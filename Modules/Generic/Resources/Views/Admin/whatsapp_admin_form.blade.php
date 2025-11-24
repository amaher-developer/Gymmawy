@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listCity') }}">Cities</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('sub_styles')

    <style>
        .help-block {
            font-size: 12px;
        }

        .tiles .tile .tile-object > .name {
            margin-right: 0px;
            margin-left: 0px;
        }
    </style>

@endsection
@section('page_body')

    <!-- BEGIN PAGE CONTENT INNER -->


    <div class="form-bordered">
        <div class="form-group row col-md-12 alert alert-info">
            <label class="control-label col-md-3"
                   style="padding-top: 15px;">{{trans('sw.sms_your_balance')}}</label>
            <div class="col-md-3" style="padding-top: 15px;">
                <b>({{$consume_message_count}} / {{$max_messages}})</b> {{trans('sw.wa_message_per_month')}}
            </div>
            <div class="col-md-3" style="padding-top: 15px;">
                <b>({{$consume_user_count}} / {{$max_users}})</b> {{trans('sw.wa_user_per_day')}}
            </div>

            {{--            <div class="col-md-3"  style="padding-top: 15px;">--}}
            {{--                <a href="{{route('sw.listWALog')}}"><i class="fa fa-history"></i> {{trans('sw.wa_logs')}}</a>--}}
            {{--            </div>--}}
        </div>
    </div>
    <div class="clearfix" style="clear: both;float:none;">
        <hr/>
    </div>
    <!-- BEGIN PAGE CONTENT INNER -->
    <div class="page-content-inner">

        <div class="portlet-body form">
            <form role="form" action="{{route('storeWhatsapp')}}" method="post" onsubmit="return confirm('{{trans('admin.are_you_sure')}}');" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="form-body">

                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="col-md-2">{{trans('admin.country')}}
                            </label>
                            <div class="col-md-2">
                                <select type="text" class="form-control" name="countries_id[]" id="countries_id" multiple>
                                    @foreach($countries as $country)
                                        <option value="{{$country->country}}">{{$country->country}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-md-2">{{trans('admin.country_code')}}
                            </label>
                            <div class="col-md-2">
                               <input type="text" class="form-control" name="country_code" value="">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"><br/>
                        <hr/>
                        <br/></div>
                    <div class="form-group">
                        <label>{{trans('sw.clients')}}</label>
                        <div class="radio-list col-md-12">
                            <label class="radio-inline col-md-2">
                                <div class="radio" id="uniform-optionsRadios4" onclick="client_type(1)"><span
                                            class=""><input type="radio" name="client_type" id="optionsRadios4"
                                                            value="1" checked=""></span>
                                </div> {{trans('sw.new_entities')}}</label>
                            <label class="radio-inline col-md-2">
                                <div class="radio" id="uniform-optionsRadios5" onclick="client_type(2)"><span
                                            class="checked"><input type="radio" name="client_type" id="optionsRadios5"
                                                                   value="2"></span></div> {{trans('admin.soft')}}
                            </label>
                            <label class="radio-inline col-md-2">
                                <div class="radio" id="uniform-optionsRadios6" onclick="client_type(3)"><span
                                            class=""><input type="radio" name="client_type" id="optionsRadios6"
                                                            value="3"></span></div> {{trans('admin.mobile')}}
                            </label>
                            <label class="radio-inline col-md-2">
                                <div class="radio" id="uniform-optionsRadios7" onclick="client_type(4)"><span
                                            class=""><input type="radio" name="client_type" id="optionsRadios6"
                                                            value="3"></span></div> {{trans('admin.web')}}
                            </label>
                        </div>
                    </div>

                    <div class="clearfix padding-tb-20"></div>

                    <div class="form-group">
                        <label>{{trans('sw.phone')}}</label> <img style="display: none;" id="spinner"
                                                                  src="{{asset('resources/assets/admin/global/img/input-spinner.gif')}}">
                        <textarea class="form-control" rows="3" name="phones" id="phones" required></textarea>
                        <span class="help-block">01234567890, 01234567891, 01234567892 ...</span>
                    </div>

                    <div class="clearfix padding-tb-15"></div>

                    <div class="form-group">
                        <label>{{trans('sw.message')}}</label>
                        <textarea class="form-control" rows="5" name="message" id="message" required></textarea>
                    </div>

                    <div class="form-group custom-file">
                        <label for="image" class="custom-file-label">{{trans('admin.upload_image')}}</label><br/><br/>
                        <input type="file" id="image" name="image" class="custom-file-input"
                               style="display: block;margin-bottom: 16px;">

                        <label for="gym_image" style="cursor: pointer;">
                            <img id="preview1" src="https://gymmawy.com/resources/assets/front/img/logo/default.png"
                                 style="width: 300px;height: 79px; object-fit: contain;border: 1px solid #c2cad8;"
                                 alt="preview image"/>
                        </label>
                    </div>

                </div>

                <div class="form-actions" style="clear:both;">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-primary">{{trans('global.send')}}</button>
                            <input type="reset" class="btn default" value="{{trans('admin.reset')}}">
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>

@endsection
@section('sub_scripts')
    <script>
        function client_type(id) {
            var countries_id = $('#countries_id').val();
            if (id) {
                $('#spinner').show();
                $.ajax({
                    url: '{{route('phonesByAjax')}}',
                    type: 'GET',
                    data: {type: id, countries_id: countries_id},
                    success: function (response) {
                        document.getElementById('phones').value = response;
                        $('#spinner').hide();
                    },
                    error: function (request, error) {
                        swal("{{trans('admin.operation_failed')}}", "{{trans('admin.something_wrong')}}", "{{trans('admin.error')}}");
                        console.error("Request: " + JSON.stringify(request));
                        console.error("Error: " + JSON.stringify(error));
                        $('#spinner').hide();
                    }
                });
            }
        }

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
