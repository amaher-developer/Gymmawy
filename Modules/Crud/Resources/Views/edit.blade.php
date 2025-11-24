@extends('generic::layouts.form');
@php $__module = 'module' @endphp
@section('form_title') edit {{ucfirst($__module->name)}} @endsection
@section('page_body')
    <form method="post" action="{{route('editCategory',$$__module->id)}}" class="form-horizontal" role="form">
        @include('crud::_form',['submit_button' => 'Update'])
    </form>
@endsection
