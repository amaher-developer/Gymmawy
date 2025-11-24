<?php

Route::prefix('operate/gym')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listGym')
        ->get('/', 'Admin\GymAdminController@index')
        ->middleware(['permission:super|gym-index']);
    Route::name('createGym')
        ->get('create', 'Admin\GymAdminController@create')
        ->middleware(['permission:super|gym-create']);
    Route::name('storeGym')
        ->post('create', 'Admin\GymAdminController@store')
        ->middleware(['permission:super|gym-create']);
    Route::name('editGym')
        ->get('{gym}/edit', 'Admin\GymAdminController@edit')
        ->middleware(['permission:super|gym-edit']);
    Route::name('editGym')
        ->post('{gym}/edit', 'Admin\GymAdminController@update')
        ->middleware(['permission:super|gym-edit']);
    Route::name('deleteGym')
        ->get('{gym}/delete', 'Admin\GymAdminController@destroy')
        ->middleware(['permission:super|gym-destroy']);


        Route::name('uploadAdminGymImages')
            ->post('upload-images', 'Admin\GymAdminController@uploadImages')
            ->middleware(['permission:super|gym-destroy']);

        Route::name('uploadAdminGymMainImage')
            ->post('upload-mainimage', 'Admin\GymAdminController@uploadMainImage')
            ->middleware(['permission:super|gym-destroy']);


        Route::name('getWebsiteGym')
            ->get('/getWebsiteGym', 'Admin\GymAdminController@getWebsiteContent')
            ->middleware(['permission:super']);


        Route::name('uploadAdminGymImages')
            ->post('admin-upload-images', 'Admin\GymAdminController@uploadImages');

        Route::name('saveCommentAjax')
            ->post('save-comment-ajax', 'Admin\GymAdminController@saveCommentAjax');
});
