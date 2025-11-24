@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listCalorieFood') }}">CalorieFoods</a>
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
                <label class="col-md-3 control-label">Category Id</label>
                <div class="col-md-9">
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">Choose...</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}"
                                    @if($caloriefood->category_id == $category->id) selected @endif >{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="clear: both"></div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Name En</label>
                <div class="col-md-9">
                    <input id="name_en" value="{{ old('name_en', $caloriefood->name_en) }}"
                           name="name_en" type="text" class="form-control" required>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Name Ar</label>
                <div class="col-md-9">
                    <input id="name_ar" value="{{ old('name_ar', $caloriefood->name_ar) }}"
                           name="name_ar" type="text" class="form-control" required>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Calories</label>
                <div class="col-md-9">
                    <input id="calories" value="{{ old('calories', $caloriefood->calories) }}" step="any"
                           name="calories" type="number" class="form-control">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Unit</label>
                <div class="col-md-9">
                    <input id="unit" value="{{ old('unit', $caloriefood->unit) }}" step="any"
                           name="unit" type="number" class="form-control">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Unit ID</label>
                <div class="col-md-9">

                    <select name="unit_id" id="unit_id" class="form-control">
                        <option value="">Choose...</option>
                        @foreach(calorie_units('ar') as $key => $unit)
                            <option value="{{$key}}"
                                    @if($key == $caloriefood->unit_id) selected @endif>{{$unit}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="form-group col-md-6" style="clear:both;">
                <label class="col-md-3 control-label">{{trans('admin.disable')}}</label>
                <div class="col-md-9">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="hidden" name="deleted_at" value="">
                            <input type="checkbox" value="{{ date('Y-m-d') }}" name="deleted_at"
                                    {{ $caloriefood->trashed()?'checked':'' }}>
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
