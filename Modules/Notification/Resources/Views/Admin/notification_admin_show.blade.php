@extends('generic::layouts.form')
@section('list_title') {{ @$title }} @endsection
@section('breadcrumb')
    <ul class="page-breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">Dashboard</a>
        </li>
        <li>
            <a href="{{ route('listNotification') }}">Notifications</a>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection

@section('page_body')

    <table class="table table-striped table-bordered table-hover" id="">
        <tr>
            <td colspan="2" class="text-center">
                {{--{{ json_encode($stats->data) }}--}}
            </td>
        </tr>
        <tr>
            <th width="40%">Remaining
                <small><br>(Number of notifications that have not been sent out yet. This mean our system is still
                    processing
                    the notification).
                </small>
            </th>
            <td>
                {{ $stats->remaining }}
            </td>
        </tr>
        <tr class="success">
            <th>Successful</th>
            <td>
                {{ $stats->successful }}
            </td>
        </tr>
        <tr class="info">
            <th>Converted
                <small><br>(Number of users who have clicked / tapped on your notification).</small>
            </th>
            <td>
                {{ $stats->converted }}
            </td>
        </tr>
        <tr class="danger">
            <th>Failed
                <small><br>(Number of notifications that could not be delivered due to an error).</small>
            </th>
            <td>
                {{ $stats->failed }}
            </td>
        </tr>
        <tr>
            <th>isAndroid</th>
            <td>
                {{ $stats->isAndroid ? 'Yes' : 'No' }}
            </td>
        </tr>
        <tr>
            <th>isiOS</th>
            <td>
                {{ $stats->isIos ? 'Yes' : 'No' }}
            </td>
        </tr>
        <tr>
            <th>Queued At</th>
            <td>
                {{ date('Y/m/d h:i:s A',$stats->queued_at) }}
            </td>
        </tr>
        <tr>
            <th>Sent At</th>
            <td>
                {{ date('Y/m/d h:i:s A',$stats->send_after) }}
            </td>
        </tr>
    </table>

@endsection
