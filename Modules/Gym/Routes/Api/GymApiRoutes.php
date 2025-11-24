<?php


Route::post('gyms', 'Api\GymApiController@gyms')->middleware('auth:api');
Route::post('map-gyms', 'Api\GymApiController@gymsOnMap')->middleware('auth:api');
Route::post('gyms-with-keyword', 'Api\GymApiController@gymsWithKeyword')->middleware('auth:api');
Route::post('gyms-with-filter', 'Api\GymApiController@gymsWithFilter')->middleware('auth:api');
Route::post('gym-categories', 'Api\GymApiController@categories')->middleware('auth:api');
Route::post('gym', 'Api\GymApiController@gym')->middleware('auth:api');
Route::post('my-gym', 'Api\GymApiController@myGym')->middleware('auth:api');
Route::post('gym-update', 'Api\GymApiController@update')->middleware('auth:api');
Route::post('gym-image-delete', 'Api\GymApiController@deleteImage')->middleware('auth:api');
Route::post('gym-common', 'Api\GymApiController@gymCommon')->middleware('auth:api');
Route::post('gym-favorite', 'Api\GymApiController@favorite')->middleware('auth:api');
Route::post('gym-favorite-delete', 'Api\GymApiController@deleteFavorite')->middleware('auth:api');

//Route::prefix('api/gym')
//    ->middleware(['api'])
//    ->group(function () {
//});
