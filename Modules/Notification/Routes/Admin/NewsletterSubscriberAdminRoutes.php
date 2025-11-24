<?php

Route::prefix('operate/newslettersubscriber')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listNewsletterSubscriber')
        ->get('/', 'Admin\NewsletterSubscriberAdminController@index')
        ->middleware(['permission:super|newsletter-subscriber-index']);
    Route::name('createNewsletterSubscriber')
        ->get('create', 'Admin\NewsletterSubscriberAdminController@create')
        ->middleware(['permission:super|newsletter-subscriber-create']);
    Route::name('storeNewsletterSubscriber')
        ->post('create', 'Admin\NewsletterSubscriberAdminController@store')
        ->middleware(['permission:super|newsletter-subscriber-create']);
    Route::name('editNewsletterSubscriber')
        ->get('{newslettersubscriber}/edit', 'Admin\NewsletterSubscriberAdminController@edit')
        ->middleware(['permission:super|newsletter-subscriber-edit']);
    Route::name('editNewsletterSubscriber')
        ->post('{newslettersubscriber}/edit', 'Admin\NewsletterSubscriberAdminController@update')
        ->middleware(['permission:super|newsletter-subscriber-edit']);
    Route::name('deleteNewsletterSubscriber')
        ->get('{newslettersubscriber}/delete', 'Admin\NewsletterSubscriberAdminController@destroy')
        ->middleware(['permission:super|newsletter-subscriber-destroy']);
});
