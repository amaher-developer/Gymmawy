<?php

Route::prefix('operate/bodybuilder')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listBodybuilder')
        ->get('/', 'Admin\BodybuilderAdminController@index')
        ->middleware(['permission:super|bodybuilder-index']);
    Route::name('createBodybuilder')
        ->get('create', 'Admin\BodybuilderAdminController@create')
        ->middleware(['permission:super|bodybuilder-create']);
    Route::name('storeBodybuilder')
        ->post('create', 'Admin\BodybuilderAdminController@store')
        ->middleware(['permission:super|bodybuilder-create']);
    Route::name('editBodybuilder')
        ->get('{bodybuilder}/edit', 'Admin\BodybuilderAdminController@edit')
        ->middleware(['permission:super|bodybuilder-edit']);
    Route::name('editBodybuilder')
        ->post('{bodybuilder}/edit', 'Admin\BodybuilderAdminController@update')
        ->middleware(['permission:super|bodybuilder-edit']);
    Route::name('deleteBodybuilder')
        ->get('{bodybuilder}/delete', 'Admin\BodybuilderAdminController@destroy')
        ->middleware(['permission:super|bodybuilder-destroy']);
});
