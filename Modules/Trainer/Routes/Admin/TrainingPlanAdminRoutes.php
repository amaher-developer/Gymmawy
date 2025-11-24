<?php

Route::prefix('operate/training-plan')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listTrainingPlan')
        ->get('/', 'Admin\TrainingPlanAdminController@index')
        ->middleware(['permission:super|training_plan-index']);
    Route::name('createTrainingPlan')
        ->get('create', 'Admin\TrainingPlanAdminController@create')
        ->middleware(['permission:super|training_plan-create']);
    Route::name('storeTrainingPlan')
        ->post('create', 'Admin\TrainingPlanAdminController@store')
        ->middleware(['permission:super|training_plan-create']);
    Route::name('editTrainingPlan')
        ->get('{plan}/edit', 'Admin\TrainingPlanAdminController@edit')
        ->middleware(['permission:super|training_plan-edit']);
    Route::name('editTrainingPlan')
        ->post('{plan}/edit', 'Admin\TrainingPlanAdminController@update')
        ->middleware(['permission:super|training_plan-edit']);
    Route::name('deleteTrainingPlan')
        ->get('{plan}/delete', 'Admin\TrainingPlanAdminController@destroy')
        ->middleware(['permission:super|training_plan-destroy']);
});
