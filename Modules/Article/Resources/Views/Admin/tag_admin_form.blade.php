@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
             <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listTag') }}">Tags</a>
             <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('page_body')
    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
     <div class="form-body">
        {{csrf_field()}}

         <div class="form-group col-md-6">
             <label class="col-md-3 control-label text-right">{{trans('admin.article_language')}} <span class="required">*</span></label>
             <div class="col-md-9">
                 <select name="language" id="language" class="form-control" required>
                     <option value="">{{trans('admin.choose')}}</option>
                     <option value="ar" @if('ar' == $tag->language) selected="" @endif>{{trans('admin.arabic')}}</option>
                     <option value="en" @if('en' == $tag->language) selected="" @endif>{{trans('admin.english')}}</option>

                 </select>
             </div>
         </div>
         <div class="form-group col-md-6">
             <label class="col-md-3 control-label">Name</label>
             <div class="col-md-9">
                 <input id="name" value="{{ old('name', $tag->name) }}"
                        name="name" type="text" class="form-control">
             </div>
         </div>
            

    <div class="form-group col-md-6" style="clear:both;">
        <label class="col-md-3 control-label">{{trans('admin.disable')}}</label>
        <div class="col-md-9">
            <div class="mt-checkbox-list">
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="hidden" name="deleted_at" value="">
                    <input type="checkbox" value="{{ date('Y-m-d') }}" name="deleted_at"
                            {{ $tag->trashed()?'checked':'' }}>
                    <span></span>
                </label>
            </div>
        </div>

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
