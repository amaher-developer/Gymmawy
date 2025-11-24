<?php

Route::prefix('operate/setting')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('editSetting')
        ->get('{setting}/edit', 'Admin\SettingAdminController@edit')
        ->middleware(['permission:super|setting-edit']);
    Route::name('editSetting')
        ->post('{setting}/edit', 'Admin\SettingAdminController@update')
        ->middleware(['permission:super|setting-edit']);

    Route::name('listContact')
        ->get('/contact', 'Admin\SettingAdminController@contacts')
        ->middleware(['permission:super|contact-index']);

    Route::name('deleteContact')
        ->get('/contact/{contact}/delete', 'Admin\SettingAdminController@contactDestroy')
        ->middleware(['permission:super|contact-destroy']);

    Route::get('test', 'Admin\GenericAdminController@test')
        ->middleware(['permission:super']);

    Route::name('createWhatsapp')
        ->get('whatsapp', 'Admin\SettingAdminController@whatsapp')
        ->middleware(['permission:super|whatsapp-create']);
    Route::name('storeWhatsapp')
        ->post('whatsapp-store', 'Admin\SettingAdminController@whatsappStore')
        ->middleware(['permission:super|whatsapp-store']);

        Route::name('phonesByAjax')
            ->get('phones-by-ajax', 'Admin\SettingAdminController@phonesByAjax')
            ->middleware(['permission:super|whatsapp-create']);

});
