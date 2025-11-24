@if(count($records) > 0)
    @foreach($records as $key =>$record)
        <div class="col-md-4 isotope-item @if(($key+1) % 2 == 0) latest @else popular @endif">
            <div class="box_grid">
                <figure>
                    <a href="#sign-in-dialog"
                       id="favorite_1_{{$record->id}}" onclick="
                    @if(@$currentUser && $record->favorites && @in_array($currentUser->id, $record->favorites->pluck('user_id')->toArray())) removeFavorite('{{$record->id}}', 1); return false;
                    @else addFavorite('{{$record->id}}', 1); return false;  @endif
                            "
                       class="
                                                    @if(!@$currentUser) login sign-in-form @endif wish_bt
                                                    @if($record->favorites && @in_array($currentUser->id, $record->favorites->pluck('user_id')->toArray())) liked @endif
                               "
                    ></a>
                    <a href="{{route('gym', [$record->id, $record->slug])}}">
                        <img src="{{$record->image_thumbnail}}" class="img-fluid" alt="" width="800" height="533">
                        <div class="read_more"><span>{{trans('global.details')}}</span></div>
                    </a>
                    <small>{{@$record->categories[0]->name}}</small>
                </figure>
                <div class="wrapper">
{{--                    <div class="cat_star">--}}
{{--                        <i class="icon_star"></i>--}}
{{--                        <i class="icon_star"></i>--}}
{{--                        <i class="icon_star"></i>--}}
{{--                        <i class="icon_star"></i>--}}
{{--                    </div>--}}
                    <h3><a href="{{route('gym', [$record->id, $record->slug])}}">{{$record->name}}</a></h3>
                    <span class="price">{{@$record->district->name}}, {{@$record->district->city->name}}</span>
                </div>
                <ul>
                    <li><i class="ti-eye"></i> {{$record->views}} {{trans('global.views')}}</li>
                    <li>
                        <div class="score">
                            <span>{{trans('global.articles')}}</span><strong>{{(int)$record->articles}}</strong></div>
                    </li>
                    {{--                <li><div class="score"><span>Superb<em>350 Reviews</em></span><strong>8.9</strong></div></li>--}}
                </ul>
            </div>
        </div>
        <!-- /box_grid -->
        <span class="aa"></span>
        <script>$('.btn-more').show();</script>
    @endforeach
    <input type="hidden" name="pager" id="pager" class="pager" data-page="{{$pager}}" value="{{$pager}}">
@endif