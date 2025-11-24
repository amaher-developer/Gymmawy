<?php

Route::post('calories', 'Api\CalorieFoodApiController@calories')->middleware('auth:api');


//Route::prefix('api/caloriefood')
//    ->middleware(['api'])
//    ->group(function () {
//});
