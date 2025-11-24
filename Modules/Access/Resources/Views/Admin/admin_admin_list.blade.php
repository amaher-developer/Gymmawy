@extends('generic::layouts.list')
@section('list_title') {{ @$title }} @endsection
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
    <a href="{{route('createAdmin')}}" class="btn btn-lg btn-success">Add User</a>
@endsection
@section('page_body')
    <table class="table table-striped table-bordered table-hover" id="sample_3">
        <thead>
        <tr class="">
            <th>#</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $key => $user)
            <tr>
                <td> {{$key + 1}}</td>
                <td> {{$user->name}}</td>
                <td>
                    <a href="{{route('editAdmin',$user->id)}}" class="btn btn-xs yellow">
                        <i class="fa fa-edit"></i>
                    </a>
                    {{--<a href="{{route('deleteUser',$user->id)}}" class="confirm_delete btn btn-xs red">--}}
                    {{--<i class="fa fa-times"></i>--}}
                    {{--</a>--}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
    @parent
    <script>

        $('#sample__').DataTable({
            paging: false
        });

        $(document).on('click', '.remove_filter', function (event) {
            event.preventDefault();
            var filter = $(this).attr('id');
            $("#input_" + filter).val('');
            $("#filter_form").submit();
        });


        $(document).on('click', '.get_edara_order', function (event) {
            event.preventDefault();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                cache: false,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    swal({
                        title: 'Edara Order',
                        text: JSON.stringify(response),
                        showCloseButton: true,
                        confirmButtonText: 'Close!',
                        showLoaderOnConfirm: true,
                        width: '80%'
                    })
                },
                error: function (request, error) {
                    swal("Operation failed", "Order Not found in 'Edara'.", "error");
                    console.error("Request: " + JSON.stringify(request));
                    console.error("Error: " + JSON.stringify(error));
                }
            });
        });
    </script>
@endsection
