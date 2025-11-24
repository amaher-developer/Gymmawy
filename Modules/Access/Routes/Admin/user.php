<?php

Route::prefix('operate/user')
    ->middleware(['auth'])
    ->group(function () {
        Route::name('listUser')
            ->get('/', 'Admin\UserAdminController@index')
            ->middleware(['permission:super|user-index']);
        Route::name('createUser')
            ->get('create', 'Admin\UserAdminController@create')
            ->middleware(['permission:super|user-create']);
        Route::name('storeUser')
            ->post('create', 'Admin\UserAdminController@store')
            ->middleware(['permission:super|user-create']);
        Route::name('editUser')
            ->get('{user}/edit', 'Admin\UserAdminController@edit')
            ->middleware(['permission:super|user-edit']);

        Route::name('editUser')
            ->post('{user}/edit', 'Admin\UserAdminController@update')
            ->middleware(['permission:super|user-edit']);

        Route::name('deleteUser')
            ->get('{user}/delete', 'Admin\UserAdminController@destroy')
            ->middleware(['permission:super|user-destroy']);
    });


Route::prefix('operate/admin')->group(function () {
    Route::name('listAdmins')->get('', 'Admin\AdminAdminController@index')->middleware(['permission:super|admin-index']);
    Route::name('createAdmin')->get('create', 'Admin\AdminAdminController@create')->middleware(['permission:super|admin-create']);
    Route::name('storeAdmin')->post('create', 'Admin\AdminAdminController@store')->middleware(['permission:super|admin-create']);
    Route::name('editAdmin')->get('{admin}/edit', 'Admin\AdminAdminController@edit')->middleware(['permission:super|admin-edit']);
    Route::name('editAdmin')->post('{admin}/edit', 'Admin\AdminAdminController@update')->middleware(['permission:super|admin-edit']);
    Route::name('deleteAdmin')->get('{admin}/delete', 'Admin\AdminAdminController@destroy')->middleware(['permission:super|admin-delete']);
    Route::name('blockAdmin')->get('{admin}/block', 'Admin\AdminAdminController@toggleBlock')->middleware(['permission:super|admin-toggleblock']);
    Route::name('AdminLog')->get('log', 'Admin\AdminAdminController@AdminLog')->middleware(['permission:super']);

});