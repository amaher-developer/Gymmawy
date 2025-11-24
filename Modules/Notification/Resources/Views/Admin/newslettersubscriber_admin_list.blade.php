@extends('generic::layouts.list')
@section('list_title') {{ @$title }} @endsection
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">{{ $title }}</a>
        </li>
    </ul>
@endsection
@section('list_add_button')
{{--    <a href="{{route('createNewsletterSubscriber')}}" class="btn btn-lg btn-success">Add NewsletterSubscriber</a>--}}
{{--     @if(request('trashed'))--}}
{{--            <a href="{{route('listNewsletterSubscriber')}}" class="btn btn-lg btn-info">Enabled</a>--}}
{{--        @else--}}
{{--            <a href="{{route('listNewsletterSubscriber')}}?trashed=1" class="btn btn-lg btn-danger">Disabled</a>--}}
{{--        @endif--}}
@endsection
@section('page_body')
    <div class="row">
        <div class="col-md-6">
            <table class="table table-striped table-bordered table-hover">
                <tbody>
                <tr>
                    <th>{{trans('admin.total_count')}}</th>
                    <td>{{ $total }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="table-responsive border-top userlist-table ">
        <table class="table card-table table-striped table-vcenter text-nowrap mb-0 " >
            <thead>
        <tr class="">
            <th>{{trans('admin.id')}}</th>
            <th>{{trans('admin.email')}}</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($newslettersubscribers as $key=> $newslettersubscriber)
            <tr>
                <td> {{ $newslettersubscriber->id }}</td>
                <td> {{ $newslettersubscriber->email }}</td>
                <td>
{{--                    <a href="{{route('editNewsletterSubscriber',$newslettersubscriber->id)}}" class="btn btn-xs yellow">--}}
{{--                        <i class="fa fa-edit"></i>--}}
{{--                    </a>--}}
                    @if(request('trashed'))
                        <a title="Enable" href="{{route('deleteNewsletterSubscriber',$newslettersubscriber->id)}}" class="confirm_delete btn btn-xs green">
                            <i class="fa fa-check-circle"></i>
                        </a>
                    @else
                        <a title="Disable" href="{{route('deleteNewsletterSubscriber',$newslettersubscriber->id)}}" class="confirm_delete btn btn-xs red">
                            <i class="fa fa-times"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    {!! $newslettersubscribers->links() !!}
@endsection
