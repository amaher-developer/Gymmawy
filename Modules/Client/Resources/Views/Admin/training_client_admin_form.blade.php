@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listTrainingClient') }}">Training Clients</a>
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
                <label class="col-md-3 control-label">Name</label>
                <div class="col-md-9">
                    <input id="name" value="{{ old('name', $client->name) }}"
                           name="name" type="text" class="form-control">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Phone</label>
                <div class="col-md-9">
                    <input id="phone" value="{{ old('phone', $client->phone) }}"
                           name="phone" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Email</label>
                <div class="col-md-9">
                    <input id="email" value="{{ old('email', $client->email) }}"
                           name="email" type="text" class="form-control">
                </div>
            </div>




            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Country</label>
                <div class="col-md-9">
                    <input id="country" value="{{ old('country', $client->country) }}"
                           name="country" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label">Code</label>
                <div class="col-md-9">
                    <input id="code" value="{{ old('code', $client->code) ?? $code }}"
                           name="code" type="text" class="form-control">
                </div>
            </div>

            <div class="clearfix" style="clear: both;float: none"><br/><hr/><br/></div>



            <div class="form-group  col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-3 control-label">{{trans('admin.question_1')}}</label>
                <div class="col-md-9">
                    <input id="question_1" name="questions[1]" type="text" value="{{ old('questions[1]', @$client->questions[1]) }}"
                           placeholder="{{trans('admin.question_1')}}" class="form-control"  required>
                </div>
            </div>

            <div class="form-group  col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-3 control-label">{{trans('admin.question_2')}}</label><div class="col-md-9">
                    <input id="question_2" name="questions[2]" type="text" value="{{ old('questions[2]', @$client->questions[2]) }}"
                           placeholder="{{trans('admin.question_2')}}" class="form-control" required>
                </div>
            </div>

            <div class="form-group  col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-3 control-label">{{trans('admin.question_3')}}</label>
                <div class="col-md-9">
                    <input id="question_3" name="questions[3]" type="text" value="{{ old('questions[3]', @$client->questions[3]) }}"
                           placeholder="{{trans('admin.question_3')}}" class="form-control" required>
                </div>
            </div>

            <div class="form-group  col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-3 control-label">{{trans('admin.question_4')}}</label>
                <div class="col-md-9">
                    <input id="question_4" name="questions[4]" type="text" value="{{ old('questions[4]', @$client->questions[4]) }}"
                           placeholder="{{trans('admin.question_4')}}" class="form-control" required>
                </div>
            </div>

            <div class="form-group  col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-3 control-label">{{trans('admin.question_5')}}</label>
                <div class="col-md-9">
                    <input id="question_5" name="questions[5]" type="text" value="{{ old('questions[5]', @$client->questions[5]) }}"
                           placeholder="{{trans('admin.question_5')}}" class="form-control" required>
                </div>
            </div>

            <div class="form-group  col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-3 control-label">{{trans('admin.question_6')}}</label>
                <div class="col-md-9">
                    <input id="question_6" name="questions[6]" type="text" value="{{ old('questions[6]', @$client->questions[6]) }}"
                           placeholder="{{trans('admin.question_6')}}" class="form-control" required>
                </div>
            </div>

            <div class="form-group  col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-3 control-label">{{trans('admin.question_7')}}</label>
                <div class="col-md-9">
                    <input id="question_7" name="questions[7]" type="text" value="{{ old('questions[7]', @$client->questions[7]) }}"
                           placeholder="{{trans('admin.question_7')}}"  class="form-control" required>
                </div>
            </div>

            <div class="form-group  col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-3 control-label">{{trans('admin.question_8')}}</label>
                <div class="col-md-9">
                    <input id="question_8" name="questions[8]" type="text" value="{{ old('questions[8]', @$client->questions[8]) }}"
                           placeholder="{{trans('admin.question_8')}}" class="form-control" required>
                </div>
            </div>

            <div class="form-group  col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-3 control-label">{{trans('admin.question_9')}}</label>
                <div class="col-md-9">
                    <input id="question_9" name="questions[9]" type="text" value="{{ old('questions[9]', @$client->questions[9]) }}"
                           placeholder="{{trans('admin.question_9')}}" class="form-control" required>
                </div>
            </div>

            <div class="form-group  col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-3 control-label">{{trans('admin.question_10')}}</label>
                <div class="col-md-9">
                    <input id="question_10" name="questions[10]" type="text" value="{{ old('questions[10]', @$client->questions[10]) }}"
                           placeholder="{{trans('admin.question_10')}}" class="form-control" required>
                </div>
            </div>


            <div class="form-group  col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-3 control-label">{{trans('admin.question_11')}}</label>
                <div class="col-md-9">
                    <input id="question_11" name="questions[11]" type="text" value="{{ old('questions[11]', @$client->questions[11]) }}"
                           placeholder="{{trans('admin.question_11')}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group  col-md-12 col-xsd-12" data-animate="fadeInLeft" data-delay="150">
                <label class="col-md-3 control-label">{{trans('admin.question_12')}}</label>
                <div class="col-md-9">
                    <input id="question_12" name="questions[12]" type="text" value="{{ old('questions[12]', @$client->questions[12]) }}"
                           placeholder="{{trans('admin.question_12')}}" class="form-control" required>
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
