@extends('generic::layouts.list')
@section('list_title') {{ @$title }} @endsection
@section('breadcrumb')
        <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/operate') }}">{{trans('admin.home')}}</a>
        <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">{{ $title }}</a>
        </li>
        </ul>
@endsection
@section('list_add_button')
    <a href="{{route('createArticle')}}" class="btn btn-lg btn-success">{{trans('admin.add')}}</a>
     @if(request('trashed'))
            <a href="{{route('listArticle')}}" class="btn btn-lg btn-info">{{trans('admin.enabled')}}</a>
        @else
            <a href="{{route('listArticle')}}?trashed=1" class="btn btn-lg btn-danger">{{trans('admin.disabled')}}</a>
        @endif
            <a href="" url="{{request()->fullUrlWithQuery(['export'=>1])}}" id="export" class="btn red btn-outline"><i class="icon-paper-clip"></i> {{trans('admin.export')}}</a>
@endsection
@section('page_body')
    <div class="row">

        <button class="btn btn-info filter_trigger_button" style="margin-bottom: 10px">{{trans('admin.show_hide_filters')}}</button>

        <form action="" id="filter_form">
            <table class="table table-striped table-bordered table-hover ">
                <tbody>
                <tr>
                    <th>{{trans('admin.id')}}</th>
                    <td><input id="id" value="{{ request('id')}}" name="id" class="form-control"
                               type="number" placeholder="{{trans('admin.id')}}"/></td>
                </tr>
                <tr>
                    <th>{{trans('admin.enabled')}}</th>
                    <td><select name="published" id="published" class="form-control" >
                            <option value="">{{trans('admin.choose')}}</option>
                            <option value="0" class=""
                                    @if(isset($_GET['published']) && (request('published') === '0')) selected @endif>{{trans('admin.disabled')}}</option>
                            <option value="1"
                                    @if(request('published') && (request('published') == 1)) selected @endif>{{trans('admin.enabled')}}</option>
                        </select></td>
                </tr>
                <tr>
                    <th>{{trans('admin.order_by')}}</th>
                    <td><select name="order_by" id="order_by" class="form-control" >
                            <option value="">{{trans('admin.choose')}}</option>
                            <option value="date" class=""
                                    @if(isset($_GET['order_by']) && (request('order_by') == 'date')) selected @endif>{{trans('admin.date')}}</option>
                            <option value="views"
                                    @if(request('order_by') && (request('order_by') == 'views')) selected @endif>{{trans('admin.views')}}</option>
                        </select></td>
                </tr>
                <tr>
                    <th>{{trans('admin.limit')}}</th>
                    <td><input name="limit" id="limit" class="form-control" type="number" value="{{@request('limit')}}" />
                            </td>
                </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-offset-9 col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn green form-control">{{trans('admin.apply')}}</button>
                    </div>
                </div>
            </div>


        </form>

        <div class="row">
            <div class="col-md-6">
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

    <table class="table table-striped table-bordered table-hover" >
        <thead>
        <tr class="">
            <th>#</th>
            <th>id</th>
            <th>{{trans('admin.title')}}</th>
            <th>{{trans('admin.image')}}</th>
            <th>{{trans('admin.view')}}</th>
            <th>{{trans('global.status')}}</th>
            <th>{{trans('admin.actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($articles as $key=> $article)
            <tr>
                <td> {{ $key+1 }}</td>
                <td> {{ $article->id }}</td>
                <td> {{ $article->title }}</td>
                <td> <img src="{{ $article->image }}" style="height: 120px;width: 120px"> </td>
                <td> {{ $article->views }}</td>
                <td>
                    {!! $article->published == '1' ? '<span style="color:green"><i class="fa fa-check"></i></span>' : '<span style="color:red"><i class="fa fa-times"></i></span>' !!}
                    {!! $article->for_mobile == '1' ? '<span style="padding: 0 5px"><i class="fa fa-mobile"></i></span>' : '' !!}
                    {!! $article->is_backlinks == '1' ? '<span style="padding: 0 5px"><i class="fa fa-link"></i></span>' : '' !!}
                    {!! $article->calculates != null ? '<span style="padding: 0 5px"><i class="fa fa-calculator"></i></span>' : '' !!}
                </td>
                <td>
                    <a href="{{route('article',[$article->id, $article->slug])}}" target="_blank" class="btn btn-xs yellow">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a data-toggle="modal" data-target="#myModal{{$article->id}}" target="_blank" class="btn btn-xs yellow">
                        <i class="fa fa-share"></i>
                    </a>
                    <a href="{{route('editArticle',$article->id)}}" class="btn btn-xs yellow">
                        <i class="fa fa-edit"></i>
                    </a>
                    @if(request('trashed'))
                        <a title="{{trans('admin.enable')}}" href="{{route('deleteArticle',$article->id)}}" class="confirm_delete btn btn-xs green">
                            <i class="fa fa-check-circle"></i>
                        </a>
                    @else
                        <a title="{{trans('admin.disable')}}" href="{{route('deleteArticle',$article->id)}}" class="confirm_delete btn btn-xs red">
                            <i class="fa fa-times"></i>
                        </a>
                    @endif
                </td>
            </tr>
            <!-- Modal -->
            <div id="myModal{{$article->id}}" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Share</h4>
                        </div>
                        <div class="modal-body" style=" @if($article->language == 'en') direction: ltr; @else direction: rtl; @endif">
                            @php
                                $meta_keywords = ($mainSettings['meta_keywords_'.$article->language]);
                                shuffle($meta_keywords);
                                $i=0;
                                $keywords = [];
                                foreach($meta_keywords as $keyword){
                                    $i++;
                                    $keywords[] = '#'.str_replace(' ', '_', trim($keyword));
                                    if($i == 10) break;
                                }
                                $keywords = implode(' ', $keywords);
                                $post = $article->title.'<br/><br/>'.$article->short_description.'<br/><br/>'.asset('article/'.$article->id.'/'.$article->slug).'<br/><br/>'.$keywords;
                                $post = str_replace('<br/>', '
', $post);
                            @endphp
                            <textarea rows="10 " style="width: 100%;" id="myPost{{$article->id}}">{!! $post !!}</textarea>
                            <br/><br/><br/><br/>
                            <a href="javasript::void(0)" onclick="myFunction({{$article->id}})">نسخ البوست</a> | <a href="{{$article->image}}"  download target="_blank">{{trans('sw.download_image')}}</a>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">x</button>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
        </tbody>
    </table>
     <div class="col-lg-5 col-md-5 col-md-offset-5">
                {!! $articles->appends($search_query)->render()  !!}
            </div>
        </div>
@endsection

@section('scripts')
    @parent

    <script>

        $(document).on('click', '#export', function (event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('url'),
                cache: false,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    var a = document.createElement("a");
                    a.href = response.file;
                    a.download = response.name;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                },
                error: function (request, error) {
                    swal("Operation failed", "Something went wrong.", "error");
                    console.error("Request: " + JSON.stringify(request));
                    console.error("Error: " + JSON.stringify(error));
                }
            });

        });

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

        function myFunction(id) {
            /* Get the text field */
            var copyText = document.getElementById("myPost"+id);

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            navigator.clipboard.writeText(copyText.value);

            /* Alert the copied text */
            // alert("Copied the text: " + copyText.value);
        }

    </script>

@endsection
