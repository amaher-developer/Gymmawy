<?php

Route::prefix('operate/articlecategory')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listArticleCategory')
        ->get('/', 'Admin\ArticleCategoryAdminController@index')
        ->middleware(['permission:super|article-category-index']);
    Route::name('createArticleCategory')
        ->get('create', 'Admin\ArticleCategoryAdminController@create')
        ->middleware(['permission:super|article-category-create']);
    Route::name('storeArticleCategory')
        ->post('create', 'Admin\ArticleCategoryAdminController@store')
        ->middleware(['permission:super|article-category-create']);
    Route::name('editArticleCategory')
        ->get('{articlecategory}/edit', 'Admin\ArticleCategoryAdminController@edit')
        ->middleware(['permission:super|article-category-edit']);
    Route::name('editArticleCategory')
        ->post('{articlecategory}/edit', 'Admin\ArticleCategoryAdminController@update')
        ->middleware(['permission:super|article-category-edit']);
    Route::name('deleteArticleCategory')
        ->get('{articlecategory}/delete', 'Admin\ArticleCategoryAdminController@destroy')
        ->middleware(['permission:super|article-category-destroy']);
});
