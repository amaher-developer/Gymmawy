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

@section('page_body')
    <table class="table table-striped table-bordered table-hover" id="sample_3">
        <thead>
        <tr class="">
            <th>Date</th>
            <th>ID</th>
            <th>Link</th>
            <th>Count</th>
            <th>Actions</th>
            <th width="20%">Params</th>
        </tr>
        </thead>
        <tbody>
        @foreach($apilogs as $apilog)
            <tr>
                <?php
                $params = '';
                foreach (json_decode((string)@$apilog->params) as $key=> $parm){
                    $params.=$key.'='.$parm.'&';
                }
                ?>
                <td> {{$apilog->created_at}}</td>
                <td> {{$apilog->id}}</td>
                <td> {{$apilog->link}} <a target="_blank" href="{{$apilog->link}}?{{ $params }}&test=1">Test</a></td>
{{--                <td> {!! substr(str_replace(',','<br>',str_replace('"','',str_replace('\\"','',$apilog->params ))),1,-1) !!}</td>--}}
                <td> {{$apilog->count}}</td>

                <td>
                    <a href="{{route('deleteApiLog',$apilog->id)}}" class="confirm_delete btn btn-xs red">
                        <i class="fa fa-times"></i>
                    </a>
                </td>
                    <td> {!! $apilog->params !!}</td>

            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
