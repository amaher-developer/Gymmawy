@extends('generic::layouts.list')
@section('list_title') {{ @$title }} @endsection
@section('breadcrumb')
    <ul class="page-breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">Dashboard</a>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('list_add_button')
    <a href="{{route('createNewsletter')}}" class="btn btn-lg btn-success">Add Newsletter</a>
     @if(request('trashed'))
            <a href="{{route('listNewsletter')}}" class="btn btn-lg btn-info">Enabled</a>
        @else
            <a href="{{route('listNewsletter')}}?trashed=1" class="btn btn-lg btn-danger">Disabled</a>
        @endif
@endsection
@section('page_body')
    <table class="table table-striped table-bordered table-hover" id="sample_3">
        <thead>
        <tr class="">
            <th>#</th>
            <th>ID</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($newsletters as $key=> $newsletter)
            <tr>
                <td> {{ $key+1 }}</td>
                <td> {{ $newsletter->id }}</td>
                <td>
                    <a href="{{route('editNewsletter',$newsletter->id)}}" class="btn btn-xs yellow">
                        <i class="fa fa-edit"></i>
                    </a>
                    @if(request('trashed'))
                        <a title="Enable" href="{{route('deleteNewsletter',$newsletter->id)}}" class="confirm_delete btn btn-xs green">
                            <i class="fa fa-check-circle"></i>
                        </a>
                    @else
                        <a title="Disable" href="{{route('deleteNewsletter',$newsletter->id)}}" class="confirm_delete btn btn-xs red">
                            <i class="fa fa-times"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! $newsletters->links() !!}
@endsection
