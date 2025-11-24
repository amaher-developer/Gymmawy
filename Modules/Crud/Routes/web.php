<?php

Route::prefix('operate/modules')->group(function () {
    Route::name('listModules')->get('', 'CrudController@index')->middleware(['permission:super']);
    Route::name('createModule')->get('create', 'CrudController@create')->middleware(['permission:super']);
    Route::name('storeModule')->post('', 'CrudController@store')->middleware(['permission:super']);
    Route::name('showModule')->get('{slug}', 'CrudController@show')->middleware(['permission:super']);
    Route::name('editModule')->get('{slug}/edit', 'CrudController@edit')->middleware(['permission:super']);
    Route::name('editModule')->post('{slug}/edit', 'CrudController@update')->middleware(['permission:super']);
    Route::name('addController')->post('{slug}/addController', 'CrudController@addController')->middleware(['permission:super']);
    Route::name('addController')->post('{slug}/addController', 'CrudController@addController')->middleware(['permission:super']);
    Route::name('addRequest')->post('{slug}/addRequest', 'CrudController@addRequest')->middleware(['permission:super']);
    Route::name('addModel')->post('{slug}/addModel', 'CrudController@addModel')->middleware(['permission:super']);
    Route::name('addMigration')->post('{slug}/addMigration', 'CrudController@addMigration')->middleware(['permission:super']);
    Route::name('addMiddleware')->post('{slug}/addMiddleware', 'CrudController@addMiddleware')->middleware(['permission:super']);
    Route::name('addSeeder')->post('{slug}/addSeeder', 'CrudController@addSeeder')->middleware(['permission:super']);
    Route::name('reverseStatus')->get('{slug}/reverse', 'CrudController@reverseStatus')->middleware(['permission:super']);
    Route::name('addSubModule')->post('{slug}/addSubModule', 'CrudController@addSubModule')->middleware(['permission:super']);
    Route::name('runMigration')->post('{slug}/runMigration', 'CrudController@runMigration')->middleware(['permission:super']);
    Route::name('resetMigration')->post('{slug}/resetMigration', 'CrudController@resetMigration')->middleware(['permission:super']);
    Route::name('refreshMigration')->post('{slug}/refreshMigration', 'CrudController@refreshMigration')->middleware(['permission:super']);
    Route::name('rollbackMigration')->post('{slug}/rollbackMigration', 'CrudController@rollbackMigration')->middleware(['permission:super']);

    Route::name('create-zip')->get('create-zip/{module}/', 'ZipArchiveController@export')->middleware(['permission:super']);
    Route::name('import-zip')->get('import-zip/{module}/', 'ZipArchiveController@import')->middleware(['permission:super']);

});
