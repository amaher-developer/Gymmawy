@extends('generic::layouts.form')
@php $__module = 'module' @endphp
@section('form_title') New {{ucfirst($__module)}} @endsection
@section('page_body')

    <form method="post" action="{{route('store'. ucfirst($__module))}}" class="form-horizontal" role="form">
        @include('crud::_form',['submit_button' => 'Add ' . ucfirst($__module)])
    </form>

@endsection