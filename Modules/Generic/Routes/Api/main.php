<?php

Route::any('home', 'Api\GenericApiController@home')->middleware('auth:api');
Route::any('contact', 'Api\GenericApiController@contact')->middleware('auth:api');
Route::any('my-favorites', 'Api\GenericApiController@myFavorites')->middleware('auth:api');
Route::any('my-notifications', 'Api\GenericApiController@myNotifications')->middleware('auth:api');
Route::any('splash', 'Api\GenericApiController@splash')->middleware('api');
Route::any('log_errors', 'Api\GenericApiController@logErrors')->middleware('api');
Route::any('update_push_token', 'Api\GenericApiController@updatePushToken')->middleware('api');

Route::post('calorie-categories', 'Api\AddonApiController@calorieCategories')->middleware('auth:api');
Route::post('calorie-foods', 'Api\AddonApiController@calorieFoods')->middleware('auth:api');

Route::post('save-backup', 'Api\MainApiController@saveBackupDB')->middleware('api');;

Route::group(['middleware' => 'auth:api'], function(){
//    Route::get('/settings', function () {
//        return \App\Modules\Generic\Models\Setting::all();
//    });

});
