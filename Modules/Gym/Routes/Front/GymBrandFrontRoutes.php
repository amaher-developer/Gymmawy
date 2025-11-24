<?php

Route::prefix('user/gym')
    ->middleware(['auth'])
    ->group(function () {

        Route::name('showUserGymBrand')
            ->get('show', 'Front\GymBrandFrontController@show');
        Route::name('editUserGymBrand')
            ->get('edit', 'Front\GymBrandFrontController@edit');
        Route::name('editUserGymBrand')
            ->post('edit', 'Front\GymBrandFrontController@update');

        Route::name('createUserGymBrand')
            ->get('create', 'Front\GymBrandFrontController@create');
        Route::name('storeUserGymBrand')
            ->post('create', 'Front\GymBrandFrontController@store');

    });


Route::prefix('gym')
    ->group(function () {

        Route::name('gym')
            ->get('/{id}/{slug}', 'Front\GymFrontController@gym');

    });

Route::get('gyms', 'Front\GymFrontController@gyms')->name('gyms');
Route::any('search-by-ajax', 'Front\GymFrontController@searchByAjax')->name('searchByAjax');
Route::any('gyms-by-json', 'Front\GymFrontController@gymsJson')->name('gymsByJson');
Route::post('get-gym-phone-by-ajax', 'Front\GymFrontController@getGymPhoneByAjax')->name('getGymPhoneByAjax');
Route::post('get-gym-location-phone-by-ajax', 'Front\GymFrontController@getGymLocationPhoneByAjax')->name('getGymLocationPhoneByAjax');