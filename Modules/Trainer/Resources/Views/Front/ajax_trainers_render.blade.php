@if(count($trainers) > 0)
    @foreach($trainers as $key =>$trainer)
        <div class="col-md-4 isotope-item @if(($key+1) % 2 == 0) latest @else popular @endif">
            <div class="box_grid">
                <figure>
                    <a href="#sign-in-dialog"
                       id="favorite_2_{{$trainer->id}}" onclick="
                    @if(@$currentUser && $trainer->favorites && @in_array($currentUser->id, @$trainer->favorites->pluck('user_id')->toArray())) removeFavorite('{{$trainer->id}}', 2); return false;
                    @else addFavorite('{{$trainer->id}}', 2); return false;  @endif
                            "
                       class="
                            @if(!@$currentUser) login sign-in-form @endif wish_bt
                            @if($trainer->favorites && @in_array($currentUser->id, $trainer->favorites->pluck('user_id')->toArray())) liked @endif
                               "
                    ></a>
                    <a href="{{route('trainer', [$trainer->id, $trainer->slug])}}">
                        <img src="{{$trainer->image_thumbnail}}" class="img-fluid" alt="" width="800" height="533">
                        <div class="read_more"><span>{{trans('global.details')}}</span></div>
                    </a>
                    <small>{{$trainer->gym_name}}</small>
                </figure>
                <div class="wrapper">
                    {{--                <div class="cat_star"><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i></div>--}}
                    <h3><a href="{{route('trainer', [$trainer->id, $trainer->slug])}}">{{$trainer->name}}</a></h3>
{{--                    <span class="price">{{@$trainer->city->name}}</span>--}}
                </div>

                <ul>
                    <li><i class="icon-location"></i>  {{@$trainer->city->name}}</li>
                    <li><i class="icon-user"></i>  {{@$trainer->gender_name}}</li>
                </ul>
                {{--            <ul class="share-buttons">--}}
                {{--                @if($trainer->facebook)--}}
                {{--                    <li><a class="phone-share" href="{{$trainer->facebook}}" target="_blank"--}}
                {{--                           title=""><i class="icon_phone"--}}
                {{--                                       aria-hidden="true"></i></a></li>--}}
                {{--                @endif--}}
                {{--                @if($trainer->facebook)--}}
                {{--                    <li><a class="fb-share" href="{{$trainer->facebook}}" target="_blank"--}}
                {{--                           title="facebook"><i class="social_facebook"--}}
                {{--                                               aria-hidden="true"></i></a></li>--}}
                {{--                @endif--}}
                {{--                @if($trainer->twitter)--}}
                {{--                    <li><a class="twitter-share" href="{{$trainer->twitter}}" target="_blank"--}}
                {{--                           title="twitter"><i class="social_twitter" aria-hidden="true"></i></a>--}}
                {{--                    </li>--}}
                {{--                @endif--}}
                {{--                @if($trainer->instagram)--}}
                {{--                    <li><a class="instagram-share" href="{{$trainer->instagram}}" target="_blank"--}}
                {{--                           title="instagram"><i class="social_instagram"--}}
                {{--                                                aria-hidden="true"></i></a></li>--}}
                {{--                @endif--}}
                {{--                @if($trainer->linkedin)--}}
                {{--                    <li><a class="linkedin-share" href="{{$trainer->linkedin}}" target="_blank"--}}
                {{--                           title="linkedin"><i class="social_linkedin"--}}
                {{--                                               aria-hidden="true"></i></a></li>--}}
                {{--                @endif--}}
                {{--            </ul>--}}
            </div>
        </div>
        <!-- /box_grid -->
        <span class="aa"></span>
        <script>$('.btn-more').show();</script>
    @endforeach

    <input type="hidden" name="pager" id="pager" class="pager" data-page="{{$pager}}" value="{{$pager}}">

@endif