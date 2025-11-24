<?php

Route::prefix('operate/district')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listDistrict')
        ->get('/', 'Admin\DistrictAdminController@index')
        ->middleware(['permission:super|district-index']);
    Route::name('createDistrict')
        ->get('create', 'Admin\DistrictAdminController@create')
        ->middleware(['permission:super|district-create']);
    Route::name('storeDistrict')
        ->post('create', 'Admin\DistrictAdminController@store')
        ->middleware(['permission:super|district-create']);
    Route::name('editDistrict')
        ->get('{district}/edit', 'Admin\DistrictAdminController@edit')
        ->middleware(['permission:super|district-edit']);
    Route::name('editDistrict')
        ->post('{district}/edit', 'Admin\DistrictAdminController@update')
        ->middleware(['permission:super|district-edit']);
    Route::name('deleteDistrict')
        ->get('{district}/delete', 'Admin\DistrictAdminController@destroy')
        ->middleware(['permission:super|district-destroy']);
});
