<?php


Route::post('articles', 'Api\ArticleApiController@articles')->middleware('auth:api');
Route::post('article', 'Api\ArticleApiController@article')->middleware('auth:api');
Route::post('article-categories', 'Api\ArticleApiController@categories')->middleware('auth:api');

//Route::prefix('api/article')
//    ->middleware(['api'])
//    ->group(function () {
//});
