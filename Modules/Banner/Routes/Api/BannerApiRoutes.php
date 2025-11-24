<?php

Route::post('banners', 'Api\BannerApiController@banners')->middleware('auth:api');

//Route::prefix('api/banner')
//    ->middleware(['api'])
//    ->group(function () {
//});
