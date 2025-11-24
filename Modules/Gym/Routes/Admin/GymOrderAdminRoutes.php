<?php

Route::prefix('operate/gymorder')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listGymOrder')
        ->get('/', 'Admin\GymOrderAdminController@index')
        ->middleware(['permission:super|gym-order-index']);
    Route::name('createGymOrder')
        ->get('create', 'Admin\GymOrderAdminController@create')
        ->middleware(['permission:super|gym-order-create']);
    Route::name('storeGymOrder')
        ->post('create', 'Admin\GymOrderAdminController@store')
        ->middleware(['permission:super|gym-order-create']);
    Route::name('editGymOrder')
        ->get('{gymorder}/edit', 'Admin\GymOrderAdminController@edit')
        ->middleware(['permission:super|gym-order-edit']);
    Route::name('editGymOrder')
        ->post('{gymorder}/edit', 'Admin\GymOrderAdminController@update')
        ->middleware(['permission:super|gym-order-edit']);
    Route::name('deleteGymOrder')
        ->get('{gymorder}/delete', 'Admin\GymOrderAdminController@destroy')
        ->middleware(['permission:super|gym-order-destroy']);
});
