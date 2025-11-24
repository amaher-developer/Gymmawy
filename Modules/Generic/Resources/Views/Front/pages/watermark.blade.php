@extends('generic::Front.layouts.master')
@section('title'){{ $title }} | @endsection
@section('style')
    <style>
        .hero_in.contacts:before {
            background: url({{asset('resources/assets/front/img/bg/contact.jpg')}}) center center no-repeat;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    </style>
@endsection
@section('content')



    <main>
        <section class="hero_in contacts">
            <div class="wrapper">
                <div class="container">
                    <h1 class="fadeInUp"><span></span>{{trans('global.watermark')}}</h1>
                </div>
            </div>
        </section>
        <!--/hero_in-->



        <div class="bg_color_1">
            <div class="container margin_80_55">
                <div class="row justify-content-between">
                    {{--                    <div class="col-lg-5">--}}
                    {{--                        <div class="map_contact">--}}
                    {{--                        </div>--}}
                    {{--                        <!-- /map -->--}}
                    {{--                    </div>--}}
                    <div class="col-lg-12">

                        @if(@$new_image)

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a onclick='downloadBase64File("{{$new_image}}")' >
                                            <img id="preview" src="{{$new_image}}" style="text-align: center;height: 300px;object-fit: contain;border: 1px solid #c2cad8;" alt="preview image" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <p class="add_top_30"><input type="button" value="{{trans('global.download')}}" class="btn_1 rounded" onclick='downloadBase64File("{{$new_image}}")'> <a href="" style="margin:0 100px">{{trans('global.reset')}}</a></p>


                        @else

                        @include('generic::errors')

                        <form method="post" action="{{route('add-watermark')}}"  enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <!-- /row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{trans('global.image')}} <span class="required">*</span></label>
                                        <input id="image" value="{{asset('resources/assets/front/img/preview_icon.png')}}"
                                               name="image" type="file" class="form-control" >
                                        <br/>
                                        <img id="preview" src="{{asset('resources/assets/front/img/preview_icon.png')}}" style="text-align: center;height: 300px;object-fit: contain;border: 1px solid #c2cad8;" alt="preview image" />
                                    </div>
                                </div>
                            </div>
                            <!-- /row -->
                                <div class="clearfix"><hr/></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{trans('global.position')}} <span class="required">*</span></label>
                                        <br/><br/>
                                        <ul>
                                            <li><input name="position" type="radio" value="1" > {{trans('global.right')}} - {{trans('global.top')}}</li>
                                            <li><input name="position" type="radio" value="2"  > {{trans('global.right')}} - {{trans('global.bottom')}}</li>
                                            <li><input name="position" type="radio" value="3" > {{trans('global.left')}} - {{trans('global.top')}}</li>
                                            <li><input name="position" type="radio" value="4"  checked> {{trans('global.left')}} - {{trans('global.bottom')}}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /row -->


                            <p class="add_top_30"><input type="submit" value="{{trans('global.send')}}" class="btn_1 rounded"></p>
                        </form>

                        @endif
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /bg_color_1 -->
    </main>
    <!--/main-->











@endsection

@section('style')



@endsection

@section('script')

<script>
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image").change(function() {
        readURL(this);
    });

    function downloadBase64File( base64Data) {
        console.log('dd', base64Data);
        const linkSource = `${base64Data}`;
        const downloadLink = document.createElement("a");
        downloadLink.href = linkSource;
        downloadLink.download = 'download.png';
        downloadLink.click();
    }
</script>
    @endsection

