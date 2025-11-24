<?php

Route::prefix('operate/subscription')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listSubscription')
        ->get('/', 'Admin\SubscriptionAdminController@index')
        ->middleware(['permission:super|subscription-index']);
    Route::name('createSubscription')
        ->get('create', 'Admin\SubscriptionAdminController@create')
        ->middleware(['permission:super|subscription-create']);
    Route::name('storeSubscription')
        ->post('create', 'Admin\SubscriptionAdminController@store')
        ->middleware(['permission:super|subscription-create']);
    Route::name('editSubscription')
        ->get('{subscription}/edit', 'Admin\SubscriptionAdminController@edit')
        ->middleware(['permission:super|subscription-edit']);
    Route::name('editSubscription')
        ->post('{subscription}/edit', 'Admin\SubscriptionAdminController@update')
        ->middleware(['permission:super|subscription-edit']);
    Route::name('deleteSubscription')
        ->get('{subscription}/delete', 'Admin\SubscriptionAdminController@destroy')
        ->middleware(['permission:super|subscription-destroy']);
});
