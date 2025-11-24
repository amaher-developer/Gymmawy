@extends('generic::layouts.user_list')
@section('styles')
    <link href="http://keenthemes.com/preview/metronic/theme/assets/pages/css/profile-2.min.css"
          rel="stylesheet" type="text/css"/>
    <style>
        .maher-tags a {
            background-color: #f4f6f8;
            color: #a0a9b4;
            font-size: 11px;
            font-weight: 600;
            padding: 7px 10px;
        }

        .maher-tags li {
            list-style: none;
            display: inline-block;
            margin: 0 5px 20px 0;
        }
    </style>
@endsection
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            {{ $title }}
        </li>
    </ul>
@endsection
@section('list_add_button')
    @if($trainer) <a href="{{route('editUserTrainer')}}"
                     class="btn btn-lg btn-success">{{trans('admin.trainer_edit')}}</a> @endif

@endsection
@section('list_title')
    {{ @$title }}
@if(isset($trainer) && !@$trainer->published)
    <span style="color: red;font-size: 13px;font-weight: bolder">"{{trans('admin.not_review')}}"</span>
@endif
@endsection
@section('page_body')
    <div class="row">


            <div class="col-md-3  col-sm-3">
                <ul class="list-unstyled profile-nav">
                    <li>
                        <img src="@if($trainer->image) {{$trainer->image}} @else {{asset('uploads/users/default.jpg')}} @endif"
                             class="img-responsive pic-bordered" alt=""/>
                    </li>

                </ul>
            </div>
            <div class="col-md-9 col-sm-9">
                <div class="row">

                    <div class="col-md-8  col-sm-8 profile-info">
                        <h1 class="font-green sbold uppercase">{{$trainer->name}}</h1>
                        <h3 class="sbold uppercase">{{$trainer->title}}</h3>
                        <p> {{$trainer->about}} </p>

                        <ul class="list-inline">
                            {{--<li>--}}
                            {{--<i class="fa fa-map-marker"></i> Spain </li>--}}
                            <li dir="rtl">
                                <i class="fa fa-calendar"></i> {{$trainer->birthdate}} ({{$trainer->age}})
                            </li>
                            <li>
                                <i class="fa fa-briefcase"></i> {{$trainer->experience}} {{trans('admin.experience_year')}}
                            </li>
                            <li>
                                <svg aria-hidden="true" data-prefix="fas" data-icon="dumbbell"
                                     class="svg-inline--fa fa-dumbbell fa-w-20" role="img"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                    <path fill="currentColor"
                                          d="M104 96H56c-13.3 0-24 10.7-24 24v104H8c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h24v104c0 13.3 10.7 24 24 24h48c13.3 0 24-10.7 24-24V120c0-13.3-10.7-24-24-24zm528 128h-24V120c0-13.3-10.7-24-24-24h-48c-13.3 0-24 10.7-24 24v272c0 13.3 10.7 24 24 24h48c13.3 0 24-10.7 24-24V288h24c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8zM456 32h-48c-13.3 0-24 10.7-24 24v168H256V56c0-13.3-10.7-24-24-24h-48c-13.3 0-24 10.7-24 24v400c0 13.3 10.7 24 24 24h48c13.3 0 24-10.7 24-24V288h128v168c0 13.3 10.7 24 24 24h48c13.3 0 24-10.7 24-24V56c0-13.3-10.7-24-24-24z"></path>
                                </svg>
                                {{$trainer->gym_name}} </li>
                            <li>
                            {{--<i class="fa fa-heart"></i> BASE Jumping </li>--}}
                        </ul>

                        <div>
                            <label class="blog-sidebar-title uppercase">{{trans('admin.categories')}}:</label>
                            <br/><br/>
                            @if(count($trainer->categories) > 0)
                                <div class="blog-single-sidebar-tags maher-tags">
                                    <ul class="blog-post-tags">
                                        @foreach($trainer->categories as $category)
                                            <li class="uppercase">
                                                <a href="javascript:;">{{$category['name']}}</a>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                            @endif
                        </div>
{{--                        <div>--}}
{{--                            <label class="blog-sidebar-title uppercase">{{trans('admin.coverage_districts')}}:</label>--}}
{{--                            <br/><br/>--}}
{{--                            @if(count($trainer->districts) > 0)--}}
{{--                                <div class="blog-single-sidebar-tags maher-tags">--}}
{{--                                    <ul class="blog-post-tags">--}}
{{--                                        @foreach($trainer->districts as $district)--}}
{{--                                            <li class="uppercase">--}}
{{--                                                <a href="javascript:;">{{$district['name']}}</a>--}}
{{--                                            </li>--}}
{{--                                        @endforeach--}}

{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                        </div>--}}

                    </div>
                    <!--end col-md-8-->
                    <div class="col-md-4  col-sm-4">

                        <div class="portlet sale-summary">
                            <div class="portlet-title">
                                <div class="caption font-red sbold"> {{trans('admin.contact_info')}} </div>
                            </div>
                            <div class="portlet-body">
                                <ul class="list-unstyled">
                                    @if($trainer->website)
                                        <li>
                                            <i class="fa fa-globe"></i><a href="{{$trainer->website}}"
                                                                          target="_blank"> {{trans('admin.website')}} </a>
                                            <br/><br/>
                                        </li>
                                    @endif
                                    @if($trainer->phone)
                                        <li>
                                            <i class="fa fa-phone"></i><a
                                                    href="callTo:{{$trainer->phone}}"> {{$trainer->phone}} </a>
                                            <br/><br/>
                                        </li>
                                    @endif
                                    @if($trainer->facebook)
                                        <li>
                                            <i class="fa fa-facebook"></i><a href="{{$trainer->facebook}}"
                                                                             target="_blank"> {{trans('admin.facebook')}} </a>
                                            <br/><br/>
                                        </li>
                                    @endif
                                    @if($trainer->twitter)
                                        <li>
                                            <i class="fa fa-twitter"></i><a href="{{$trainer->twitter}}"
                                                                            target="_blank"> {{trans('admin.twitter')}} </a>
                                            <br/><br/>
                                        </li>
                                    @endif
                                    @if($trainer->instagram)
                                        <li>
                                            <i class="fa fa-instagram"></i><a href="{{$trainer->instagram}}"
                                                                              target="_blank"> {{trans('admin.instagram')}} </a>
                                            <br/><br/>
                                        </li>
                                    @endif
                                    @if($trainer->linkedin)
                                        <li>
                                            <i class="fa fa-linkedin"></i><a href="{{$trainer->linkedin}}"
                                                                             target="_blank"> {{trans('admin.linkedin')}} </a>
                                            <br/><br/>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--end col-md-4-->

                </div>
                <!--end row-->
            </div>

    </div>

@endsection

