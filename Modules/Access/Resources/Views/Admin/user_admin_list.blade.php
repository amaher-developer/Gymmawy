@extends('generic::layouts.list')
@section('list_title') {{ @$title }} @endsection
@section('styles')
    <link href="{{asset('resources/assets/admin/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}"
          rel="stylesheet"
          type="text/css"/>
    <style>
        .rounded-circle {
            border-radius: 50% !important;
        }
        .avatar-md {
            width: 48px !important;
            height: 48px !important;
            font-size: 24px !important;
        }
    </style>
@endsection
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">Dashboard</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('list_add_button')
    <a href="{{route('createUser')}}" class="btn btn-lg btn-success">Add Customer</a>
@endsection
@section('page_body')
    <button class="btn btn-info filter_trigger_button">Filters</button>

    <form action="" id="filter_form">
        <div class="row">
            <div class="col-lg-2 col-md-2">
                <label class="control-label">User_id</label>
                <input id="input_user_id" value="{{ request('user_id') }}" name="user_id" class="form-control"
                       type="number" placeholder="User_id"/>
            </div>
            <div class="col-lg-2 col-md-2">
                <label class="control-label">Name</label>
                <input id="input_name" value="{{ request('name') }}" name="name" class="form-control"
                       type="text" placeholder="Name"/>
            </div>
            <div class="col-lg-2 col-md-2">
                <label class="control-label">Phone</label>
                <input id="input_phone" value="{{ request('phone') }}" name="phone" class="form-control"
                       type="text" placeholder="Phone"/>
            </div>
            <div class="col-lg-2 col-md-2">
                <label class="control-label">Email</label>
                <input id="input_email" value="{{ request('email') }}" name="email" class="form-control"
                       type="text" placeholder="Email"/>
            </div>

        </div>

        <div class="row">
            <div class="col-md-offset-9 col-md-3">
                <div class="form-group">
                    <button type="submit" class="btn green form-control">Apply</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <th>Customer count</th>
                        <td>{{ $users_count }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <table class="table table-striped table-bordered table-hover ">
        <thead>
        <tr class="">
            <th>#</th>
            <th  scope="col">Name</th>
            <th  scope="col"></th>
            <th>Mobile</th>
            <th>Login</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $key => $user)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    {{ $user->name }}
                </td>
                <td>
                    <img alt="avatar" class="rounded-circle avatar-md mr-2" src="{{ $user->image }}">
                </td>
                <td>{{ $user->phone }}</td>
                <td>
                    @if( $user->google_id) <i class="fa fa-google-plus"></i>@endif
                    @if( $user->apple_id) <i class="fa fa-apple"></i>@endif
                    @if( $user->facebook_id) <i class="fa fa-facebook"></i>@endif
                    @if( $user->twitter_id) <i class="fa fa-twitter"></i>@endif
                    @if( $user->instagram_id) <i class="fa fa-instagram"></i>@endif
                  </td>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->toDateString() }}</td>

                <td>
                    <a href="{{ route('editUser',$user->id) }}" class="btn btn-xs yellow">
                        <i class="fa fa-edit"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="col-lg-5 col-md-5 col-md-offset-5">
        {{ ($paginated) ? $users->links() : '' }}
    </div>


@endsection


@section('scripts')
    @parent

    <script>
        var export_btn = $('#export_btn').html();
        $('.trigger-tools-group').html(export_btn);
        $(document).on('click', '#export', function (event) {
            event.preventDefault();
            var url = document.location.href + "?export";
            if (document.location.href.indexOf('?') >= 0) {
                url = document.location.href + "&export";
            }
            $.ajax({
                url: url,
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


        $('#sample_3').dataTable({
            paging: false,
            info: false,
            searching: false,
            lengthChange: false
        });

        $("#filter_form").slideUp();
        $(".filter_trigger_button").click(function () {
            $("#filter_form").slideToggle(300);
//            toggleClass('hidden',300);
        });

        // $(document).on('click', '.remove_filter', function (event) {
        //     event.preventDefault();
        //     var filter = $(this).attr('id');
        //     $("#input_" + filter).val('');
        //     $("#filter_form").submit();
        // });


    </script>


    <script src="{{asset('resources/assets/admin/global/plugins/moment.min.js')}}"
            type="text/javascript"></script>
@endsection
