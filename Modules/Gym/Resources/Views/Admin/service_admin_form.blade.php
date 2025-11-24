@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
             <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listService') }}">Services</a>
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
             <label class="col-md-3 control-label">Name AR</label>
             <div class="col-md-9">
                 <input id="name_ar" value="{{ old('name_ar', $service->name_ar) }}"
                        name="name_ar" type="text" class="form-control" >
             </div>
         </div>
         <div class="form-group col-md-6">
             <label class="col-md-3 control-label">Name EN</label>
             <div class="col-md-9">
                 <input id="name_en" value="{{ old('name_en', $service->name_en) }}"
                        name="name_en" type="text" class="form-control" >
             </div>
         </div>


         <div style="clear: both;"></div>
         <div class="form-group col-md-6">
             <label class="col-md-3 control-label">{{trans('admin.image')}}</label>
             <div class="col-md-8">
                 <input id="logo" value="{{ old('logo', $service->logo) }}"
                        name="logo" type="file" class="form-control" >
             </div>
             @if(!empty($service->logo))
                 <label class="col-md-1 control-label">
                     <a href="{{ $service->logo }}" class="fancybox-button" data-rel="fancybox-button">
                         view
                     </a>
                 </label>
             @endif
         </div>
    <div class="form-group col-md-6" style="clear:both;">
        <label class="col-md-3 control-label">{{trans('admin.disable')}}</label>
        <div class="col-md-9">
            <div class="mt-checkbox-list">
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="hidden" name="deleted_at" value="">
                    <input type="checkbox" value="{{ date('Y-m-d') }}" name="deleted_at"
                            {{ $service->trashed()?'checked':'' }}>
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
