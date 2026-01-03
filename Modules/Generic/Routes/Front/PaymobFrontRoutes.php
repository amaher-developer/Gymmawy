
<?php

Route::prefix('paymob')
    ->middleware(['web'])
    ->group(function () {

    // Paymob callback route
    Route::name('paymob.payment.callback')
        ->any('/callback', 'Front\PaymobFrontController@callback');

    // Paymob cancel route
    Route::name('paymob.payment.cancel')
        ->get('/cancel', 'PaymobFrontController@cancel');

    });
