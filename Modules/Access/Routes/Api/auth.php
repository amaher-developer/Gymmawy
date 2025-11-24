<?php


Route::any('login', 'Api\AuthApiController@login')->middleware('api');
Route::any('register', 'Api\AuthApiController@register')->middleware('api');
Route::any('update-profile', 'Api\AuthApiController@update_profile')->middleware('auth:api');
Route::any('my-user', 'Api\AuthApiController@myUser')->middleware('auth:api');
Route::any('reset-password', 'Api\AuthApiController@reset_user_password')->middleware('api');





