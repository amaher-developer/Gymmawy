<?php

Route::prefix('operate/gym-advice')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listGymAdvice')
        ->get('/', 'Admin\GymAdviceAdminController@index')
        ->middleware(['permission:super|gym-advice-index']);
    Route::name('createGymAdvice')
        ->get('create', 'Admin\GymAdviceAdminController@create')
        ->middleware(['permission:super|gym-advice-create']);
    Route::name('storeGymAdvice')
        ->post('create', 'Admin\GymAdviceAdminController@store')
        ->middleware(['permission:super|gym-advice-create']);


        Route::name('editGymAdvice')
            ->get('{advice}/edit', 'Admin\GymAdviceAdminController@edit')
            ->middleware(['permission:super|gym-advice-edit']);
        Route::name('editGymAdvice')
            ->post('{advice}/edit', 'Admin\GymAdviceAdminController@update')
            ->middleware(['permission:super|gym-advice-edit']);
        Route::name('deleteGymAdvice')
            ->get('{advice}/delete', 'Admin\GymAdviceAdminController@destroy')
            ->middleware(['permission:super|gym-advice-destroy']);

});
