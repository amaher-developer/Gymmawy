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
                            <h4><a class="active" href="{{route('showProfile')}}">{{trans('global.update_profile')}}</a></h4>
                            <h4><a href="{{route('savedSearchList')}}">{{trans('global.saved_search')}}</a></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-1 col-xs-12">
                    <div class="data">
                        <form role="form" method="POST"  class="clearfix">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input class="form-control" type="text" name="name" value="{{$currentUser->name}}" placeholder="{{trans('global.name')}}"
                                       data-bv-trigger="keyup change"
                                       required data-bv-notempty-message="{{trans('generic::global.required')}}">
                                <span class="required_mark">*</span>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="email" name="email" value="{{$currentUser->email}}" placeholder="{{trans('global.email')}}"
                                       data-bv-trigger="keyup change"
                                       required data-bv-notempty-message="{{trans('generic::global.required')}}">
                                <span class="required_mark">*</span>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="phone" value="{{$currentUser->phone}}" placeholder="{{trans('global.phone')}}"
                                       data-bv-trigger="keyup change"
                                       required data-bv-notempty-message="{{trans('generic::global.required')}}" >
                                <span class="required_mark">*</span>
                            </div>
                            <div class="form-group">
                                <input class="form-control" autocomplete="new-password" type="password" name="password" placeholder="********" >
                            </div>


                            <div class="clearfix">
                                <button type="submit" class="btn btn-default custom_button">{{trans('global.save')}}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection

@section('script')
@stop
