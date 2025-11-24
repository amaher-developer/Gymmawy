@extends('generic::layouts.list')
@section('list_title') {{ @$title }} @endsection
@section('breadcrumb')
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('listTrainingClient') }}">{{trans('admin.training_clients')}}</a>
                <i class="fa fa-circle"></i>
            </li>
        <li>
            <a href="#">{{ $title }}</a>
        </li>
        </ul>
@endsection
@section('list_add_button')
    <a href="{{route('createTrainingPlan')}}" class="btn btn-lg btn-success">{{trans('admin.add')}} plan</a>
     @if(request('trashed'))
            <a href="{{route('listTrainingPlan')}}" class="btn btn-lg btn-info">{{trans('admin.enabled')}}</a>
        @else
            <a href="{{route('listTrainingPlan')}}?trashed=1" class="btn btn-lg btn-danger">{{trans('admin.disabled')}}</a>
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
                    <th>{{trans('admin.title')}}</th>
                    <td><input id="title" value="{{ request('title')}}" name="title" class="form-control"
                               type="text" placeholder="{{trans('admin.title')}}"/></td>
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
            <th>Title</th>
            <th>Date</th>
            <th>{{trans('admin.actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($plans as $key=> $plan)
            <tr>
                <td> {{ $plan->id }}</td>
                <td> {{ $plan->title }}</td>
                <td> {{ \Carbon\Carbon::parse($plan->created_at)->toDateString() }}</td>
                <td>
                    <a href="{{route('editTrainingPlan', $plan->id)}}" class="btn btn-xs yellow">
                        <i class="fa fa-edit"></i>
                    </a>
                    @if(request('trashed'))
                        <a title="{{trans('admin.enable')}}" href="{{route('deleteTrainingPlan', $plan->id)}}" class="confirm_delete btn btn-xs green">
                            <i class="fa fa-check-circle"></i>
                        </a>
                    @else
                        <a title="{{trans('admin.disable')}}" href="{{route('deleteTrainingPlan', $plan->id)}}" class="confirm_delete btn btn-xs red">
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
                {!! $plans->appends($search_query)->render()  !!}
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


    </script>

@endsection
