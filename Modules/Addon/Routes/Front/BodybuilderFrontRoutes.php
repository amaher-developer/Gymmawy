<?php


Route::prefix('')
    ->group(function () {
        Route::name('bodybuilder')
            ->get('bodybuilder/{id}/{slug}', 'Front\BodybuilderFrontController@bodybuilder');
        Route::name('bodybuilders')
            ->get('bodybuilders', 'Front\BodybuilderFrontController@bodybuilders');
    });
