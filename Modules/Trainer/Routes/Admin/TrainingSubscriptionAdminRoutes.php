<?php

Route::prefix('operate/training-subscription/{user}')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listTrainingSubscription')
        ->get('/', 'Admin\TrainingSubscriptionAdminController@index')
        ->middleware(['permission:super|training_subscription-index']);
    Route::name('createTrainingSubscription')
        ->get('create', 'Admin\TrainingSubscriptionAdminController@create')
        ->middleware(['permission:super|training_subscription-create']);
    Route::name('storeTrainingSubscription')
        ->post('create', 'Admin\TrainingSubscriptionAdminController@store')
        ->middleware(['permission:super|training_subscription-create']);
    Route::name('editTrainingSubscription')
        ->get('{client}/edit', 'Admin\TrainingSubscriptionAdminController@edit')
        ->middleware(['permission:super|training_subscription-edit']);
    Route::name('editTrainingSubscription')
        ->post('{client}/edit', 'Admin\TrainingSubscriptionAdminController@update')
        ->middleware(['permission:super|training_subscription-edit']);
    Route::name('deleteTrainingSubscription')
        ->get('{client}/delete', 'Admin\TrainingSubscriptionAdminController@destroy')
        ->middleware(['permission:super|training_subscription-destroy']);
});
