@extends('generic::layouts.user_list')
@section('list_title') {{ @$title }} @endsection
@section('styles')
    @if($lang == 'ar')
        <link href="{{asset('resources/assets/admin/pages/css/blog-rtl.css')}}" rel="stylesheet" type="text/css"/>
    @else
        <link href="{{asset('resources/assets/admin/pages/css/blog.css')}}" rel="stylesheet" type="text/css"/>
    @endif
@endsection
@section('breadcrumb')
        <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">{{trans('admin.home')}}</a>
        <i class="fa fa-circle"></i>
        </li>
        <li>
            {{ $title }}
        </li>
        </ul>
@endsection
@section('list_add_button')
    <a href="{{route('createUserArticle')}}" class="btn btn-lg btn-success">{{trans('admin.article_add')}}</a>

@endsection
@section('page_body')


    <div class="row">
        <div class="col-md-6 col-sm-6">
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

    @if(count($articles) < 1)
        <div class="alert alert-danger"><p class="text-center">{{trans('admin.no_records')}}</p></div>
    @else

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12  col-sm-12 blog-page">
            <div class="row">
                <div class="col-md-9 col-sm-12 article-block">

                    @foreach($articles as $key=> $article)
                    <div class="row">
                        <div class="col-md-4  col-sm-4 blog-img blog-tag-data">
                            <img src="{{$article->image}}" alt="" class="img-responsive">
                            <ul class="list-inline">
                                <li>
                                    <i class="fa fa-calendar"></i>
                                    <a href="#">
                                        {{$article->arabic_date}} </a>
                                </li>
                                <li>
                                    <i class="fa fa-tags"></i>
                                    <a href="#">{{@$article->category->name}} </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-8  col-sm-8 blog-article">
                            <h3>
                                <a @if(!$article->deleted_at && (@$article->published == 1)) href="{{route('article', [$article->id, $article->slug])}}"  target="_blank" @endif>
                                    {{$article->title}} </a>
                            </h3>
                            <p>
                                {{$article->short_description}}
                            </p>

                            <a href="{{route('editUserArticle',$article->id)}}" title="{{trans('admin.edit')}}" class="btn yellow">
                               {{trans('admin.edit')}} <i class="fa fa-edit"></i>
                            </a>
                            @if(@$article->deleted_at)
                                <a title="{{trans('admin.enable')}}"  title="{{trans('admin.enable')}}"   href="{{route('deleteUserArticle',$article->id)}}"   class=" btn green">
                                    {{trans('admin.enable')}} <i class="fa fa-check-circle"></i>
                                </a>
                            @else
                                <a title="{{trans('admin.disable')}}"  title="{{trans('admin.disable')}}" onclick="return confirm('{{trans('admin.are_you_sure')}}');" href="{{route('deleteUserArticle',$article->id)}}"  class=" btn red">
                                    {{trans('admin.disable')}} <i class="fa fa-times"></i>
                                </a>
                            @endif
                            <br/><br/>
                            <div> @if(@$article->published == 0) {!! '<span style="color:red;">'.trans('admin.not_review').'</span>' !!} @else {!! '<span style="color:green;">'.trans('admin.reviewed').'</span>' !!} @endif</div>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                </div>
                <!--end col-md-9-->
            </div>
            <div class="row">
            <div class="col-lg-5 col-md-5 col-md-offset-5">
                {!! $articles->appends($search_query)->render()  !!}
            </div>
            </div>
        </div>
    </div>

    @endif

@endsection

@section('scripts')
    @parent

    <script>

        $("#filter_form").slideUp();
        $(".filter_trigger_button").click(function () {
            $("#filter_form").slideToggle(300);
        });

        $(document).on('click', '.remove_filter', function (event) {
            event.preventDefault();
            var filter = $(this).attr('id');
            $("#" + filter).val('');
            $("#filter_form").submit();
        });


    </script>

@endsection