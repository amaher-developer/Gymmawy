@extends('generic::layouts.master');
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
                            <span class="caption-subject font-dark sbold uppercase">Roles</span>
                        </div>
                        <div class="actions">
                            <a href="{{route('access.role.create')}}" class="btn btn-lg btn-success">Add Role</a>
                            <div class="btn-group">
                                <a class="btn red btn-outline" href="javascript:;" data-toggle="dropdown">
                                    <i class="fa fa-share"></i>
                                    <span class="hidden-xs"> Trigger Tools </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu pull-right" id="sample_3_tools">
                                    <li>
                                        <a href="javascript:;" data-action="0" class="tool-action">
                                            <i class="icon-printer"></i> Print</a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-action="1" class="tool-action">
                                            <i class="icon-check"></i> Copy</a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-action="2" class="tool-action">
                                            <i class="icon-doc"></i> PDF</a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-action="3" class="tool-action">
                                            <i class="icon-paper-clip"></i> Excel</a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-action="4" class="tool-action">
                                            <i class="icon-cloud-upload"></i> CSV</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="javascript:;" data-action="5" class="tool-action">
                                            <i class="icon-refresh"></i> Reload</a>
                                    </li>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="sample_3">
                                <thead>
                                <tr class="">
                                    <th> Name</th>
                                    <th> Slug</th>
                                    <th> Description</th>
                                    <th> Edit</th>
                                    <th> Remove</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td> {{$role->display_name}}</td>
                                        <td> {{$role->name}}</td>
                                        <td> {{$role->description}}</td>
                                        <td>
                                            <a href="javascript:;" class="btn btn-sm yellow"> Edit
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="javascript:;" class="btn btn-sm red"> Delete
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->

            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@stop
