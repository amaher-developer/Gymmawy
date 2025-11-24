<?php

Route::prefix('operate/tag')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listTag')
        ->get('/', 'Admin\TagAdminController@index')
        ->middleware(['permission:super|tag-index']);
    Route::name('createTag')
        ->get('create', 'Admin\TagAdminController@create')
        ->middleware(['permission:super|tag-create']);
    Route::name('storeTag')
        ->post('create', 'Admin\TagAdminController@store')
        ->middleware(['permission:super|tag-create']);
    Route::name('editTag')
        ->get('{tag}/edit', 'Admin\TagAdminController@edit')
        ->middleware(['permission:super|tag-edit']);
    Route::name('editTag')
        ->post('{tag}/edit', 'Admin\TagAdminController@update')
        ->middleware(['permission:super|tag-edit']);
    Route::name('deleteTag')
        ->get('{tag}/delete', 'Admin\TagAdminController@destroy')
        ->middleware(['permission:super|tag-destroy']);
});
