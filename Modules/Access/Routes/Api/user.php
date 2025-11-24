<?php

Route::any('addresses', 'Api\UserAddressApiController@index')->middleware('api');
Route::any('addresses_delivery_days', 'Api\UserAddressApiController@addressesDeliveryDays')->middleware('api');
Route::any('address/store', 'Api\UserAddressApiController@store')->middleware('api');
Route::any('address/update', 'Api\UserAddressApiController@update')->middleware('api');
Route::any('address/delete', 'Api\UserAddressApiController@disable')->middleware('api');

