<?php

Route::prefix('operate/caloriefood')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listCalorieFood')
        ->get('/', 'Admin\CalorieFoodAdminController@index')
        ->middleware(['permission:super|calorie-food-index']);
    Route::name('createCalorieFood')
        ->get('create', 'Admin\CalorieFoodAdminController@create')
        ->middleware(['permission:super|calorie-food-create']);
    Route::name('storeCalorieFood')
        ->post('create', 'Admin\CalorieFoodAdminController@store')
        ->middleware(['permission:super|calorie-food-create']);
    Route::name('editCalorieFood')
        ->get('{caloriefood}/edit', 'Admin\CalorieFoodAdminController@edit')
        ->middleware(['permission:super|calorie-food-edit']);
    Route::name('editCalorieFood')
        ->post('{caloriefood}/edit', 'Admin\CalorieFoodAdminController@update')
        ->middleware(['permission:super|calorie-food-edit']);
    Route::name('deleteCalorieFood')
        ->get('{caloriefood}/delete', 'Admin\CalorieFoodAdminController@destroy')
        ->middleware(['permission:super|calorie-food-destroy']);
});
