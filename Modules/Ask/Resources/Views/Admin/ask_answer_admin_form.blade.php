@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listAsk') }}">{{trans('admin.questions')}}</a>
            <i class="fa fa-circle"></i>
        </li> <li>
            <a href="{{ route('listAskAnswer') }}">{{trans('admin.answers')}}</a>
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

            <div class="form-group col-md-12">
                <label class="control-label">{{trans('global.name')}} <span class="required">*</span></label>
                <input type="text" name="name" id="name" class="form-control" maxlength="255" value="{{old('name', $ask->name)}}" placeholder="" >
            </div>
            <div class="form-group col-md-12">
                <label class="control-label">{{trans('global.email')}} <span class="required">*</span></label>
                <input type="email" name="email" id="email" class="form-control" maxlength="255" value="{{old('email', $ask->email)}}" placeholder="" >
            </div>
            <div id="div_related_questions" class="clearfix"></div>
            <div class="form-group col-md-12">
                <label class="control-label">{{trans('admin.answer')}}</label>
                <textarea  name="answer" id="answer" maxlength="12000" rows="5" class="form-control">{{old('answer', $ask->answer)}}</textarea>
            </div>

            <div class="form-group col-md-12 "><br/><hr/><br/></div>

            <div class="form-group col-md-6" style="clear:both;">
                <label class="col-md-3 control-label">{{trans('admin.disable')}}</label>
                <div class="col-md-3">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="hidden" name="deleted_at" value="">
                            <input type="checkbox" value="{{ date('Y-m-d') }}" name="deleted_at"
                                    {{ $ask->trashed()?'checked':'' }}>
                            <span></span>
                        </label>
                    </div>
                </div>
                <label class="col-md-3 control-label"> </label>
                <div class="col-md-3  control-label">
                    @if($ask->question_id)<a href="{{route('editAsk', $ask->question_id)}}" target="_blank">{{trans('admin.edit_question')}}</a>@endif
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
