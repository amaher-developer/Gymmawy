@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listClient') }}">Clients</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection

@section('sub_styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection
@section('form_title') {{ @$title }} @endsection
@section('page_body')
    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Name</label>
                <div class="col-md-9">
                    <input id="name" value="{{ old('name', $client->name) }}"
                           name="name" type="text" class="form-control">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Phone</label>
                <div class="col-md-9">
                    <input id="phone" value="{{ old('phone', $client->phone) }}"
                           name="phone" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Address</label>
                <div class="col-md-9">
                    <input id="address" value="{{ old('address', $client->address) }}"
                           name="address" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Status</label>
                <div class="col-md-9">
                    <input id="status" value="{{ old('status', $client->status) }}"
                           name="status" type="number" class="form-control">
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Gym ID</label>
                <div class="col-md-9">
                    <input id="gym_id" value="{{ old('gym_id', $client->gym_id) }}"
                           name="gym_id" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Token</label>
                <div class="col-md-9">
                    <input id="token" value="{{ old('token', $client->token) ?? $token }}"
                           name="token" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">SMS Balance</label>
                <div class="col-md-9">
                    <input id="sms_balance" value="{{ old('sms_balance', $client->sms_balance) }}"
                           name="sms_balance" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">SMS Sender ID</label>
                <div class="col-md-9">
                    <input id="sms_sender_id" value="{{ old('sms_sender_id', $client->sms_sender_id) }}"
                           name="sms_sender_id" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Software URL</label>
                <div class="col-md-9">
                    <input id="sw_url" value="{{ old('sw_url', $client->sw_url) }}"
                           name="sw_url" type="text" class="form-control">
                </div>
            </div>

            <div class="clearfix"></div>

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
    </script>
@endsection
