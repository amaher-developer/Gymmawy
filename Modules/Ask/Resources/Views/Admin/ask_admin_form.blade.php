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
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('sub_styles')
    <link rel="stylesheet" type="text/css" href="{{asset('resources/assets/admin/')}}/global/plugins/select2/select2.css"/>
    <link href="{{asset('resources/assets/admin/')}}/global/css/plugins-rtl.css" rel="stylesheet" type="text/css"/>

@endsection
@section('page_body')
    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}

            <div class="form-group col-md-12">
                <label class="control-label">{{trans('global.ask_question')}} <span class="required">*</span></label>
                <input type="text" name="question" id="question" class="form-control" maxlength="255" value="{{old('question', $ask->question)}}" placeholder="" required>
            </div>
            <div id="div_related_questions" class="clearfix"></div>
            <div class="form-group col-md-12">
                <label class="control-label">{{trans('global.question_details')}}</label>
                <textarea  name="details" id="details" maxlength="12000" rows="5" class="form-control">{{old('details', $ask->details)}}</textarea>
            </div>
            <div class="form-group col-md-12">
                <label class="control-label">{{trans('global.email')}} </label>
                <input type="email" name="email" id="email" class="form-control"  value="{{old('email', $ask->email)}}" placeholder="">
            </div>
            <div class="form-group col-md-12">
                <label class="control-label">{{trans('admin.category')}}</label>
                <select name="category_id" id="category_id" class="form-control" style="padding-top: 4px !important;">
                    <option value="">{{trans('global.no_classification')}}</option>
                    @foreach($article_categories as $article_category)
                        <option value="{{$article_category->id}}" @if(@old('category_id', $ask->category_id) == $article_category->id) selected="" @endif>{{$article_category->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-12">
                <label class="control-label">{{trans('global.tags')}} <span style="font-size: 14px;">({{trans('global.tag_form_msg')}})</span></label>
                <input type="hidden" id="select2_sample5" name="tags" class="form-control select2" value="{{old('tags', $questionTags)}}">
            </div>


            <div class="form-group col-md-6" style="clear:both;">
                <label class="col-md-3 control-label">{{trans('admin.disable')}}</label>
                <div class="col-md-9">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="hidden" name="deleted_at" value="">
                            <input type="checkbox" value="{{ date('Y-m-d') }}" name="deleted_at"
                                    {{ $ask->trashed()?'checked':'' }}>
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
@section('scripts')
    @parent
    <script type="text/javascript" src="{{asset('resources/assets/admin/')}}/global/plugins/select2/select2.min.js"></script>
    <script>
        $("#select2_sample5").select2({
            tags: [{!! $tags !!}]
        });
    </script>
@endsection
