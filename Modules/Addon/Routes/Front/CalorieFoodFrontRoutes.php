<?php

Route::prefix('')
    ->group(function () {
        Route::name('calories')
            ->get('calories/', 'Front\CalorieFoodFrontController@calories');
        Route::name('calorieCategory')
            ->get('calories/{category_id}/{slug}', 'Front\CalorieFoodFrontController@calories');
    });