<?php

Route::post('training-check-for-subscription', 'Api\TrainingSubscriptionApiController@checkForSubscription')->middleware('auth:api');
Route::post('training-subscriptions', 'Api\TrainingSubscriptionApiController@subscriptions')->middleware('auth:api');
Route::post('training-subscription', 'Api\TrainingSubscriptionApiController@subscription')->middleware('auth:api');

//Route::prefix('api/subscription')
//    ->middleware(['api'])
//    ->group(function () {
//});
