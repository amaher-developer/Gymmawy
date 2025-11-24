<?php

Route::prefix('operate/ask')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listAsk')
        ->get('/', 'Admin\AskAdminController@index')
        ->middleware(['permission:super|ask-index']);
    Route::name('createAsk')
        ->get('create', 'Admin\AskAdminController@create')
        ->middleware(['permission:super|ask-create']);
    Route::name('storeAsk')
        ->post('create', 'Admin\AskAdminController@store')
        ->middleware(['permission:super|ask-create']);
    Route::name('editAsk')
        ->get('{ask}/edit', 'Admin\AskAdminController@edit')
        ->middleware(['permission:super|ask-edit']);
    Route::name('editAsk')
        ->post('{ask}/edit', 'Admin\AskAdminController@update')
        ->middleware(['permission:super|ask-edit']);
    Route::name('deleteAsk')
        ->get('{ask}/delete', 'Admin\AskAdminController@destroy')
        ->middleware(['permission:super|ask-destroy']);



    Route::name('listAskAnswer')
        ->get('/answer', 'Admin\AskAdminController@indexAnswer')
        ->middleware(['permission:super|ask-answer-index']);
    Route::name('createAskAnswer')
        ->get('answer/create', 'Admin\AskAdminController@createAnswer')
        ->middleware(['permission:super|ask-answer-create']);
    Route::name('storeAskAnswer')
        ->post('answer/create', 'Admin\AskAdminController@storeAnswer')
        ->middleware(['permission:super|ask-answer-create']);
    Route::name('editAskAnswer')
        ->get('answer/{ask}/edit', 'Admin\AskAdminController@editAnswer')
        ->middleware(['permission:super|ask-answer-edit']);
    Route::name('editAskAnswer')
        ->post('answer/{ask}/edit', 'Admin\AskAdminController@updateAnswer')
        ->middleware(['permission:super|ask-answer-edit']);
    Route::name('deleteAskAnswer')
        ->get('answer/{ask}/delete', 'Admin\AskAdminController@destroyAnswer')
        ->middleware(['permission:super|ask-answer-destroy']);

});
