<?php

Route::prefix('operate/banner')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listBanner')
        ->get('/', 'Admin\BannerAdminController@index')
        ->middleware(['permission:super|banner-index']);
    Route::name('createBanner')
        ->get('create', 'Admin\BannerAdminController@create')
        ->middleware(['permission:super|banner-create']);
    Route::name('storeBanner')
        ->post('create', 'Admin\BannerAdminController@store')
        ->middleware(['permission:super|banner-create']);
    Route::name('editBanner')
        ->get('{banner}/edit', 'Admin\BannerAdminController@edit')
        ->middleware(['permission:super|banner-edit']);
    Route::name('editBanner')
        ->post('{banner}/edit', 'Admin\BannerAdminController@update')
        ->middleware(['permission:super|banner-edit']);
    Route::name('deleteBanner')
        ->get('{banner}/delete', 'Admin\BannerAdminController@destroy')
        ->middleware(['permission:super|banner-destroy']);
});
