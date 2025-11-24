@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listNotification') }}">{{trans('admin.notifications')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('page_body')
    @if(session('notification_recipients'))
        <div class="alert alert-success">
            Notification Sent to <strong>{{ session('notification_recipients') }}</strong> Customers
        </div>
    @elseif(session('notification_error'))
        <div class="alert alert-warning">
            <strong>{{ session('notification_error') }}</strong>
        </div>
    @endif

    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        @if(request('test'))
            <input type="hidden" name="test" value="1">
        @endif
        <div class="form-body">
            {{csrf_field()}}
            <div class="form-group">
                <label for="title" class="col-md-3 control-label">Notification Title</label>
                <div class="col-md-9">
                    <input required id="title" value="{{ old('title') }}" step="any"
                           name="title" type="text" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="body" class="col-md-3 control-label">Notification Body</label>
                <div class="col-md-9">
                    <textarea id="body" name="body" class="form-control">{{ old('body') }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="type" class="col-md-3 control-label">Type</label>
                <div class="col-md-9">
                    <select required id="type" name="type" class="bs-select form-control"
                            title="Choose One Of The Following Types...">
                        <option value="{{\App\Modules\Notification\Http\enums\NotificationType::external_url}}">URL</option>
                        <option value="{{\App\Modules\Notification\Http\enums\NotificationType::general_message}}">Message</option>
                    </select>
                </div>
            </div>

            <div id="types"></div>

            <div class="form-group">
                <label for="url" class="col-md-3 control-label">Notification URL</label>
                <div class="col-md-9">
                    <input disabled required id="url" value="{{ old('url') }}" name="url" type="text"
                           class="form-control">
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Submit</button>
                        <input type="reset" class="btn default" value="Reset">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div style="height: 200px"></div>
@endsection

@section('scripts')
    @parent
    <script>
        $(document).on('changed.bs.select', '#type', function (event) {
            switch ($(this).val()) {
                case '1':
                    $('#url').removeAttr('disabled');
                    break;
                case '2':
                    $('#url').attr('disabled', 'disabled');
            }
        });
    </script>

@endsection
