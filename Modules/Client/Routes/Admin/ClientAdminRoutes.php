<?php

Route::prefix('operate/client')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listClient')
        ->get('/', 'Admin\ClientAdminController@index')
        ->middleware(['permission:super|client-index']);
    Route::name('createClient')
        ->get('create', 'Admin\ClientAdminController@create')
        ->middleware(['permission:super|client-create']);
    Route::name('storeClient')
        ->post('create', 'Admin\ClientAdminController@store')
        ->middleware(['permission:super|client-create']);
    Route::name('migrateClient')
        ->get('{client}/migrate', 'Admin\ClientAdminController@migrate')
        ->middleware(['permission:super|client-edit']);
    Route::name('editClient')
        ->get('{client}/edit', 'Admin\ClientAdminController@edit')
        ->middleware(['permission:super|client-edit']);
    Route::name('editClient')
        ->post('{client}/edit', 'Admin\ClientAdminController@update')
        ->middleware(['permission:super|client-edit']);
    Route::name('deleteClient')
        ->get('{client}/delete', 'Admin\ClientAdminController@destroy')
        ->middleware(['permission:super|client-destroy']);
});
