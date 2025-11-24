@extends('generic::layouts.user_list')
@section('styles')
    {{--    <link href="http://keenthemes.com/preview/metronic/theme/assets/pages/css/profile-2.min.css"--}}
    {{--          rel="stylesheet" type="text/css"/>--}}

    <!-- Page level plugin styles START -->
    <link href="{{asset('/')}}resources/assets/admin/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">
    <style>
        /*.maher-tags a {*/
        /*    background-color: #f4f6f8;*/
        /*    color: #a0a9b4;*/
        /*    font-size: 11px;*/
        /*    font-weight: 600;*/
        /*    padding: 7px 10px;*/
        /*}*/

        .maher-tags li {
            list-style: none;
            display: inline-block;
            margin: 0 5px 20px 0;
        }


        .modal-window {
            position: fixed;
            background-color: rgba(200, 200, 200, 0.75);
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 999;
            opacity: 0;
            pointer-events: none;
            -webkit-transition: all 0.3s;
            -moz-transition: all 0.3s;
            transition: all 0.3s;
        }

        .modal-window {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-window > div {
            position: relative;
            margin: 10% auto;
            background: #fff;
            color: #444;
        }

        .modal-window header {
            font-weight: bold;
        }

        .modal-close {
            color: #aaa;
            line-height: 50px;
            font-size: 80%;
            position: absolute;
            right: 0;
            text-align: center;
            top: 0;
            width: 70px;
            text-decoration: none;
        }

        .modal-close:hover {
            color: #000;
        }

        .modal-window h1 {
            font-size: 150%;
            margin: 0 0 15px;
        }

        .modal-confirm {
            color: #434e65;
            width: 525px;
        }

        .modal-confirm .modal-content {
            padding: 20px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
        }

        .modal-confirm .modal-header {
            background: #e85e6c;
            border-bottom: none;
            position: relative;
            text-align: center;
            margin: -20px -20px 0;
            padding: 35px;
        }

        .modal-confirm h4 {
            text-align: center;
            font-size: 36px;
            margin: 10px 0;
        }

        .modal-confirm .form-control, .modal-confirm .btn {
            min-height: 40px;
            border-radius: 3px;
        }

        .modal-confirm .close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #fff;
            text-shadow: none;
            opacity: 0.5;
        }

        .modal-confirm .close:hover {
            opacity: 0.8;
        }

        .modal-confirm .icon-box {
            color: #fff;
            width: 95px;
            height: 95px;
            display: inline-block;
            border-radius: 50%;
            z-index: 9;
            border: 5px solid #fff;
            padding: 15px;
            text-align: center;
        }

        .modal-confirm .icon-box i {
            font-size: 58px;
            padding-top: 10px;
        }

        .modal-confirm.modal-dialog {
            margin-top: 80px;
        }

        .modal-confirm .btn {
            color: #fff;
            border-radius: 4px;
            background: #eeb711;
            text-decoration: none;
            transition: all 0.4s;
            line-height: normal;
            border-radius: 30px;
            margin-top: 10px;
            min-width: 150px;
            border: none;
        }

        .modal-confirm .btn:hover, .modal-confirm .btn:focus {
            background: #eda645;
            outline: none;
        }

        .trigger-btn {
            display: inline-block;
            margin: 100px auto;
        }

        .fb-profile img.fb-image-lg {
            z-index: 0;
            width: 100%;
            margin-bottom: 10px;
        }

        .fb-image-profile {
            margin: -90px 10px 0px 50px;
            z-index: 9;
            width: 20%;
        }

        @media (max-width: 768px) {

            .fb-profile-text > h1 {
                font-weight: 700;
                font-size: 16px;
            }

            .fb-image-profile {
                margin: -45px 10px 0px 25px;
                z-index: 9;
                width: 20%;
            }
        }

        .fancybox-lock .fancybox-overlay {
            z-index: 99999;
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
    @if($gym)
        <a href="{{route('editUserGymBrand')}}" class="btn btn-lg btn-success"><i class="fa fa-edit"></i> {{trans('admin.gym_edit')}}</a>
    @endif

@endsection
@section('list_title') {{ @$title }}

@endsection
@section('page_body')

    <div class="row">
        <div class="col-md-3 col-sm-3">
            <ul class="list-unstyled profile-nav">
                <li>
                    <img src="@if($gym->logo) {{$gym->logo}} @else {{asset('uploads/users/default.jpg')}} @endif"
                         class="img-responsive pic-bordered" alt=""/>
                </li>

            </ul>
            <div style="clear: both"></div>


        </div>
        <div class="col-md-9 col-sm-9">
            <div class="row">

                <div class="col-md-8 col-sm-8 profile-info">
                    <h3 class="font-green sbold uppercase">{{$gym->name}}</h3>
                    <p> {{$gym->description}} </p>

                    <div class="clearfix">
                        <hr/>
                    </div>
                    <label class="blog-sidebar-title uppercase">{{trans('admin.gym_branches')}}:</label>
                    <br/><br/>
                    @if(isset($gym->gyms) && count($gym->gyms) > 0)
                        <div class="blog-single-sidebar-tags maher-tags">
                            <ul class="blog-post-tags">
                                @foreach($gym->gyms as $branch)
                                    <li class="uppercase">
                                        <a class="btn default" data-toggle="modal" href="#branch_{{$branch->id}}"
                                           >{{$branch->district->name}}</a>
                                    </li>
                                    <div class="modal fade bs-modal-lg" id="branch_{{$branch->id}}" tabindex="-1"
                                         role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true"></button>
                                                    <h6 class="modal-title"><a style="font-size: 14px;" href="{{route('editUserGym', $branch->id)}}" class="btn btn-lg btn-success"><i class="fa fa-edit"></i> {{trans('admin.gym_branch_edit')}}</a>
                                                        @if(!@$branch->published)
                                                            <span style="color: red;font-size: 13px;font-weight: bolder">"{{trans('admin.not_review')}}"</span>
                                                        @endif
                                                    </h6>

                                                </div>
                                                <div class="modal-body">

                                                    <div class="container col-lg-12">
                                                        <div class="fb-profile">
                                                            <img align="left" class="fb-image-lg"
                                                                 style="max-height: 260px;object-fit: cover;"
                                                                 src="{{$branch->cover_image}}"
                                                                 alt="Profile image example"/>
                                                            <img align="left" class="fb-image-profile thumbnail"
                                                                 src="{{$branch->image}}" alt="Profile image example"/>
                                                            <div class="fb-profile-text">
                                                                <h1>{{$gym->name}}</h1>
                                                                <p>{{$gym->description}}</p>


                                                                <div>
                                                                    <div class="portlet">
                                                                        <div class="portlet-title">
                                                                            <div class="caption">
                                                                                <i class="fa fa-cogs"></i>{{trans('admin.categories')}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @if(count($branch->categories) > 0)
                                                                        <div class="blog-single-sidebar-tags maher-tags">

                                                                            <ul class="list-inline">
                                                                                @foreach($branch->categories as $category)
                                                                                    <li class="uppercase">
                                                                                        <img src="{{$category['logo']}}"
                                                                                             style="height: 20px;"> {{$category['name']}}
                                                                                    </li>
                                                                                @endforeach

                                                                            </ul>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div>
                                                                    <div class="portlet">
                                                                        <div class="portlet-title">
                                                                            <div class="caption">
                                                                                <i class="fa fa-cogs"></i>{{trans('admin.gym_services')}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @if(count($branch->services) > 0)
                                                                        <div class="blog-single-sidebar-tags maher-tags">

                                                                            <ul class="list-inline">

                                                                                @foreach($branch->services as $service)
                                                                                    <li class="uppercase">
                                                                                        <img src="{{$service['logo']}}"
                                                                                             style="height: 20px;"> {{$service['name']}}

                                                                                    </li>
                                                                                @endforeach

                                                                            </ul>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <div class="portlet">
                                                                    <div class="portlet-title">
                                                                        <div class="caption">
                                                                            <i class="fa fa-cogs"></i>{{trans('admin.gym_images')}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if($branch->images)
                                                                    <div class="row margin-bottom-40">
                                                                        @foreach($branch->images as $image)
                                                                            <div class="col-md-2 col-sm-2 gallery-item"
                                                                                 style="padding-bottom: 10px;">
                                                                                <a data-rel="fancybox-button-{{$branch->id}}"
                                                                                   href="{{$image->image}}"
                                                                                   class="fancybox-button">
                                                                                    <img alt=""
                                                                                         src="{{$image->image}}"
                                                                                         style="height: 60px;width: 100%;object-fit: cover;"
                                                                                         class="img-responsive">
                                                                                </a>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endif


                                                                <div class="portlet">
                                                                    <div class="portlet-title">
                                                                        <div class="caption">
                                                                            <i class="fa fa-cogs"></i>{{trans('admin.contact_info')}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <ul class="list-inline">
                                                                    @foreach((array)$branch['phones'] as $phone)
                                                                        <li><a href="tel:{{$phone}}"><i class="fa fa-phone-square"></i> {{$phone}} </a></li>
                                                                    @endforeach

                                                                </ul>
                                                                <div class="portlet">
                                                                    <div class="portlet-title">
                                                                        <div class="caption">
                                                                            <i class="fa fa-cogs"></i>{{trans('admin.place_info')}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <ul class="list-inline">

                                                                    @if($branch->address)
                                                                        <li dir="rtl">
                                                                            <i class="fa fa-map-marker"></i> {{$branch->address}}
                                                                        </li>
                                                                    @endif

                                                                    <li dir="rtl">
                                                                        <i class="fa fa-location-arrow"></i> {{$branch->district->name}}
                                                                        ,{{$branch->district->city->name}}
                                                                    </li>
                                                                </ul>
                                                                <iframe style="height: 300px;width: 100%"
                                                                        id="gmap_canvas"
                                                                        src="https://maps.google.com/maps?q={{$branch->lat}},{{$branch->lng}}&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                                                        frameborder="0" scrolling="no" marginheight="0"
                                                                        marginwidth="0"></iframe>

                                                            </div>
                                                        </div>
                                                    </div> <!-- /container -->


                                                    <div style="clear: both"></div>

                                                </div>

                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->

                                @endforeach

                                    <li class="uppercase">
                                        <a class="btn green" title="{{trans('admin.location_add')}}"
                                           href="{{route('createUserGym')}}"
                                        ><i class="fa fa-plus"></i></a>
                                    </li>
                            </ul>
                        </div>
                    @endif


                </div>
                <!--end col-md-8-->
                <div class="col-md-4 col-sm-4">

                    <div class="portlet sale-summary">
                        <div class="portlet-title">
                            <div class="caption font-red sbold"> {{trans('admin.contact_info')}} </div>
                        </div>
                        <div class="portlet-body">
                            <ul class="list-unstyled">

                                @if(@$gym->main_phone)
                                    <li>
                                        <i class="fa fa-phone"></i><a
                                                href="callTo:{{$gym->main_phone}}"> {{$gym->main_phone}} </a>
                                        <br/><br/>
                                    </li>
                                @endif
                                @if(@$gym->socails['website'])
                                    <li>
                                        <i class="fa fa-globe"></i><a href="{{$gym->socials['website']}}"
                                                                      target="_blank"> {{trans('admin.website')}} </a>
                                        <br/><br/>
                                    </li>
                                @endif
                                @if(@$gym->socials['facebook'])
                                    <li>
                                        <i class="fa fa-facebook"></i><a href="{{$gym->socials['facebook']}}"
                                                                         target="_blank"> {{trans('admin.facebook')}} </a>
                                        <br/><br/>
                                    </li>
                                @endif
                                @if(@$gym->socials['twitter'])
                                    <li>
                                        <i class="fa fa-twitter"></i><a href="{{$gym->socials['twitter']}}"
                                                                        target="_blank"> {{trans('admin.twitter')}} </a>
                                        <br/><br/>
                                    </li>
                                @endif
                                @if(@$gym->socials['instagram'])
                                    <li>
                                        <i class="fa fa-instagram"></i><a href="{{$gym->socials['instagram']}}"
                                                                          target="_blank"> {{trans('admin.instagram')}} </a>
                                        <br/><br/>
                                    </li>
                                @endif
                                @if(@$gym->socials['linkedin'])
                                    <li>
                                        <i class="fa fa-linkedin"></i><a href="{{$gym->socials['linkedin']}}"
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
@section('scripts')
    <script src="{{asset('/')}}resources/assets/admin/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script><!-- pop up -->
@endsection
