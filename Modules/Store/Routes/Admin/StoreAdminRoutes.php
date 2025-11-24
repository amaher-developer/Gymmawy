<?php

Route::prefix('operate/store')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listStore')
        ->get('/', 'Admin\StoreAdminController@index')
        ->middleware(['permission:super|store-index']);
    Route::name('createStore')
        ->get('create', 'Admin\StoreAdminController@create')
        ->middleware(['permission:super|store-create']);
    Route::name('storeStore')
        ->post('create', 'Admin\StoreAdminController@store')
        ->middleware(['permission:super|store-create']);
    Route::name('editStore')
        ->get('{store}/edit', 'Admin\StoreAdminController@edit')
        ->middleware(['permission:super|store-edit']);
    Route::name('editStore')
        ->post('{store}/edit', 'Admin\StoreAdminController@update')
        ->middleware(['permission:super|store-edit']);
    Route::name('deleteStore')
        ->get('{store}/delete', 'Admin\StoreAdminController@destroy')
        ->middleware(['permission:super|store-destroy']);
});
