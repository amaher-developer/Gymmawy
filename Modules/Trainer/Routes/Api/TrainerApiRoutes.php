<?php

Route::post('trainers', 'Api\TrainerApiController@trainers')->middleware('auth:api');
Route::post('trainers-with-keyword', 'Api\TrainerApiController@trainersWithKeyword')->middleware('auth:api');
Route::post('trainer-categories', 'Api\TrainerApiController@categories')->middleware('auth:api');
Route::post('trainer', 'Api\TrainerApiController@trainer')->middleware('auth:api');
Route::post('my-trainer', 'Api\TrainerApiController@myTrainer')->middleware('auth:api');
Route::post('trainer-update', 'Api\TrainerApiController@update')->middleware('auth:api');
Route::post('trainer-favorite', 'Api\TrainerApiController@favorite')->middleware('auth:api');
Route::post('trainer-favorite-delete', 'Api\TrainerApiController@deleteFavorite')->middleware('auth:api');

//Route::prefix('api/trainer')
//    ->middleware(['api'])
//    ->group(function () {
//});
