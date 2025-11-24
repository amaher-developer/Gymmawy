@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listTrainingPlan') }}">Training Plans</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection

@section('sub_styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection
@section('form_title') {{ @$title }} @endsection
@section('page_body')
    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Title</label>
                <div class="col-md-9">
                    <input id="title" value="{{ old('title', $plan->title) }}"
                           name="title" type="text" class="form-control">
                </div>
            </div>

            <div class="clearfix" style="clear: both;float: none"><br/><hr/><br/></div>

            <div class="form-group col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-6 col-md-offset-3 control-label" style="text-align: right">{{trans('admin.content')}}</label>
            </div>
            <div class="form-group col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                {{--                <label class="col-md-3 control-label">{{trans('admin.content')}}</label>--}}
                <div class="col-md-6 col-md-offset-3">
                    <textarea id="content" name="content" type="text"
                              placeholder="{{trans('admin.content')}}" rows="100" class="form-control  summernote-textarea-ar"  required>{{ old('content', $plan->content) }}</textarea>
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
@section('sub_scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $( "#date_from" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"

        });
        $( "#date_to" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"

        });
    </script>
@endsection
