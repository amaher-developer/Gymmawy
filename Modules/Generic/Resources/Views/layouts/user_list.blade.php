@extends('generic::Front.layouts.user_master')
@section('styles')
<style>
    .table-striped thead tr th {
        padding-right: 2%;
    }
</style>
@endsection
@section('gym_shortcuts')

@endsection


@section('content')
    <div class="page-fixed-main-content">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-notebook font-green"></i>
                            <span class="caption-subject font-dark sbold uppercase">
                                @yield('list_title')
                            </span>
                        </div>
                        <div class="actions">
                            @yield('list_add_button')
                            {{--<div class="btn-group trigger-tools-group">--}}
                                {{--<a class="btn red btn-outline" href="javascript:;" data-toggle="dropdown">--}}
                                    {{--<i class="fa fa-share"></i>--}}
                                    {{--<span class="hidden-xs"> Trigger Tools </span>--}}
                                    {{--<i class="fa fa-angle-down"></i>--}}
                                {{--</a>--}}
                                {{--<ul class="dropdown-menu pull-right" id="sample_3_tools">--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;" data-action="0" class="tool-action">--}}
                                            {{--<i class="icon-printer"></i> Print</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;" data-action="1" class="tool-action">--}}
                                            {{--<i class="icon-check"></i> Copy</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;" data-action="2" class="tool-action">--}}
                                            {{--<i class="icon-doc"></i> PDF</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;" data-action="3" class="tool-action">--}}
                                            {{--<i class="icon-paper-clip"></i> Excel</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;" data-action="4" class="tool-action">--}}
                                            {{--<i class="icon-cloud-upload"></i> CSV</a>--}}
                                    {{--</li>--}}
                                    {{--<li class="divider"></li>--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;" data-action="5" class="tool-action">--}}
                                            {{--<i class="icon-refresh"></i> Reload</a>--}}
                                    {{--</li>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            @yield('page_body')
                        </div>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@stop

@section('scripts')
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        $(document).on('click', '.confirm_delete', function (event) {
            var tr = $(this).parent().parent();
            event.preventDefault();
            url = $(this).attr('href');
            swal({
                title: "{{trans('admin.are_you_sure')}}",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{trans('admin.yes')}}",
                cancelButtonText: "{{trans('admin.no_cancel')}}",
                showLoaderOnConfirm: true,
//                ,closeOnConfirm: false,
//                closeOnCancel: false
                preConfirm: function (isConfirm) {
                    return new Promise(function (resolve, reject) {
                        setTimeout(function () {
                            if (isConfirm) {
                                $.ajax({
                                    url: url,
                                    type: 'GET',
                                    success: function () {
                                        swal("{{trans('completed')}}", "{{trans('admin.completed_successfully')}}", "success");

                                        tr.remove();
                                    },
                                    error: function (request, error) {
                                        swal("{{trans('operation_failed')}}", "{{trans('admin.something_wrong')}}", "error");
                                        console.error("Request: " + JSON.stringify(request));
                                        console.error("Error: " + JSON.stringify(error));
                                    }
                                });
                            } else {
                                swal("{{trans('admin.cancelled')}}", "{{trans('admin.everything_still')}}", "info");
                            }
//            });
                        }, 2000)
                    })
                },
                allowOutsideClick: false
            }).then(function (isConfirm) {

            });

//                    .then(function () {
//
        });



    </script>
@endsection