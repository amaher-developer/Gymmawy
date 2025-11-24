<?php

Route::prefix('')
    ->group(function () {
        Route::name('calculateCalories')
            ->get('calculate-calories/', 'Front\CalorieCalculateFrontController@calories');
        Route::name('calculateCaloriesResult')
            ->post('calculate-calories-result/', 'Front\CalorieCalculateFrontController@caloriesResult');

        Route::name('calculateBMI')
            ->get('calculate-bmi/', 'Front\CalorieCalculateFrontController@bmi');
        Route::name('calculateBMIResult')
            ->post('calculate-bmi-result/', 'Front\CalorieCalculateFrontController@bmiResult');

        Route::name('calculateIBW')
            ->get('calculate-ibw/', 'Front\CalorieCalculateFrontController@ibw');
        Route::name('calculateIBWResult')
            ->post('calculate-ibw-result/', 'Front\CalorieCalculateFrontController@ibwResult');

        Route::name('calculateWater')
            ->get('calculate-water/', 'Front\CalorieCalculateFrontController@water');
        Route::name('calculateWaterResult')
            ->post('calculate-water-result/', 'Front\CalorieCalculateFrontController@waterResult');
    });
