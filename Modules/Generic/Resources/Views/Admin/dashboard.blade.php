@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{ $title }}</a>
        </li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('sub_styles')
<style>
    .comment{
        color: grey;
        font-size: 12px;
        width: 50%;
        white-space: pre-line;
    }
</style>
@endsection
@section('page_body')
    <!-- BEGIN DASHBOARD STATS -->
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {{$gym_active_count}}
                    </div>
                    <div class="desc">
                        Gyms Active
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {{$gym_not_active_count}}
                    </div>
                    <div class="desc">
                        Gyms Not Active
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {{$trainer_active_count}}
                    </div>
                    <div class="desc">
                        Trainers Active
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat purple-plum">
                <div class="visual">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {{$trainer_not_active_count}}
                    </div>
                    <div class="desc">
                        Trainers Not Active
                    </div>
                </div>
            </div>
        </div>

        <!-- END DASHBOARD STATS -->
        <div class="clearfix">
        </div>
    </div>
    <!-- END DASHBOARD STATS -->
    <div class="clearfix"><hr/>
    </div>
    <!-- BEGIN DASHBOARD STATS -->
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {{$article_active_count}}
                    </div>
                    <div class="desc">
                        Articles Active Count
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {{$article_not_active_count}}
                    </div>
                    <div class="desc">
                        Articles Not Active
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {{$user_count}}
                    </div>
                    <div class="desc">
                        Users Count
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat purple-plum">
                <div class="visual">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {{$user_guest_count}}
                    </div>
                    <div class="desc">
                        Users Guest
                    </div>
                </div>
            </div>
        </div>


{{--        <div style="padding-top: 50px;clear: both;float: none"></div>--}}
{{--        <div class="row col-md-12 table-responsive border-top userlist-table">--}}
{{--        <table class="table card-table table-striped table-vcenter text-nowrap mb-0" >--}}
{{--            <thead>--}}
{{--            <tr class="">--}}
{{--                <th>id</th>--}}
{{--                <th>{{trans('admin.gym')}}</th>--}}
{{--                <th></th>--}}
{{--                <th>{{trans('admin.contacts')}}</th>--}}
{{--                <th>{{trans('admin.comment')}}</th>--}}
{{--                <th>{{trans('admin.actions')}}</th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @foreach($gyms as $key=> $gym)--}}
{{--                <tr>--}}
{{--                    <td> {{ $gym->id }}</td>--}}
{{--                    <td> <img src="{{ $gym->logo }}" class="rounded-circle avatar-md mr-2"> </td>--}}
{{--                    <td> {{ $gym->name }}--}}
{{--                        <br/>--}}
{{--                        <p class="comment">{{@$gym->address}}, {{@$gym->district->name}}</p>--}}
{{--                    </td>--}}
{{--                    <td>--}}
{{--                        @if(@$gym->gym_brand->main_phone)--}}
{{--                        <ul>--}}
{{--                            <li><a href="callto:{{$gym->gym_brand->main_phone}}">{{$gym->gym_brand->main_phone}}</a></li>--}}
{{--                            @if(isset($gym->phones) && (count($gym->phones) > 0))--}}
{{--                                @foreach($gym->phones as $phone)--}}
{{--                                    <li><a href="callto:{{$phone}}">{{$phone}}</a></li>--}}
{{--                                @endforeach--}}
{{--                                @endif--}}
{{--                        </ul>--}}
{{--                        @endif--}}
{{--                    </td>--}}
{{--                    <td>--}}
{{--                        <textarea style="min-width: 100px;" id="comment_{{$gym->id}}" class="form-control" placeholder="{{trans('admin.comment')}}">{{@$gym->call_center_log->comment}}</textarea>--}}
{{--                        <br/>--}}
{{--                        <select class="form-control" id="rate_{{$gym->id}}">--}}
{{--                            <option>{{trans('admin.rate')}}</option>--}}
{{--                            <option value="1" @if(@$gym->call_center_log->rate == 1) selected="" @endif>1</option>--}}
{{--                            <option value="2" @if(@$gym->call_center_log->rate == 2) selected="" @endif>2</option>--}}
{{--                            <option value="3" @if(@$gym->call_center_log->rate == 3) selected="" @endif>3</option>--}}
{{--                            <option value="4" @if(@$gym->call_center_log->rate == 4) selected="" @endif>4</option>--}}
{{--                            <option value="5" @if(@$gym->call_center_log->rate == 5) selected="" @endif>5</option>--}}
{{--                        </select>--}}

{{--                    </td>--}}
{{--                    <td>--}}
{{--                        <a href="javascript:void(0)" onclick="saveComment({{$gym->id}})" class="btn btn-xs yellow">--}}
{{--                            <i class="fa fa-save"></i>--}}
{{--                        </a>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--            </tbody>--}}
{{--        </table>--}}

{{--            <div class="col-lg-5 col-md-5 col-md-offset-5">--}}
{{--                {!! $gyms->render()  !!}--}}
{{--            </div>--}}
{{--        </div>--}}

    </div>
@endsection
@section('sub_scripts')
    <script>
        function saveComment(gym_id){
            let comment = $('#comment_'+gym_id).val();
            let rate = $('#rate_'+gym_id).val();

            if(comment){
                $.ajax({
                    url: '{{route('saveCommentAjax')}}',
                    cache: false,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        comment: comment,
                        gym_id: gym_id,
                        rate: rate,
                        _token: "{{csrf_token()}}"
                    },
                    success: function (response) {
                        swal("Operation success", "Comment saved!", "success");
                    },
                    error: function (request, error) {
                        swal("Operation failed", "Something went wrong.", "error");
                        console.error("Request: " + JSON.stringify(request));
                        console.error("Error: " + JSON.stringify(error));
                    }
                });
            }
        }
    </script>
@endsection
