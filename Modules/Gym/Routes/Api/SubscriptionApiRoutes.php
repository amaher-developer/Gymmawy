<?php

Route::post('gym-check-for-subscription', 'Api\SubscriptionApiController@checkForSubscription')->middleware('auth:api');
Route::post('gym-subscriptions', 'Api\SubscriptionApiController@subscriptions')->middleware('auth:api');
Route::post('gym-delete-subscriptions', 'Api\SubscriptionApiController@deleteSubscription')->middleware('auth:api');

Route::post('gym-subscription-add-from-sw', 'Api\SubscriptionApiController@addFromSW')->middleware('api');
Route::post('gym-subscription-send-notification-to-member-from-sw', 'Api\SubscriptionApiController@sendNotificationToMemberFromSW')->middleware('api');
Route::post('gym-subscription-get-members-list', 'Api\SubscriptionApiController@listOfSubscriptions')->middleware('api');

//Route::prefix('api/subscription')
//    ->middleware(['api'])
//    ->group(function () {
//});
