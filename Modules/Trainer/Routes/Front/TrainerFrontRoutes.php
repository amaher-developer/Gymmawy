<?php

Route::prefix('user/trainer')
    ->middleware(['auth'])
    ->group(function () {

        Route::name('showUserTrainer')
            ->get('show', 'Front\TrainerFrontController@show');
        Route::name('editUserTrainer')
            ->get('edit', 'Front\TrainerFrontController@edit');
        Route::name('editUserTrainer')
            ->post('edit', 'Front\TrainerFrontController@update');

    });


Route::prefix('')
    ->group(function () {
        Route::name('trainers')
            ->get('trainers/', 'Front\TrainerFrontController@trainers');
        Route::name('trainer')
            ->get('trainer/{id}/{slug}', 'Front\TrainerFrontController@trainer');
    });

Route::get('trainers', 'Front\TrainerFrontController@search')->name('trainers');
Route::post('trainers-by-ajax', 'Front\TrainerFrontController@searchByAjax')->name('trainersByAjax');
Route::post('get-trainer-phone-by-ajax', 'Front\TrainerFrontController@getTrainerPhoneByAjax')->name('getTrainerPhoneByAjax');