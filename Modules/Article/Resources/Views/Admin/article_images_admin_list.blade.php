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
@section('page_body')
    <div class="row">

        <form method="post" action="{{route('uploadArticleImage')}}" role="form" enctype="multipart/form-data">
            {{csrf_field()}}
            <table class="table table-striped table-bordered table-hover ">
                <tbody>
                <tr>
                    <th>{{trans('admin.image')}}</th>
                    <td><input id="image"  name="image" class="form-control"
                               type="file" placeholder="{{trans('admin.image')}}"/></td>
                </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-offset-9 col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn green form-control">{{trans('admin.submit')}}</button>
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

    <table class="table table-striped table-bordered table-hover" >
        <thead>
        <tr class="">
            <th>id</th>
            <th>{{trans('admin.image')}}</th>
            <th>{{trans('admin.image')}}</th>
            <th>{{trans('admin.actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($images as $key=> $image)
            <tr>
                <td> {{ $image->id }}</td>
                <td> {{ $image->image_with_path }}</td>
                <td> <img src="{{ $image->image_with_path }}" style="height: 120px;width: 120px"> </td>
                <td>
                        <a title="{{trans('admin.disable')}}" href="{{route('deleteArticleImage',$image->id)}}" class="confirm_delete btn btn-xs red">
                            <i class="fa fa-times"></i>
                        </a>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
     <div class="col-lg-5 col-md-5 col-md-offset-5">
                {!! $images->render()  !!}
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
