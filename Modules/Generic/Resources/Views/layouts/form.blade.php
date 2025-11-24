@extends('generic::layouts.master')
@section('styles')
{{--    <link href="{{asset('resources/assets/admin/global/plugins/fancybox/source/jquery.fancybox.css')}}"--}}
{{--          rel="stylesheet" type="text/css"/>--}}
{{--    <link href="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css')}}"--}}
{{--          rel="stylesheet" type="text/css"/>--}}
{{--    <link href="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/css/jquery.fileupload.css')}}"--}}
{{--          rel="stylesheet" type="text/css"/>--}}
{{--    <link href="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/css/jquery.fileupload-ui.css')}}"--}}
{{--          rel="stylesheet" type="text/css"/>--}}
    <link href="{{asset('resources/assets/admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css')}}"
          rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" type="text/css"
          href="{{asset('resources/assets/admin/global/plugins/bootstrap-summernote/summernote.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('resources/assets/admin/global/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('resources/assets/admin/global/plugins/select2/css/select2-bootstrap.min.css')}}">

    <link rel="stylesheet" type="text/css"
          href="{{asset('resources/assets/admin/custom/bootstrapValidator.css')}}"/>

{{--    <link rel="stylesheet" type="text/css"--}}
{{--          href="{{asset('resources/assets/admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css')}}"/>--}}

    @yield('sub_styles')
@endsection
@section('content')
    <div class="page-fixed-main-content">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                @include('generic::errors')
                        <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">
                                @yield('form_title')
                            </span>
                        </div>
                        <div class="actions"></div>
                    </div>
                    <div class="portlet-body form">
                        @yield('page_body')
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('scripts')
{{--    <script src="{{asset('resources/assets/admin/global/plugins/fancybox/source/jquery.fancybox.pack.js')}}"--}}
{{--            type="text/javascript"></script>--}}
    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js')}}"
            type="text/javascript"></script>
{{--    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/js/vendor/tmpl.min.js')}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/js/vendor/load-image.min.js')}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js')}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js')}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/js/jquery.iframe-transport.js')}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/js/jquery.fileupload.js')}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/js/jquery.fileupload-process.js')}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/js/jquery.fileupload-image.js')}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/js/jquery.fileupload-audio.js')}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/js/jquery.fileupload-video.js')}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/js/jquery.fileupload-validate.js')}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('resources/assets/admin/global/plugins/jquery-file-upload/js/jquery.fileupload-ui.js')}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('resources/assets/admin/pages/scripts/form-fileupload.min.js')}}"--}}
{{--            type="text/javascript"></script>--}}
    <script src="{{asset('resources/assets/admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('resources/assets/admin/pages/scripts/components-bootstrap-select.min.js')}}"
            type="text/javascript"></script>


    <script type="text/javascript"
            src="{{asset('resources/assets/admin/global/plugins/select2/js/select2.full.min.js')}}"></script>
    <script type="text/javascript"
{{--            src="{{asset('resources/assets/admin/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js')}}"></script>--}}
{{--    <script type="text/javascript"--}}
            src="{{asset('resources/assets/admin/global/plugins/bootstrap-summernote/summernote.min.js')}}"></script>

    <script type="text/javascript"
            src="{{asset('resources/assets/admin/custom/bootstrapValidator.js')}}"></script>


    <script>

        $(".js-tags-multi").select2({
            tags: true,
            tokenSeparators: ['&']
        });

        $(".js-tags-multi-ar").select2({
            tags: true,
            tokenSeparators: ['&'],
            direction: 'rtl'
        });


        $('.summernote-textarea').summernote({
            height: 200,
            focus: true,
            callbacks: {
                onImageUpload: function (files, editor, welEditable) {
                    // upload image to server and create imgNode...
                    sendFile(files[0], editor, welEditable);
                }
            }
        });
        $('.summernote-textarea-ar').summernote({
            height: 200,
            focus: true,
            direction: 'rtl',
            callbacks: {
                onImageUpload: function (files, editor, welEditable) {
                    // upload image to server and create imgNode...
                    sendFile(files[0], editor, welEditable);
                }
            }
        });

        $('.note-codable').attr('required', true).attr('data-summernote', true);

        function sendFile(file, editor, welEditable) {
            console.log(file);
            data = new FormData();
            data.append("file", file);
            data.append("_token", '{{csrf_token()}}');
            $.ajax({
                url: "{{route('uploadImageForCKEditorAjax')}}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data) {
                    var image = $('<img>').attr('src', data);
                    $(document.getSelection().anchorNode.parentNode).append(image);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                }
            });
        }


        @if(!(strpos(request()->url(),'/item/')|| strpos(request()->url(),'/operate/order/add/')|| strpos(request()->url(),'/operate/order/add-re-order/')))
            $('form').bootstrapValidator({
            live: 'enable',
            submitted: 'enable'
        });
        @endif
    </script>
    @yield('sub_scripts')
@endsection
