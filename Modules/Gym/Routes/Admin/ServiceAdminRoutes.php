<?php

Route::prefix('operate/service')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listService')
        ->get('/', 'Admin\ServiceAdminController@index')
        ->middleware(['permission:super|service-index']);
    Route::name('createService')
        ->get('create', 'Admin\ServiceAdminController@create')
        ->middleware(['permission:super|service-create']);
    Route::name('storeService')
        ->post('create', 'Admin\ServiceAdminController@store')
        ->middleware(['permission:super|service-create']);
    Route::name('editService')
        ->get('{service}/edit', 'Admin\ServiceAdminController@edit')
        ->middleware(['permission:super|service-edit']);
    Route::name('editService')
        ->post('{service}/edit', 'Admin\ServiceAdminController@update')
        ->middleware(['permission:super|service-edit']);
    Route::name('deleteService')
        ->get('{service}/delete', 'Admin\ServiceAdminController@destroy')
        ->middleware(['permission:super|service-destroy']);
});
