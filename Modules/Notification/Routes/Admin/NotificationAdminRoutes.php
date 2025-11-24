<?php

Route::prefix('operate/notification')
    ->middleware(['auth'])
    ->group(function () {

        Route::name('listNotification')
            ->get('/', 'Admin\NotificationAdminController@index')
            ->middleware(['permission:super|notification-index']);

        Route::name('createNotification')
            ->get('create', 'Admin\NotificationAdminController@create')
            ->middleware(['permission:super|notification-create']);

        Route::name('showNotification')
            ->get('/{notification}', 'Admin\NotificationAdminController@show')
            ->middleware(['permission:super|notification-show']);

        Route::name('storeNotification')
            ->post('create', 'Admin\NotificationAdminController@push')
            ->middleware(['permission:super|notification-create']);


    });
