<?php

Route::prefix('operate/category')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listCategory')
        ->get('/', 'Admin\CategoryAdminController@index')
        ->middleware(['permission:super|category-index']);
    Route::name('createCategory')
        ->get('create', 'Admin\CategoryAdminController@create')
        ->middleware(['permission:super|category-create']);
    Route::name('storeCategory')
        ->post('create', 'Admin\CategoryAdminController@store')
        ->middleware(['permission:super|category-create']);
    Route::name('editCategory')
        ->get('{category}/edit', 'Admin\CategoryAdminController@edit')
        ->middleware(['permission:super|category-edit']);
    Route::name('editCategory')
        ->post('{category}/edit', 'Admin\CategoryAdminController@update')
        ->middleware(['permission:super|category-edit']);
    Route::name('deleteCategory')
        ->get('{category}/delete', 'Admin\CategoryAdminController@destroy')
        ->middleware(['permission:super|category-destroy']);
});
