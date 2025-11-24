<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => 'article'], function () {
    Route::get('/', function () {
        dd('This is the Article module index page. Build something great!');
    });
});

            
foreach (File::allFiles(__DIR__ . '/Admin') as $route) {
require_once $route->getPathname();
}
Route::group(array('middleware' => 'front','prefix' => (request()->segment(1) == 'ar' || request()->segment(1) == 'en') ? request()->segment(1) : ''), function () {
    foreach (File::allFiles(__DIR__ . '/Front') as $route) {
        require_once $route->getPathname();
    }
});