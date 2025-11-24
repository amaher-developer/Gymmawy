@extends('generic::layouts.form')
@section('breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('listArticle') }}">{{trans('admin.articles')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>{{ $title }}</li>
    </ul>
@endsection
@section('sub_styles')
    <link rel="stylesheet" type="text/css" href="{{asset('resources/assets/admin/')}}/global/plugins/select2/select2.css"/>
    <!-- BEGIN THEME STYLES -->
    <link href="{{asset('resources/assets/admin/')}}/global/css/plugins-rtl.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset('resources/assets/admin/')}}/layouts/layout/css/layout-rtl.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}resources/assets/admin/global/plugins/jquery-tags-input/jquery.tagsinput-rtl.css"/>
    <style>
        h2, h3 {
            display: block;
            font-size: 1.5em;
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }
    </style>
@endsection

@section('form_title') {{ @$title }} @endsection
@section('page_body')
    @if(@$article->id)
    <div class="col-xs-2 col-xs-offset-10">
        <a href="{{route('backlinkArticle', $article->id)}}" onclick="return confirm('Are you sure?')" class="btn  btn-warning">{{trans('admin.add')}} {{trans('admin.backlinks')}}</a></div>
    <div class="clearfix"></div>
    @endif

    <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="form-body">
            {{csrf_field()}}

            <div class="form-group col-md-6">
                <label class="col-md-3 control-label text-right">{{trans('admin.article_language')}} <span class="required">*</span></label>
                <div class="col-md-9">
                    <select name="language" id="language" class="form-control" required>
                        <option value="">{{trans('admin.choose')}}</option>
                        <option value="ar" @if('ar' == $article->language) selected="" @endif>{{trans('admin.arabic')}}</option>
                        <option value="en" @if('en' == $article->language) selected="" @endif>{{trans('admin.english')}}</option>

                    </select>
                </div>
            </div>

            <div class="form-group col-md-6">
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
            <div class="form-group col-md-12" style="clear:both;">
                <hr/>
            </div>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.slug')}}</label>
                <div class="col-md-9">
                    <input id="slug" value="{{ old('slug', $article->slug) }}"
                           name="slug" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.title')}}</label>
                <div class="col-md-9">
                    <input id="title" value="{{ old('title', $article->title) }}"
                           name="title" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.short_description')}}</label>
                <div class="col-md-9">
                    <textarea id="short_description"
                              name="short_description" type="text" class="form-control">{{ old('short_description', $article->short_description) }}</textarea>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.description')}}</label>
                <div class="col-md-9">
                 <textarea id="description"
                           name="description"
                           class="form-control
{{--                            summernote-textarea-ar--}}
                            ">{{ old('description', $article->description) }}</textarea>
                </div>
            </div>
            <div style="clear: both;"></div>
            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.image')}}</label>
                <div class="col-md-8">
                    <input id="image" value="{{ old('image', $article->image) }}"
                           name="image" type="file" class="form-control">
                </div>
                @if(!empty($article->image))
                    <label class="col-md-1 control-label">
                        <a href="{{ $article->image }}" class="fancybox-button" data-rel="fancybox-button">
                            view
                        </a>
                    </label>
                @endif
            </div>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.youtube_link')}}</label>
                <div class="col-md-9">
                    <input id="youtube" value="{{ old('youtube', $article->youtube) }}"
                           name="youtube" type="text" class="form-control">
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="control-label col-md-3">{{trans('global.tags')}}</label>
                <div class="col-md-9">
                    <input type="hidden" id="select2_sample5" name="tags" class="form-control select2" value="{{$article->tags ? old('tags', count($article->tags) > 0 ? implode(', ', $article->tags->pluck('name')->toArray()) : "") : ""}}">
                </div>
            </div>




            <div class="form-group col-md-12">
                <label class="control-label col-md-3">{{trans('admin.meta_keywords')}}</label>
                <div class="col-md-9">
                    <input id="tags_1" name="meta_keywords" type="text" class="form-control tags" value="{{isset($article->meta_keywords) ? ($article->meta_keywords) : ''}}" placeholder="{{trans('admin.meta_keywords')}}"/>
                </div>
            </div>

            <div class="form-group col-md-12">
                <label class="col-md-3 control-label">{{trans('admin.meta_description')}}</label>
                <div class="col-md-9">
                    <textarea id="meta_description"
                              name="meta_description" type="text" class="form-control">{{ old('meta_description', $article->meta_description) }}</textarea>
                </div>
            </div>

            <div style="clear: both;"></div>
            <h5 class="form-section"><i class="fa fa-list-ul"></i> {{trans('global.calculates_title')}} <span
                        class="required">*</span></h5>

            <div class="row">
                <div class="form-group col-md-12">
                        <ul style="list-style: none;" class="col-lg-4  col-md-4  col-sm-4">
                            <li>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-10">
                                        <label class="mt-checkbox mt-checkbox-outline">
                                            <input type="checkbox" name="calculates[ibw]" value="1"  @if(@$article->calculates['ibw'] == 1) checked @endif
                                                   > {{trans('global.calculate_ibw')}}
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-10">
                                        <label class="mt-checkbox mt-checkbox-outline">
                                            <input type="checkbox" name="calculates[calories]" value="1" @if(@$article->calculates['calories'] == 1) checked @endif
                                                   > {{trans('global.calculate_calories')}}
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-10">
                                        <label class="mt-checkbox mt-checkbox-outline">
                                            <input type="checkbox" name="calculates[bmi]" value="1" @if(@$article->calculates['bmi'] == 1) checked @endif
                                                   > {{trans('global.calculate_bmi')}}
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-10">
                                        <label class="mt-checkbox mt-checkbox-outline">
                                            <input type="checkbox" name="calculates[water]" value="1" @if(@$article->calculates['water'] == 1) checked @endif
                                                   > {{trans('global.calculate_water')}}
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                        </ul>

                </div>
            </div>
            <div style="clear: both;"></div>
            <div class="form-group col-md-12" style="clear:both;">
                <hr/>
            </div>
            @if(!@$article->published)
                <input type="hidden" name="published" value="0">
                <input type="hidden" name="for_mobile" value="0">
            @else
                <div class="form-group col-md-12" style="clear:both;">
                    <label class="col-md-2 control-label" style="padding-top: 14px;">{{trans('admin.enable')}}</label>
                    <div class="col-md-10">
                        <div class="mt-checkbox-list">
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input type="checkbox" value="1" name="published"
                                @if(@$article->published) {{ $article->published?'checked':'' }} @endif>
                                <span></span>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="form-group col-md-12" style="clear:both;">
                    <label class="col-md-2 control-label" style="padding-top: 14px;">{{trans('admin.enable_mobile')}}</label>
                    <div class="col-md-10">
                        <div class="mt-checkbox-list">
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input type="checkbox" value="1" name="for_mobile"
                                @if(@$article->for_mobile) {{ $article->for_mobile?'checked':'' }}  @endif>
                                <span></span>
                            </label>
                        </div>
                    </div>

                </div>
            @endif



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

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="{{asset('resources/assets/admin/')}}/global/plugins/select2/select2.min.js"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <script src="{{asset('/')}}resources/assets/admin/global/plugins/jquery-tags-input/jquery.tagsinput.min.js" type="text/javascript"></script>

{{--    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>--}}
{{--    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/translations/ar.js"></script>--}}

{{--    <script>--}}
{{--        ClassicEditor--}}
{{--            .create( document.querySelector( '#description' ), {--}}
{{--                language: 'ar',--}}
{{--                ui: 'ar',--}}
{{--                content: 'ar',--}}
{{--                image: {--}}
{{--                    resizeUnit: 'px',--}}
{{--                    resizeOptions: [--}}
{{--                        {--}}
{{--                            name: 'resizeImage:original',--}}
{{--                            label: 'Original',--}}
{{--                            value: null--}}
{{--                        },--}}
{{--                        {--}}
{{--                            name: 'resizeImage:100',--}}
{{--                            label: '100px',--}}
{{--                            value: '100'--}}
{{--                        },--}}
{{--                        {--}}
{{--                            name: 'resizeImage:200',--}}
{{--                            label: '200px',--}}
{{--                            value: '200'--}}
{{--                        }--}}
{{--                    ]--}}
{{--                }--}}
{{--            } )--}}
{{--            .catch( error => {--}}
{{--                console.error( error );--}}
{{--            } );--}}
{{--    </script>--}}

    <script>
        $('#description').summernote({
            tabsize: 2,
            height: 120,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        }).on('summernote.paste', function(e, ne) {
            // let bufferText = ((ne.originalEvent || ne).clipboardData || window.clipboardData).getData('Text');
            // // bufferText  = bufferText.find("*").not("a,img,br").each(function() {
            // //     $(this).replaceWith(this.innerHTML);
            // // });
            // ne.preventDefault();
            // document.execCommand('insertText', false, bufferText);
        });
        $("#select2_sample5").select2({
            tags: [{!! '"' . implode( '","', ($tags) ) . '"' !!}]
        });

        $('#tags_1').tagsInput({
            width: 'auto',
            defaultText: '{{trans('admin.add_keywords')}}',
            'onAddTag': function () {
                //alert(1);
            },
        });
    </script>
@endsection
