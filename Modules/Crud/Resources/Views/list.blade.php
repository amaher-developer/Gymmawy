@extends('generic::layouts.list')
@php $module = 'module' @endphp
@section('list_title')modules @endsection
@section('list_add_button')
        <a href="{{route('create'.ucfirst($module))}}" class="btn btn-lg btn-success">Add {{ucfirst($module)}}</a>
        <a href="{{ route('import-zip',['download'=>'zip', 'module'=> 'all']) }}" class="btn btn-lg btn-success">Import All</a>
    {{--<span style="color: red;   border: 1px solid red;   font-size: 13px;   padding: 5px;   margin: auto 10px;"> * to create new module use command php artisan make:module :moduleName </span>--}}
@endsection


@section('page_body')
    @include('crud::_table')
@endsection

