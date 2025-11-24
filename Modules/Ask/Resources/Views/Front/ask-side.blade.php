
<div class="widget">
    <form method="get" action="{{route('asks')}}">
        <div class="form-group">
            <input type="text" name="search" id="search" class="form-control" placeholder="{{trans('global.search')}}..." value="{{@request('search')}}">
        </div>
        <button type="submit" id="submit" class="btn_1 rounded"> {{trans('global.search')}}</button>
        <a  style="@if($lang == 'ar') float:left; @else float:right; @endif padding-top: 15px;"  href="{{route('createQuestionAsk')}}"><i class="icon-question"></i> {{trans('global.add_question')}}</a>

    </form>

</div>
<!-- /widget -->

<div class="widget">
    <div class="widget-title">
        <h4><b>{{trans('global.categories')}}</b></h4>
    </div>
    <ul class="cats">

        @foreach($article_categories as $article_category)
            <li>
                <a href="{{route('askCategory', [$article_category->id, $article_category->slug])}}">{{$article_category->name}}
                    {{--                                        <span>({{$article_category->articles}})</span>--}}
                </a></li>
        @endforeach
    </ul>
</div>
<!-- /widget -->
<div class="widget">
    <div class="widget-title">
        <h4><b>{{trans('global.tags')}}</b></h4>
    </div>
    <div class="tags">
        @foreach($get_tags as $get_tag)
            <a href="{{route('asks', ['tag' =>$get_tag])}}">{{$get_tag}}</a>
        @endforeach
    </div>
</div>
<!-- /widget -->
