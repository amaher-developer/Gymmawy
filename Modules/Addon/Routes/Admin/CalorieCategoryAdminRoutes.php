<?php

Route::prefix('operate/caloriecategory')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listCalorieCategory')
        ->get('/', 'Admin\CalorieCategoryAdminController@index')
        ->middleware(['permission:super|calorie-category-index']);
    Route::name('createCalorieCategory')
        ->get('create', 'Admin\CalorieCategoryAdminController@create')
        ->middleware(['permission:super|calorie-category-create']);
    Route::name('storeCalorieCategory')
        ->post('create', 'Admin\CalorieCategoryAdminController@store')
        ->middleware(['permission:super|calorie-category-create']);
    Route::name('editCalorieCategory')
        ->get('{caloriecategory}/edit', 'Admin\CalorieCategoryAdminController@edit')
        ->middleware(['permission:super|calorie-category-edit']);
    Route::name('editCalorieCategory')
        ->post('{caloriecategory}/edit', 'Admin\CalorieCategoryAdminController@update')
        ->middleware(['permission:super|calorie-category-edit']);
    Route::name('deleteCalorieCategory')
        ->get('{caloriecategory}/delete', 'Admin\CalorieCategoryAdminController@destroy')
        ->middleware(['permission:super|calorie-category-destroy']);
});
