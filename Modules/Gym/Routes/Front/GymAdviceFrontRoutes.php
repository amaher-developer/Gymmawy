<?php

Route::prefix('')
    ->group(function () {
        Route::name('advices')
            ->get('advices/', 'Front\AdviceFrontController@advices');
        Route::name('advice')
            ->get('advice/{id}/{slug}', 'Front\AdviceFrontController@advice');
    });
