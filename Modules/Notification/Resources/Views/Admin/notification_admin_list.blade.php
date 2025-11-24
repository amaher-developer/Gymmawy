@extends('generic::layouts.list')
@section('list_title') {{ @$title }} @endsection
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('list_add_button')
    <a href="{{route('createNotification')}}" class="btn btn-lg btn-success">{{trans('admin.add')}}  Notification</a>
@endsection
@section('page_body')
    <table class="table table-striped table-bordered table-hover" id="sample_3">
        <thead>
        <tr class="">
            <th>id</th>
            <th>{{trans('admin.title')}} </th>
            <th>{{trans('admin.id')}}</th>
            <th>{{trans('admin.sent_at')}} </th>
{{--            <th>{{trans('admin.view')}} </th>--}}
        </tr>
        </thead>
        <tbody>
        @foreach($notifications as $key => $notification)
            <tr>
                <td>{{ $notification->id }}</td>
                <td> {{@$notification->body['title']}}</td>
                <td> {{$notification->notification_id}}</td>
                <td> {{$notification->created_at}}</td>
{{--                <td>--}}
{{--                    <a href="{{route('showNotification',$notification->id)}}" class="btn btn-sm blue">--}}
{{--                        Show stats <i class="fa fa-bar-chart"></i>--}}
{{--                    </a>--}}
{{--                </td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
