<?php

Route::prefix('')
    ->group(function () {
        Route::name('calorieCategories')
            ->get('calorie-categories/', 'Front\CalorieCategoryFrontController@categories');
    });
