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
    <a href="{{route('createBanner')}}" class="btn btn-lg btn-success">{{trans('admin.add')}} Banner</a>
     @if(request('trashed'))
            <a href="{{route('listBanner')}}" class="btn btn-lg btn-info">{{trans('admin.enabled')}}</a>
        @else
            <a href="{{route('listBanner')}}?trashed=1" class="btn btn-lg btn-danger">{{trans('admin.disabled')}}</a>
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
            <th>{{trans('admin.image')}}</th>
            <th>{{trans('admin.lang')}}</th>
            <th>{{trans('admin.type')}}</th>
            <th>{{trans('admin.category')}}</th>
            <th>{{trans('admin.date_from')}}</th>
            <th>{{trans('admin.date_to')}}</th>
            <th>{{trans('admin.actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($banners as $key=> $banner)
            <tr>
                <td> {{ $banner->id }}</td>
                <td> <img src="{{ $banner->image }}" style="width: 300px;height: 79px; object-fit: contain"></td>
                <td> {{ $banner['lang'] }}</td>
                <td> {{ $banner->type_name }}</td>
                <td> {{ $banner->category->name ?? trans('admin.all') }}</td>
                <td> {{\Carbon\Carbon::parse($banner->date_from)->toDateString()}} </td>
                <td> {{\Carbon\Carbon::parse($banner->date_to)->toDateString()}} </td>
                <td>
                    <a href="{{route('editBanner',$banner->id)}}" class="btn btn-xs yellow">
                        <i class="fa fa-edit"></i>
                    </a>
                    @if(request('trashed'))
                        <a title="{{trans('admin.enable')}}" href="{{route('deleteBanner',$banner->id)}}" class="confirm_delete btn btn-xs green">
                            <i class="fa fa-check-circle"></i>
                        </a>
                    @else
                        <a title="{{trans('admin.disable')}}" href="{{route('deleteBanner',$banner->id)}}" class="confirm_delete btn btn-xs red">
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
                {!! $banners->appends($search_query)->render()  !!}
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
