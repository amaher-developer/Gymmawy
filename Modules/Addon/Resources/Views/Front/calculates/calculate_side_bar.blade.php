<li><a href="{{route('calculateIBW')}}" @if(\Route::currentRouteName() == 'calculateIBW') class="active" @endif><i class="icon_document_alt"></i>{{trans('global.calculate_ibw')}}</a></li>
<li><a href="{{route('calculateCalories')}}" @if(\Route::currentRouteName() == 'calculateCalories') class="active" @endif><i class="icon_document_alt"></i>{{trans('global.calculate_calories')}}</a></li>
<li><a href="{{route('calculateBMI')}}" @if(\Route::currentRouteName() == 'calculateBMI') class="active" @endif><i class="icon_document_alt"></i>{{trans('global.calculate_bmi')}}</a></li>
<li><a href="{{route('calculateWater')}}" @if(\Route::currentRouteName() == 'calculateWater') class="active" @endif><i class="icon_document_alt"></i>{{trans('global.calculate_water')}}</a></li>

