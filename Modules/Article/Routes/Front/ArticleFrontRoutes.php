<?php

Route::prefix('user/article')
    ->middleware(['auth'])
    ->group(function () {

Route::name('listUserArticle')
    ->get('/', 'Front\ArticleFrontController@index');
Route::name('createUserArticle')
    ->get('create', 'Front\ArticleFrontController@create');
Route::name('storeUserArticle')
    ->post('create', 'Front\ArticleFrontController@store');
Route::name('editUserArticle')
    ->get('{article}/edit', 'Front\ArticleFrontController@edit');
Route::name('editUserArticle')
    ->post('{article}/edit', 'Front\ArticleFrontController@update');
Route::name('deleteUserArticle')
    ->get('{article}/delete', 'Front\ArticleFrontController@destroy');

});



Route::prefix('')
    ->group(function () {
        Route::name('articles')
            ->get('articles/', 'Front\ArticleFrontController@articles');
        Route::name('articles.tag')
            ->get('articles/tag/{tag}', 'Front\ArticleFrontController@articleTags');
        Route::name('articleCategory')
            ->get('articles/{category_id}/{slug}', 'Front\ArticleFrontController@articles');
        Route::name('article')
            ->get('article/{id}/{slug}', 'Front\ArticleFrontController@article');
    });
