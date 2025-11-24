<?php

Route::prefix('user/store')
    ->middleware(['auth'])
    ->group(function () {

        Route::name('listUserStore')
            ->get('/', 'Front\StoreFrontController@index');
        Route::name('createUserStore')
            ->get('create', 'Front\StoreFrontController@create');
        Route::name('storeUserStore')
            ->post('create', 'Front\StoreFrontController@store');
        Route::name('editUserStore')
            ->get('{store}/edit', 'Front\StoreFrontController@edit');
        Route::name('editUserStore')
            ->post('{store}/edit', 'Front\StoreFrontController@update');
        Route::name('deleteUserStore')
            ->get('{store}/delete', 'Front\StoreFrontController@destroy');

    });
