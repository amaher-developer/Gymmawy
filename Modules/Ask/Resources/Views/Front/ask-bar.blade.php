<style>
    .secondary_nav li{
        font-weight: bolder !important;
        /*padding: 0 10px;*/
    }
    li a.active {
        color: #fc5b62 !important;
        /*color: rgba(0,0,0,.9);*/
    }
</style>
<nav class="secondary_nav sticky_horizontal">
    <div class="container">
        <ul class="clearfix">

            <li><a href="{{route('asks')}}" class="ask-href @if(in_array(\Request::route()->getName(), ['asks', 'ask']) ) active @endif"><i class="icon-list"></i> {{trans('global.questions')}}</a></li>
            <li><a href="{{route('asks.tags')}}" class="ask-href @if(\Request::route()->getName() == 'asks.tags') active @endif"><i class="icon-tags-2"></i> {{trans('global.tags')}}</a></li>
            <li><a href="{{route('createQuestionAsk')}}" class="ask-href @if(\Request::route()->getName() == 'createQuestionAsk') active @endif"><i class="icon-question"></i> {{trans('global.add_question')}}</a></li>
            <li> </li>
        </ul>
    </div>
</nav>
