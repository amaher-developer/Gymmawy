<?php

Route::prefix('operate/article')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listArticleImages')
        ->get('article-images', 'Admin\ArticleAdminController@indexImages')
        ->middleware(['permission:super|article-image-index']);
    Route::name('deleteArticleImage')
        ->get('{image}/delete-article-image', 'Admin\ArticleAdminController@deleteArticleImage')
        ->middleware(['permission:super|delete-article-image-index']);

    Route::name('uploadArticleImage')
        ->post('upload-article-image', 'Admin\ArticleAdminController@uploadArticleImage')
        ->middleware(['permission:super|article-image-index']);

    Route::name('listArticle')
        ->get('/', 'Admin\ArticleAdminController@index')
        ->middleware(['permission:super|article-index']);
    Route::name('createArticle')
        ->get('create', 'Admin\ArticleAdminController@create')
        ->middleware(['permission:super|article-create']);
    Route::name('storeArticle')
        ->post('create', 'Admin\ArticleAdminController@store')
        ->middleware(['permission:super|article-create']);
    Route::name('editArticle')
        ->get('{article}/edit', 'Admin\ArticleAdminController@edit');
    Route::name('backlinkArticle')
        ->get('{article}/backlink', 'Admin\ArticleAdminController@backlink')
        ->middleware(['permission:super|article-edit']);
    Route::name('editArticle')
        ->post('{article}/edit', 'Admin\ArticleAdminController@update')
        ->middleware(['permission:super|article-edit']);
    Route::name('deleteArticle')
        ->get('{article}/delete', 'Admin\ArticleAdminController@destroy')
        ->middleware(['permission:super|article-destroy']);

    Route::name('getWebsiteArticle')
        ->get('/getWebsiteArticle', 'Admin\ArticleAdminController@getWebsiteContent')
        ->middleware(['permission:super']);
});
