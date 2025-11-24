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

@endsection
@section('styles')
    <style>
        .text {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 5; /* start showing ellipsis when 3rd line is reached */
            white-space: pre-wrap; /* let the text wrap preserving spaces */
        }
    </style>
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
            <th>{{trans('admin.type')}}</th>
            <th>{{trans('admin.contact_info')}}</th>
            <th>{{trans('admin.message')}}</th>
            <th>{{trans('admin.date')}}</th>
            <th>{{trans('admin.actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($contacts as $key=> $contact)
            <tr>
                <td> {{ $contact->id }}</td>
                <td> @if($contact->type == 0) {!! '<i class="fa fa-globe"></i> '. trans('admin.web') !!} @elseif($contact->type == 1) {!! '<i class="fa fa-cogs"></i> ' .trans('admin.soft') !!} @else {!! '<i class="fa fa-mobile"></i> ' .trans('admin.mobile') !!} @endif</td>
                <td> <ul>
                        <li style="line-height: 26px"><b>{{trans('admin.name')}}:</b> {{ $contact->name }}</li>
                        <li style="line-height: 26px"><b>{{trans('admin.email')}}:</b> {{ $contact->email }}</li>
                        <li style="line-height: 26px"><b>{{trans('admin.phone')}}:</b> {{ $contact->phone }}</li>
                        <li style="line-height: 26px"><b>{{trans('admin.country')}}:</b> {{ $contact->country }}</li>

                    </ul></td>
                <td> <p class="text" title="{{$contact->msg}}">{!! $contact->msg  !!}</p></td>
                <td> {{ \Carbon\Carbon::parse($contact->created_at)->toDateString()  }}</td>
                <th>@if(request('trashed'))
                        <a title="{{trans('admin.enable')}}" href="{{route('deleteContact',$contact->id)}}" class="confirm_delete btn btn-xs green">
                            <i class="fa fa-check-circle"></i>
                        </a>
                    @else
                        <a title="{{trans('admin.disable')}}" href="{{route('deleteContact',$contact->id)}}" class="confirm_delete btn btn-xs red">
                            <i class="fa fa-times"></i>
                        </a>
                    @endif</th>
            </tr>
        @endforeach
        </tbody>
    </table>
        </div>
     <div class="col-lg-5 col-md-5 col-md-offset-5">
                {!! $contacts->appends($search_query)->render()  !!}
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
