<?php

Route::prefix('operate/apilog')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listApiLog')
        ->get('/', 'Admin\ApiLogAdminController@index')
        ->middleware(['permission:super|api-log-index']);
    Route::name('deleteApiLog')
        ->get('{apilog}/delete', 'Admin\ApiLogAdminController@destroy')
        ->middleware(['permission:super|api-log-destroy']);
});
