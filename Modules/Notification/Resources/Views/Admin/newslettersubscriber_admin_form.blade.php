@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">Dashboard</a>
        </li>
        <li>
            <a href="{{ route('listNewsletterSubscriber') }}">NewsletterSubscribers</a>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('page_body')
    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
     <div class="form-body">
        {{csrf_field()}}
    <div class="form-group col-md-6">
    <label class="col-md-3 control-label">Email</label>
    <div class="col-md-9">
        <input id="email" value="{{ old('email', $newslettersubscriber->email) }}"
               name="email" type="text" class="form-control">
    </div>
</div>
            

    <div class="form-group col-md-6" style="clear:both;">
        <label class="col-md-3 control-label">Disable</label>
        <div class="col-md-9">
            <div class="mt-checkbox-list">
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="hidden" name="deleted_at" value="">
                    <input type="checkbox" value="{{ date('Y-m-d') }}" name="deleted_at"
                            {{ $newslettersubscriber->trashed()?'checked':'' }}>
                    <span></span>
                </label>
            </div>
        </div>

    </div>

    <div class="form-actions" style="clear:both;">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <button type="submit" class="btn green">Submit</button>
                <input type="reset" class="btn default" value="Reset">
            </div>
        </div>
    </div>
    </div>
    </form>
@endsection
