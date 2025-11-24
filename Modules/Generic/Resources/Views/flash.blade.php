@if(session()->has('sweet_flash_message'))
    <script>
        swal({
            title: "{{session('sweet_flash_message.title')}}",
            text: "{{session('sweet_flash_message.message')}}",
            type: "{{session('sweet_flash_message.type')}}",
            timer: 4000,
            confirmButtonText: 'Ok',
        });
    </script>
@endif

@if(session()->has('sweet_flash_message_overlay'))
    <script>
        swal({
            title: "{{session('sweet_flash_message_overlay.title')}}",
            text: "{{session('sweet_flash_message_overlay.message')}}",
            type: "{{session('sweet_flash_message_overlay.type')}}",
            confirmButtonText: 'Ok',
        });
    </script>
@endif
{{--<script>--}}
    {{--$("form").submit(function (event) {--}}
        {{--swal({--}}
            {{--title: "Submitting ...",--}}
            {{--imageUrl: "{{asset('resources/assets/admin/global/img/loading2.gif')}}",--}}
            {{--imageSize: "180x180",--}}
            {{--showConfirmButton: false--}}
        {{--});--}}
    {{--});--}}
{{--</script>--}}