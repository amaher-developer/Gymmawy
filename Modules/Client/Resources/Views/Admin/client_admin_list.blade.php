@extends('generic::layouts.list')
@section('list_title') {{ @$title }} @endsection
@section('breadcrumb')
        <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
        <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">{{ $title }}</a>
        </li>
        </ul>
@endsection
@section('list_add_button')
    <a href="{{route('createClient')}}" class="btn btn-lg btn-success">{{trans('admin.add')}} Client</a>
     @if(request('trashed'))
            <a href="{{route('listClient')}}" class="btn btn-lg btn-info">{{trans('admin.enabled')}}</a>
        @else
            <a href="{{route('listClient')}}?trashed=1" class="btn btn-lg btn-danger">{{trans('admin.disabled')}}</a>
        @endif
            <a href="" url="{{request()->fullUrlWithQuery(['export'=>1])}}" id="export" class="btn red btn-outline"><i class="icon-paper-clip"></i> {{trans('admin.export')}}</a>
@endsection
@section('page_body')
    <div class="row">

        <button class="btn btn-info filter_trigger_button" style="margin-bottom: 10px">{{trans('admin.show_hide_filters')}}</button>

        <form action="" id="filter_form">
            <table class="table table-striped table-bordered table-hover ">
                <tbody>
                <tr>
                    <th>{{trans('admin.id')}}</th>
                    <td><input id="id" value="{{ request('id')}}" name="id" class="form-control"
                               type="number" placeholder="{{trans('admin.id')}}"/></td>
                </tr>
                <tr>
                    <th>{{trans('admin.phone')}}</th>
                    <td><input id="phone" value="{{ request('phone')}}" name="phone" class="form-control"
                               type="text" placeholder="{{trans('admin.phone')}}"/></td>
                </tr>
                <tr>
                    <th>{{trans('admin.status')}}</th>
                    <td><select name="status" class="form-control">
                            <option value="">{{trans('admin.status')}}</option>
                            <option value="1" @if(request('status') == 1) selected="" @endif>{{trans('admin.active')}}</option>
                            <option value="0" @if((request('status') == 0)) selected="" @endif>{{trans('admin.inactive')}}</option>
                        </select></td>
                </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-offset-9 col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn green form-control">{{trans('admin.apply')}}</button>
                    </div>
                </div>
            </div>


        </form>

        <div class="row">
            <div class="col-md-6">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <th>{{trans('admin.total_count')}}</th>
                        <td>{{ $total }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
<div class="table-responsive border-top userlist-table ">
    <table class="table card-table table-striped table-vcenter text-nowrap mb-0 " >
        <thead>
        <tr class="">
            <th>id</th>
            <th>Gym</th>
            <th>Status</th>
            <th>Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Start Date</th>
            <th>Expire Date</th>
{{--            <th>Token</th>--}}
            <th>Last Migrate</th>
{{--            <th>SMS Balance</th>--}}
{{--            <th>SMS Sender ID</th>--}}
{{--            <th>Date From</th>--}}
{{--            <th>Date To</th>--}}
            <th>{{trans('admin.actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($clients as $key=> $client)
            <tr>
                <td> {{ $client->id }}</td>
                <td> {{ $client->sms_sender_id }}</td>
                <td> @if(\Carbon\Carbon::parse($client->date_to)->toDateString() >= \Carbon\Carbon::now()->toDateString()) <i class="fa fa-check" style="color: lightgreen"></i> @else <i class="fa fa-times"  style="color: red"></i> @endif</td>
                <td> {{ $client->name }}</td>
                <td> {{ $client->address }}</td>
                <td> {{ $client->phone }}</td>
                <td> {{ \Carbon\Carbon::parse($client->date_from)->toDateString() }}</td>
                <td style="font-weight: bold; @if(\Carbon\Carbon::parse($client->date_to)->toDateString() < \Carbon\Carbon::now()->toDateString()) color: red; @endif"> {{ \Carbon\Carbon::parse($client->date_to)->toDateString() }}</td>
{{--                <td> {{ $client->token }}</td>--}}
{{--                <td> {{ @$client->gym->gym_brand->name }}</td>--}}
{{--                <td> {{ $client->sms_balance }}</td>--}}
                <td> {{ $client->last_migrate }}</td>
{{--                <td> {{ \Carbon\Carbon::parse($client->date_from)->toDateString() }}</td>--}}
{{--                <td>{{ \Carbon\Carbon::parse($client->date_to)->toDateString() }}</td>--}}
                <td>
                    <a onclick="migrate_refresh({{$client->id}})" class="btn btn-xs yellow">
                        <i class="fa fa-refresh"></i>
                    </a>
                    <a href="{{route('editClient',$client->id)}}" class="btn btn-xs yellow">
                        <i class="fa fa-edit"></i>
                    </a>
                    @if(request('trashed'))
                        <a title="{{trans('admin.enable')}}" href="{{route('deleteClient',$client->id)}}" class="confirm_delete btn btn-xs green">
                            <i class="fa fa-check-circle"></i>
                        </a>
                    @else
                        <a title="{{trans('admin.disable')}}" href="{{route('deleteClient',$client->id)}}" class="confirm_delete btn btn-xs red">
                            <i class="fa fa-times"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
     <div class="col-lg-5 col-md-5 col-md-offset-5">
                {!! $clients->appends($search_query)->render()  !!}
            </div>
        </div>
@endsection

@section('scripts')
    @parent

    <script>

        $(document).on('click', '#export', function (event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('url'),
                cache: false,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    var a = document.createElement("a");
                    a.href = response.file;
                    a.download = response.name;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                },
                error: function (request, error) {
                    swal("Operation failed", "Something went wrong.", "error");
                    console.error("Request: " + JSON.stringify(request));
                    console.error("Error: " + JSON.stringify(error));
                }
            });

        });

        $("#filter_form").slideUp();
        $(".filter_trigger_button").click(function () {
            $("#filter_form").slideToggle(300);
        });

        $(document).on('click', '.remove_filter', function (event) {
            event.preventDefault();
            var filter = $(this).attr('id');
            $("#" + filter).val('');
            $("#filter_form").submit();
        });

        function migrate_refresh(id){
            // $('#fingerprint_refresh').hide().after('<div class="col-md-12"><div class="loader"></div></div>');
            let url = "{{route('migrateClient', ':id')}}";
            url = url.replace(":id", id);
            $.ajax({
                url: url,
                cache: false,
                type: 'GET',
                dataType: 'text',
                data: {id: id},
                success: function (response) {
                    alert(response);
                    {{--if(response === '1') {--}}
                    {{--    $('#fingerprint_refresh').after(--}}
                    {{--        '<button class="btn btn-success btn-block rounded-3 " disable>' +--}}
                    {{--        ' <i class="fa fa-check mx-1"></i> ' +--}}
                    {{--        "{{trans('admin.successfully_processed')}}" +--}}
                    {{--        '</button>');--}}
                    {{--}else{--}}
                    {{--    $('#fingerprint_refresh').after(--}}
                    {{--        '<button class="btn btn-danger btn-block rounded-3 " disable>' +--}}
                    {{--        ' <i class="fa fa-times mx-1"></i> ' +--}}
                    {{--        "{{trans('sw.zk_not_connect')}}" +--}}
                    {{--        '</button>');--}}
                    {{--}--}}
                    {{--$('#fingerprint_refresh').remove();--}}
                    {{--$('.loader').hide();--}}


                },
                error: function (request, error) {
                    swal("Operation failed", "Something went wrong.", "error");
                    console.error("Request: " + JSON.stringify(request));
                    console.error("Error: " + JSON.stringify(error));
                }
            });

        }
    </script>

@endsection
