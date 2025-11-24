<?php

Route::prefix('operate/training-client')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listTrainingClient')
        ->get('/', 'Admin\TrainingClientAdminController@index')
        ->middleware(['permission:super|training_client-index']);
    Route::name('createTrainingClient')
        ->get('create', 'Admin\TrainingClientAdminController@create')
        ->middleware(['permission:super|training_client-create']);
    Route::name('storeTrainingClient')
        ->post('create', 'Admin\TrainingClientAdminController@store')
        ->middleware(['permission:super|training_client-create']);
    Route::name('editTrainingClient')
        ->get('{client}/edit', 'Admin\TrainingClientAdminController@edit')
        ->middleware(['permission:super|training_client-edit']);
    Route::name('editTrainingClient')
        ->post('{client}/edit', 'Admin\TrainingClientAdminController@update')
        ->middleware(['permission:super|training_client-edit']);
    Route::name('deleteTrainingClient')
        ->get('{client}/delete', 'Admin\TrainingClientAdminController@destroy')
        ->middleware(['permission:super|training_client-destroy']);
});
