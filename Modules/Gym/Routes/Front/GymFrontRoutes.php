<?php

Route::prefix('user/gym/branch')
    ->middleware(['auth'])
    ->group(function () {

        Route::name('uploadUserGymMainImage')
            ->post('upload-mainimage', 'Front\GymFrontController@uploadMainImage');

        Route::name('listUserGym')
            ->get('/', 'Front\GymFrontController@index');
        Route::name('createUserGym')
            ->get('create', 'Front\GymFrontController@create');
        Route::name('storeUserGym')
            ->post('create', 'Front\GymFrontController@store');
        Route::name('editUserGym')
            ->get('{id}/edit', 'Front\GymFrontController@edit');
        Route::name('editUserGym')
            ->post('{id}/edit', 'Front\GymFrontController@update');
        Route::name('deleteUserGym')
            ->get('{id}/delete', 'Front\GymFrontController@destroy');


        Route::name('uploadUserGymImages')
            ->post('upload-images', 'Front\GymFrontController@uploadImages');

    });