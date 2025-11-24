<?php

Route::prefix('operate/newsletter')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listNewsletter')
        ->get('/', 'Admin\NewsletterAdminController@index')
        ->middleware(['permission:super|newsletter-index']);
    Route::name('createNewsletter')
        ->get('create', 'Admin\NewsletterAdminController@create')
        ->middleware(['permission:super|newsletter-create']);
    Route::name('storeNewsletter')
        ->post('create', 'Admin\NewsletterAdminController@store')
        ->middleware(['permission:super|newsletter-create']);

    Route::name('selectAreaForPromotionLetter')
        ->get('promotion-letter-select-district', 'Admin\NewsletterAdminController@select_area')
        ->middleware(['permission:super|newsletter-create']);

    Route::name('createPromotionLetter')
        ->post('promotion-letter-create', 'Admin\NewsletterAdminController@create_promotion_letter')
        ->middleware(['permission:super|newsletter-create']);

    Route::name('sendPromotionLetter')
        ->post('promotion-letter-send', 'Admin\NewsletterAdminController@send_promotion_letter')
        ->middleware(['permission:super|newsletter-create']);


});
