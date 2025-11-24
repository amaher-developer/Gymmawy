<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('operate', 'Admin\DashboardAdminController@showHome')
    ->middleware(['permission:super|dashboard-show']);

Route::name('noJs')->get('noJs', 'Admin\DashboardAdminController@noJs');

Route::name('backupDB')->get('operate/db-backup', 'Admin\DashboardAdminController@backupDb')->middleware(['permission:super|dashboard']);

Route::name('uploadImageForCKEditorAjax')->post('upload-ckeditor-ajax', 'Admin\GenericAdminController@uploadImageForCKEditorAjax') ->middleware(['auth']);

//$router->get(config('l5-swagger.routes.api'), [
//    'as' => 'l5-swagger.api',
//    'middleware' => config('l5-swagger.routes.middleware.api', []),
//    'uses' => 'SwaggerController@api',
//]);
//
//$router->any(config('l5-swagger.routes.docs').'/{jsonFile?}', [
//    'as' => 'l5-swagger.docs',
//    'middleware' => config('l5-swagger.routes.middleware.docs', []),
//    'uses' => 'SwaggerController@docs',
//]);
//
//$router->get(config('l5-swagger.routes.docs').'/asset/{asset}', [
//    'as' => 'l5-swagger.asset',
//    'middleware' => config('l5-swagger.routes.middleware.asset', []),
//    'uses' => 'SwaggerAssetController@index',
//]);
//
//$router->get(config('l5-swagger.routes.oauth2_callback'), [
//    'as' => 'l5-swagger.oauth2_callback',
//    'middleware' => config('l5-swagger.routes.middleware.oauth2_callback', []),
//    'uses' => 'SwaggerController@oauth2Callback',
//]);


//Route::name('rss')->get('/rss', 'Front\MainFrontController@rss');

Route::group(array('middleware' => 'front','prefix' => (request()->segment(1) == 'ar' || request()->segment(1) == 'en') ? request()->segment(1) : ''), function () {
    foreach (File::allFiles(__DIR__ . '/Front') as $route) {
        require_once $route->getPathname();
    }
});
            
foreach (File::allFiles(__DIR__ . '/Admin') as $route) {
require_once $route->getPathname();
}