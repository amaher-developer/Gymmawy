@extends('generic::Front.layouts.master')

@section('content')


    <main>
        <div id="error_page">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-xl-7 col-lg-9">
                        <h2>404 <i class="icon_error-triangle_alt"></i></h2>
{{--                        <p>{{trans('global.not_found')}}</p>--}}
{{--                        <form>--}}
{{--                            <div class="search_bar_error">--}}
{{--                                <input type="text" class="form-control" placeholder="What are you looking for?">--}}
{{--                                <input type="submit" value="Search">--}}
{{--                            </div>--}}
{{--                        </form>--}}
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /error_page -->
    </main>
    <!--/main-->




{{--    <!-- Inner Page Banner Area Start Here -->--}}
{{--    <div class="inner-page-banner-area" style="background-image: url('img/banner/inner-banner.jpg');">--}}
{{--        <div class="container">--}}
{{--            <div class="pagination-area">--}}
{{--                <h2 class="inner-section-title-textprimary"><span>{{trans('global.thank_you')}}</span></h2>--}}
{{--                <div class="section-title-bar"><i class="flaticon-dumbbell"></i></div>--}}
{{--                <ul>--}}
{{--                    <li><a href="{{asset('/')}}">{{trans('global.home')}}</a> -</li>--}}
{{--                    <li>{{trans('global.thank_you')}}</li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- Inner Page Banner Area End Here -->--}}
{{--    <!-- Contact Us Page Area Start Here -->--}}
{{--    <div class="section-space-all body-bg">--}}
{{--        <div class="container">--}}
{{--            <div class="col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1 col-xs-12 thanks-main text-center">--}}
{{--                <img src="{{asset('resources/assets/front/img/thanks.png')}}">--}}
{{--                <h3>{{trans('global.thank_you')}}</h3>--}}
{{--                <p>{{trans('global.thank_you_message')}}</p>--}}


{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- Contact Us Page Area End Here -->--}}










@endsection

@section('style')



@endsection

@section('script')


    @endsection

