<?php

Route::prefix('operate/city')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listCity')
        ->get('/', 'Admin\CityAdminController@index')
        ->middleware(['permission:super|city-index']);
    Route::name('createCity')
        ->get('create', 'Admin\CityAdminController@create')
        ->middleware(['permission:super|city-create']);
    Route::name('storeCity')
        ->post('create', 'Admin\CityAdminController@store')
        ->middleware(['permission:super|city-create']);
    Route::name('editCity')
        ->get('{city}/edit', 'Admin\CityAdminController@edit')
        ->middleware(['permission:super|city-edit']);
    Route::name('editCity')
        ->post('{city}/edit', 'Admin\CityAdminController@update')
        ->middleware(['permission:super|city-edit']);
    Route::name('deleteCity')
        ->get('{city}/delete', 'Admin\CityAdminController@destroy')
        ->middleware(['permission:super|city-destroy']);
});
