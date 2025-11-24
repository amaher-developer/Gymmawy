@extends('generic::layouts.form')
@section('form_title') {{$module->name}} Module @endsection

@section('page_body')
    <div class="row generation-options">
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 " style="margin: 10px">
            <a class=" btn btn-info sbold" data-toggle="modal" href="#add-controller"> Create Controller </a>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 " style="margin: 10px">
            <a class=" btn btn-info sbold" data-toggle="modal" href="#add-request"> Create Request </a>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 " style="margin: 10px">
            <a class=" btn btn-info sbold" data-toggle="modal" href="#add-model"> Create Model </a>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 " style="margin: 10px">
            <a class=" btn btn-info sbold" data-toggle="modal" href="#add-migration"> Create migration </a>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 " style="margin: 10px">
            <a class=" btn btn-info sbold" data-toggle="modal" href="#add-seeder"> Create Seeder </a>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 " style="margin: 10px">
            <a class=" btn btn-info sbold" data-toggle="modal" href="#add-middleware"> Create Middleware </a>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 " style="margin: 10px">
            <a class=" btn btn-info sbold" data-toggle="modal" href="#add-form"> Create Sub Module </a>
        </div>
        @if($migrate)
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 " style="margin: 10px">
                <a class=" btn btn-info sbold" data-toggle="modal" href="#run-migration"> Run Migration</a>
            </div>
        @endif
        @if($rollback)
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 " style="margin: 10px">
                <a class=" btn btn-info sbold" data-toggle="modal" href="#rollback-migration"> Rollback Migration</a>
            </div>
        @endif
        @if($rollback)
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 " style="margin: 10px">
                <a class=" btn btn-info sbold" data-toggle="modal" href="#reset-migration"> Reset Migration</a>
            </div>
        @endif
        @if($rollback)
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 " style="margin: 10px">
                <a class=" btn btn-info sbold" data-toggle="modal" href="#refresh-migration"> Refresh Migration</a>
            </div>
        @endif
    </div>
    <table class="table table-bordered table-hover" id="sample_3">
        <tr>
            <td colspan="2">

            </td>
        </tr>
        <tr>
            <th width="20%">Name</th>
            <td>{{$module->name}}</td>
        </tr>
        <tr>
            <th>slug</th>
            <td>{{$module->slug}}</td>
        </tr>
        <tr>
            <th>description</th>
            <td>{{$module->description}}</td>
        </tr>
    </table>

    <div id="add-form" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add New Sub Module</h4>
                </div>
                <form method="post" action="{{route('addSubModule',$module->slug)}}" class="form-horizontal"
                      role="form">
                    <div class="modal-body">
                        <div class="form-body">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-md-3 control-label">Sub Module Name</label>
                                <div class="col-md-6">
                                    <input pattern="[a-zA-Z0-9_ ]+" required name="name" type="text"
                                           class="form-control" placeholder="post,product,category,city,country">
                                </div>
                            </div>
                            <table id="form-generation-table" class="table table-bordered table-hover dataTable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Nullable</th>
                                    @if(env('MULTI_LANG')==true)
                                        <th>Multi Language</th>
                                    @endif
                                    <th>Show in List?</th>
                                    <th>DB Only?</th>
                                    <th>remove?</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input pattern="[a-zA-Z0-9_]+" name="fields[0]" required class="form-control">
                                    </td>
                                    <td>
                                        <select name="types[0]" required class="form-control">
                                            {{--<option value="1">Text Input</option>--}}
                                            {{--<option value="2">Email Input</option>--}}
                                            {{--<option value="3">Number Input</option>--}}
                                            {{--<option value="4">URL Input</option>--}}
                                            {{--<option value="5">File Input</option>--}}
                                            {{--<option value="6">Text Area</option>--}}
                                            {{--<option value="7">Select</option>--}}
                                            <option value="1">Text Input</option>
                                            <option value="2">Email Input</option>
                                            <option value="3">Integer</option>
                                            <option value="10">Double</option>
                                            <option value="4">File Input</option>
                                            <option value="5">Text Area</option>
                                            <option value="6">Select</option>
                                            <option value="7">Radio</option>
                                            <option value="8">Checkbox</option>
                                            <option value="9">Date</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="nullable[0]" value="0" checked hidden>
                                        <input type="checkbox" name="nullable[0]" value="1" class="form-control">
                                    </td>
                                    @if(env('MULTI_LANG')==true)
                                        <td>
                                            <input type="checkbox" name="multi_language[0]" value="0" checked hidden>
                                            <input type="checkbox" name="multi_language[0]" value="1"
                                                   class="form-control">
                                        </td>
                                    @endif
                                    <td>
                                        <input type="checkbox" name="show_in_list[0]" value="0" checked hidden>
                                        <input type="checkbox" name="show_in_list[0]" value="1" class="form-control">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="db_only[0]" value="0" checked hidden>
                                        <input type="checkbox" name="db_only[0]" value="1" class="form-control">
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                        <button id="add_new_row" type="button" class="btn dark btn-outline">Add New Row</button>

                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
                        <button type="submit" class="btn green">Submit</button>
                    </div>
                </form>

                <table id="table-row-template" class="hidden">
                    <tr>
                        <td><input pattern="[a-zA-Z0-9_]+" name="fields[]" required class="form-control"></td>
                        <td>
                            <select name="types[]" required class="form-control">
                                <option value="1">Text Input</option>
                                <option value="2">Email Input</option>
                                <option value="3">Integer</option>
                                <option value="10">Double</option>
                                <option value="4">File Input</option>
                                <option value="5">Text Area</option>
                                <option value="6">Select</option>
                                <option value="7">Radio</option>
                                <option value="8">Checkbox</option>
                                <option value="9">Date</option>
                            </select>
                        </td>
                        <td>
                            <input type="checkbox" name="nullable[]" value="0" checked hidden>
                            <input type="checkbox" name="nullable[]" value="1" class="form-control">
                        </td>
                        @if(env('MULTI_LANG')==true)
                            <td>
                                <input type="checkbox" name="multi_language[]" value="0" checked hidden>
                                <input type="checkbox" name="multi_language[]" value="1"
                                       class="form-control">
                            </td>
                        @endif
                        <td>
                            <input type="checkbox" name="show_in_list[]" value="0" checked hidden>
                            <input type="checkbox" name="show_in_list[]" value="1" class="form-control">
                        </td>
                        <td>
                            <input type="checkbox" name="db_only[]" value="0" checked hidden>
                            <input type="checkbox" name="db_only[]" value="1" class="form-control">
                        </td>
                        <td class="text-center">
                            <span class="fa fa-times remove_row" title="Delete"></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div id="add-controller" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add New Controller</h4>
                </div>
                <form method="post" action="{{route('addController',$module->slug)}}" class="form-horizontal"
                      role="form">
                    <div class="modal-body">
                        <div class="form-body">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-md-3 control-label">Controller Name</label>
                                <div class="col-md-9">
                                    <input pattern="[a-z A-Z 0-9]+" required value="" name="name" type="text"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Section</label>
                                <div class="col-md-9">
                                    <div class="mt-radio-list">
                                        <label class="mt-radio">
                                            <input required type="radio" name="section" id="optionsRadios22"
                                                   value="1"> Admin Panel
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" name="section" id="optionsRadios23"
                                                   value="2"> Api
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" name="section" id="optionsRadios24"
                                                   value="3"> Front
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
                        <button type="submit" class="btn green">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="add-request" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add New Request</h4>
                </div>
                <form method="post" action="{{route('addRequest',$module->slug)}}" class="form-horizontal" role="form">
                    <div class="modal-body">
                        <div class="form-body">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-md-3 control-label">Request Name</label>
                                <div class="col-md-9">
                                    <input pattern="[a-zA-Z0-9 ]+" required value="" name="name" type="text"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
                        <button type="submit" class="btn green">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="add-model" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add New Model</h4>
                </div>
                <form method="post" action="{{route('addModel',$module->slug)}}" class="form-horizontal" role="form">
                    <div class="modal-body">
                        <div class="form-body">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-md-3 control-label">Model Name</label>
                                <div class="col-md-9">
                                    <input pattern="[a-zA-Z0-9 ]+" required value="" name="name" type="text"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
                        <button type="submit" class="btn green">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="add-migration" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add New Migration</h4>
                </div>
                <form method="post" action="{{route('addMigration',$module->slug)}}" class="form-horizontal"
                      role="form">
                    <div class="modal-body">
                        <div class="form-body">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-md-3 control-label">Migration Name</label>
                                <div class="col-md-9">
                                    <input pattern="[a-zA-Z0-9_ ]+" placeholder="optional" value="" name="name"
                                           type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Table Name</label>
                                <div class="col-md-9">
                                    <input pattern="[a-zA-Z0-9_ ]+" required value="" name="table_name" type="text"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Table</label>
                                <div class="col-md-9">
                                    <div class="mt-radio-list">
                                        <label class="mt-radio">
                                            <input required type="radio" name="type" id="optionsRadios22"
                                                   value="1"> New Table
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" name="type" id="optionsRadios23"
                                                   value="2"> Existing Table
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
                        <button type="submit" class="btn green">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="add-middleware" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add New Middleware</h4>
                </div>
                <form method="post" action="{{route('addMiddleware',$module->slug)}}" class="form-horizontal"
                      role="form">
                    <div class="modal-body">
                        <div class="form-body">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-md-3 control-label">Middleware Name</label>
                                <div class="col-md-9">
                                    <input pattern="[a-zA-Z0-9_ ]+" required value="" name="name" type="text"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
                        <button type="submit" class="btn green">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="add-seeder" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add New Seeder</h4>
                </div>
                <form method="post" action="{{route('addSeeder',$module->slug)}}" class="form-horizontal" role="form">
                    <div class="modal-body">
                        <div class="form-body">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-md-3 control-label">Seeder Name</label>
                                <div class="col-md-9">
                                    <input pattern="[a-zA-Z0-9_ ]+" required value="" name="name" type="text"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
                        <button type="submit" class="btn green">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="run-migration" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Run Migration</h4>
                </div>
                <form method="post" action="{{route('runMigration',$module->slug)}}" class="form-horizontal"
                      role="form">
                    <div class="modal-body">
                        <div class="form-body">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-md-12 control-label">Are You Sure ,Run Migration</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
                        <button type="submit" class="btn green">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="reset-migration" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Reset Migration</h4>
                </div>
                <form method="post" action="{{route('resetMigration',$module->slug)}}" class="form-horizontal"
                      role="form">
                    <div class="modal-body">
                        <div class="form-body">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-md-12 control-label">Are You Sure ,Reset Migration</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
                        <button type="submit" class="btn green">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="refresh-migration" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Refresh Migration</h4>
                </div>
                <form method="post" action="{{route('refreshMigration',$module->slug)}}" class="form-horizontal"
                      role="form">
                    <div class="modal-body">
                        <div class="form-body">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-md-12 control-label">Are You Sure ,Refresh Migration</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
                        <button type="submit" class="btn green">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="rollback-migration" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Rollback Migration</h4>
                </div>
                <form method="post" action="{{route('rollbackMigration',$module->slug)}}" class="form-horizontal"
                      role="form">
                    <div class="modal-body">
                        <div class="form-body">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-md-12 control-label">Are You Sure ,Rollback Migration</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
                        <button type="submit" class="btn green">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    @parent
    <script src="{{asset('resources/assets/global/plugins/jquery-ui/jquery-ui.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('resources/assets/pages/scripts/ui-modals.min.js')}}"
            type="text/javascript"></script>

    <script>
        var rows = 1;
        $('#add_new_row').click(function () {
            var new_row = $('#table-row-template tbody').html();
            var res = new_row.replace(/\[\]/g, "[" + rows + "]");
            $('#form-generation-table tr:last').after(res);
            rows = rows + 1;
        });

        $('#form-generation-table').on('click', '.remove_row', function (e) {
            $(this).closest('tr').remove()
        })
    </script>
@endsection