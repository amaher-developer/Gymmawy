@extends('generic::layouts.list')
@section('list_title')Roles @endsection
@section('list_add_button')
    <a href="{{route('createRole')}}" class="btn btn-lg btn-success">Add Role</a>
@endsection
@section('page_body')
    @include('access::role._table')
@endsection
