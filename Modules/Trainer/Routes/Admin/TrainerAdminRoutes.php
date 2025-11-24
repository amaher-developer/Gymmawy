<?php

Route::prefix('operate/trainer')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listTrainer')
        ->get('/', 'Admin\TrainerAdminController@index')
        ->middleware(['permission:super|trainer-index']);
    Route::name('createTrainer')
        ->get('create', 'Admin\TrainerAdminController@create')
        ->middleware(['permission:super|trainer-create']);
    Route::name('storeTrainer')
        ->post('create', 'Admin\TrainerAdminController@store')
        ->middleware(['permission:super|trainer-create']);
    Route::name('editTrainer')
        ->get('{trainer}/edit', 'Admin\TrainerAdminController@edit')
        ->middleware(['permission:super|trainer-edit']);
    Route::name('editTrainer')
        ->post('{trainer}/edit', 'Admin\TrainerAdminController@update')
        ->middleware(['permission:super|trainer-edit']);
    Route::name('deleteTrainer')
        ->get('{trainer}/delete', 'Admin\TrainerAdminController@destroy')
        ->middleware(['permission:super|trainer-destroy']);
});
