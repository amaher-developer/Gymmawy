<?php


Route::post('client-get-sms-balance', 'Api\ClientApiController@getSMSBalance')->middleware('api');
Route::post('client-send-sms', 'Api\ClientApiController@sendSMS')->middleware('api');


Route::get('client-software-payments/{token}', 'Api\ClientSoftwareApiController@getPayment')->middleware('api');
Route::get('client-software-invoices/{token}', 'Api\ClientSoftwareApiController@getClientInvoices')->middleware('api');
Route::get('client-software-create-payment', 'Api\ClientSoftwareApiController@createPayment')->middleware('api');
Route::any('client-software-store-payment', 'Api\ClientSoftwareApiController@storePayment')->middleware('api')->name('client.sw.payment.callback');
