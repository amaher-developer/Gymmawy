@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listGymAdvice') }}">{{trans('admin.advices')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('sub_styles')
    <link rel="stylesheet" type="text/css" href="{{asset('resources/assets/admin/')}}/global/plugins/select2/select2.css"/>
    <!-- BEGIN THEME STYLES -->
    <link href="{{asset('resources/assets/admin/')}}/global/css/plugins-rtl.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset('resources/assets/admin/')}}/layouts/layout/css/layout-rtl.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}resources/assets/admin/global/plugins/jquery-tags-input/jquery.tagsinput-rtl.css"/>

@endsection

@section('form_title') {{ @$title }} @endsection
@section('page_body')
    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.title')}}</label>
                <div class="col-md-9">
                    <input id="title" value="{{ old('title', $advice->title) }}"
                           name="title" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.content')}}</label>
                <div class="col-md-9">
                    <textarea id="content"
                              name="content" type="text" class="form-control">{{ old('content', $advice->content) }}</textarea>
                </div>
            </div>

            <div style="clear: both;"></div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.image')}}</label>
                <div class="col-md-8">
                    <input id="image" value="{{ old('image', $advice->image) }}"
                           name="image" type="file" class="form-control">
                </div>
                @if(!empty($advice->image))
                    <label class="col-md-1 control-label">
                        <a href="{{ $advice->image }}" class="fancybox-button" data-rel="fancybox-button">
                            view
                        </a>
                    </label>
                @endif
            </div>


            <div class="form-group col-md-12" style="clear:both;">
                <hr/>
            </div>



            <div class="form-actions" style="clear:both;">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">{{trans('admin.submit')}}</button>
                        <input type="reset" class="btn default" value="{{trans('admin.reset')}}">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('sub_scripts')


@endsection
