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
    <a href="{{route('createGymAdvice')}}" class="btn btn-lg btn-success">{{trans('admin.add')}}</a>
    @if(request('trashed'))
        <a href="{{route('listGymAdvice')}}" class="btn btn-lg btn-info">{{trans('admin.enabled')}}</a>
    @else
        <a href="{{route('listGymAdvice')}}?trashed=1" class="btn btn-lg btn-danger">{{trans('admin.disabled')}}</a>
    @endif
@endsection
@section('page_body')
    <div class="table-responsive border-top userlist-table ">
        <table class="table card-table table-striped table-vcenter text-nowrap mb-0 " >
            <thead>
        <tr class="">
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($advices as $key=> $advice)
            <tr>
                <td> {{ $advice->id }}</td>
                <td> {{ $advice->title }}</td>
                <td> {{ $advice->content }}</td>
                <td>
                    <a href="{{route('editGymAdvice',$advice->id)}}" class="btn btn-xs yellow">
                        <i class="fa fa-edit"></i>
                    </a>
                    @if(request('trashed'))
                        <a title="Enable" href="{{route('deleteGymAdvice',$advice->id)}}" class="confirm_delete btn btn-xs green">
                            <i class="fa fa-check-circle"></i>
                        </a>
                    @else
                        <a title="Disable" href="{{route('deleteGymAdvice',$advice->id)}}" class="confirm_delete btn btn-xs red">
                            <i class="fa fa-times"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    {!! $advices->links() !!}
@endsection
