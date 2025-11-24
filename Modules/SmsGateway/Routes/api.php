<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/sms-gateway', function (Request $request) {
    // return $request->sms-gateway();
})->middleware('auth:api');

Route::any('/mobily', 'Admin\MobilyAdminController@sentSms')->middleware('api');
Route::any('/clickatell-test', 'Admin\ClickatellAdminController@sentSmsTest')->middleware('api');