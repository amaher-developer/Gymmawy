@extends('generic::layouts.user_form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listUserArticle') }}">{{trans('admin.my_articles')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            {{ $title }}
        </li>
    </ul>
@endsection
@section('form_title') {{ @$title }} @endsection
@section('page_body')
    <style>
        .note-group-select-from-files {display: none !important;}
    </style>
    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data" >
        <div class="form-body right-text" >
            {{csrf_field()}}
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.article_language')}} <span class="required">*</span></label>
                <div class="col-md-9">
                    <select name="language" id="language" class="form-control" required>
                        <option value="">{{trans('admin.choose')}}</option>
                            <option value="ar" @if('ar' == $article->language) selected="" @endif>{{trans('admin.arabic')}}</option>
                            <option value="en" @if('en' == $article->language) selected="" @endif>{{trans('admin.english')}}</option>

                    </select>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label text-right">{{trans('admin.category')}} <span class="required">*</span></label>
                <div class="col-md-9">
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">{{trans('admin.choose')}}</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" @if($category->id == $article->category_id) selected="" @endif>{{$category->name}}</option>
                        @endforeach

                    </select>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.article_title')}} <span class="required">*</span></label>
                <div class="col-md-9">
                    <input id="title" value="{{ old('title', $article->title) }}" required
                           name="title" type="text" class="form-control" >
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.description')}} <span class="required">*</span></label>
                <div class="col-md-9">
                 <textarea id="description"
                           name="description" class="form-control  summernote-textarea" required>{!!old('description', $article->description)!!}</textarea>
                </div>
            </div>
            <div style="clear: both;"></div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.image')}} <span class="required">*</span></label>
                <div class="col-md-8">
                    <input id="image" value="{{ old('image', $article->image) }}" @if(!$article->image) required @endif
                           name="image"  type="file" class="form-control" ><br/>
                    {{--<a href="{{ $article->image }}" class="fancybox-button" data-rel="fancybox-button">--}}
                        <img id="preview" src="@if($article->image) {{$article->image}} @else {{asset('resources/assets/front/img/preview_icon.png')}} @endif"
                             style="height: 120px;width: 320px;object-fit: contain;border: 1px solid #c2cad8;object-fit: cover;"
                             alt="preview image" />
                    {{--</a>--}}
                </div>
                {{--@if(!empty($article->image))--}}
                    {{--<label class="col-md-1 control-label">--}}
                        {{--<a href="{{ $article->image }}" class="fancybox-button" data-rel="fancybox-button">--}}
                            {{--{{trans('admin.view')}}--}}
                        {{--</a>--}}
                    {{--</label>--}}
                {{--@endif--}}
            </div>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.youtube_link')}}</label>
                <div class="col-md-9">
                    <input id="youtube" dir="ltr" value="@if($article->youtube) {{ old('youtube', $article->youtube_link) }} @endif"
                           name="youtube" type="text" placeholder="https://www.youtube.com/watch?v=xxxxxxxx" class="form-control" ><br/>
                    <a href="{{$article->youtube_link}}" target="_blank">
                    <img  src="@if($article->youtube_link) {{$article->youtube_image}} @else {{asset('resources/assets/front/img/youtube_logo.png')}} @endif" style="height: 120px;object-fit: contain;border: 1px solid #c2cad8;" alt="preview image" />
                    </a>
                </div>
            </div>

            {{--<div class="form-group col-md-12" style="clear:both;"><hr/></div>--}}

            {{--<div class="form-group col-md-12" style="clear:both;">--}}
                {{--<label class="col-md-2 control-label" style="padding-top: 14px;">{{trans('admin.enable')}}</label>--}}
                {{--<div class="col-md-10">--}}
                    {{--<div class="mt-checkbox-list">--}}
                        {{--<label class="mt-checkbox mt-checkbox-outline">--}}
                            {{--<input type="hidden" name="published" value="">--}}
                            {{--<input type="checkbox" value="{{ old('published', @$gym->published)  }}" name="published"--}}
                                   {{--@if(@$gym->published) {{ $gym->published?'checked':'' }} @else checked @endif>--}}
                            {{--<span></span>--}}
                        {{--</label>--}}
                    {{--</div>--}}
                {{--</div>--}}

            {{--</div>--}}

            <div class="form-actions" style="clear:both;">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">{{trans('admin.submit')}}</button>
                        <input type="reset" class="btn default" value="{{trans('admin.reset')}}">
                    </div>
                </div>
            </div>
        </div>
    </form>



@endsection
@section('sub_scripts')
    {{--<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>    --}}
    {{--<script src="{{asset('resources/assets/admin/global/plugins/fancybox/source/jquery.fancybox.js')}}"></script>--}}
    <script>
        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#image").change(function() {
            readURL(this);
        });
    </script>
    @endsection
