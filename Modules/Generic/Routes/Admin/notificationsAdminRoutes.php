<?php

Route::prefix('operate/admin_notifications')
    ->middleware(['auth'])
    ->group(function () {
        Route::name('getNewAdminNotifications')
            ->get('', 'Admin\GenericAdminController@checkForNewNotifications')
            ->middleware(['permission:super|generic-notification-check']);

    });
