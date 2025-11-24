<?php

Route::name('home')->get('/', 'Front\MainFrontController@index');
Route::name('about')->get('/about', 'Front\MainFrontController@about');
Route::name('policy')->get('/policy', 'Front\MainFrontController@policy');
Route::name('terms')->get('/terms', 'Front\MainFrontController@terms');

Route::name('mobile-policy')->get('/mobile-policy', 'Front\MainFrontController@mobilePolicy');
Route::name('mobile-terms')->get('/mobile-terms', 'Front\MainFrontController@mobileTerms');

Route::name('favorites')->get('/favorites', 'Front\MainFrontController@favorites')->middleware(['auth']);
Route::name('searchRedirect')->get('search-redirect', 'Front\MainFrontController@searchRedirect');
Route::name('setCurrentArea')->post('set-area', 'Front\MainFrontController@setCurrentArea');


Route::name('contact')->get('/contact', 'Front\MainFrontController@contactCreate');
Route::name('contact')->post('/contact', 'Front\MainFrontController@contactStore');
Route::name('feedback')->post('/feedback', 'Front\MainFrontController@feedbackStore');
Route::name('newsletter')->post('/newsletter', 'Front\MainFrontController@newsletter');

Route::name('thanks')->get('/thanks', 'Front\MainFrontController@thanks');

//Route::name('rss')->get('/rss', 'Front\MainFrontController@rss');
Route::name('sitemap')->get('/sitemap', 'Front\MainFrontController@sitemap');


Route::name('add-watermark')->get('/add-watermark', 'Front\MainFrontController@createWatermark');
Route::name('add-watermark')->post('/add-watermark', 'Front\MainFrontController@storeWatermark');

Route::post('add-favorite-by-ajax', 'Front\MainFrontController@addFavoriteByAjax')->name('addFavoriteByAjax');
Route::post('remove-favorite-by-ajax', 'Front\MainFrontController@removeFavoriteByAjax')->name('removeFavoriteByAjax');

Route::prefix('user')
    ->middleware(['auth'])
    ->group(function () {

        Route::name('dashboard')
            ->get('', 'Front\MainFrontController@home');

});
