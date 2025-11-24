@extends('generic::Front.layouts.master')
@section('style')

@stop
@section('content')

    <section class="profile_pages">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <div class="similar profile_aside">
                        <h3 class="title">{{$currentUser->name}}</h3>
                        <div class="widgets">
                            <h4><a class="active" href="{{route('showProfile')}}">{{trans('global.update_profile')}}</a>
                            </h4>
                            <h4><a href="{{route('savedSearchList')}}">{{trans('global.saved_search')}}</a></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-1 col-xs-12">
                    <div class="data">
                        <h3>{{$currentUser->name}}</h3>
                        <h3>{{$currentUser->email}}</h3>
                        <h3>{{$currentUser->phone}}
{{--                            @if($currentUser->verified)--}}
{{--                                <span style="color: #50c5bc;   margin: auto 20px;   font-size: 17px;">{{trans('global.verified')}}</span>--}}
{{--                            @else--}}
{{--                                <a href="{{route('showVerificationPage')}}">{{trans('global.verify')}}</a>--}}
{{--                            @endif--}}
                        </h3>
                        <div class="clearfix">
                            <a href="{{route('editProfile')}}" class="btn btn-default custom_button">{{trans('global.edit')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
@stop
