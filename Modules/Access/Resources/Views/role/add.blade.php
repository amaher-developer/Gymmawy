@extends('generic::layouts.form');
@section('form_title') New Role @endsection

@section('styles')
    <link href="{{asset('resources/assets/admin/global/plugins/bootstrap-table/bootstrap-table.min.css')}}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('resources/assets/admin/global/plugins/icheck/skins/all.css')}}"
          rel="stylesheet"
          type="text/css"/>
@endsection

@section('page_body')

    <form method="post" action="{{route('storeRole')}}" class="form-horizontal" role="form">
        @include('access::role._form',['submit_button' => 'Add Role'])
    </form>

@endsection

@section('scripts')
    <script src="{{asset('resources/assets/admin/global/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('resources/assets/admin/pages/scripts/components-bootstrap-switch.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('resources/assets/admin/global/plugins/icheck/icheck.min.js')}}"
            type="text/javascript"></script>

    <script type="text/javascript">

        $('#display_name').keyup(function () {
            $('#name').val(convertToSlug($('#display_name').val()));

        });
        $('#display_name').change(function () {
            $('#name').val(convertToSlug($('#display_name').val()));

        });

        function convertToSlug(Text) {
            return Text
                .toLowerCase()
                .replace(/ /g, '-')
                .replace(/[^\w-]+/g, '')
                ;
        }

    </script>
@endsection
