@extends('generic::Front.layouts.master')
@section('content')
    <main>
        <div id="error_page">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-xl-7 col-lg-9">
                        <h2><i class="icon_check_alt"></i></h2>
                        <p>{{trans('global.thank_you_message')}}</p>
                        <p><a href="{{route('dashboard')}}">{{trans('global.dashboard')}}</a></p>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /error_page -->
    </main>
    <!--/main-->
@endsection
@section('style')
@endsection
@section('script')
    <script>
        setTimeout(function(){ window.location = "{{route('dashboard')}}"; },1500);
    </script>
@endsection