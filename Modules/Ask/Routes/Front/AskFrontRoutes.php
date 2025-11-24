<?php

Route::prefix('')
    ->group(function () {
        Route::name('asks')
            ->get('asks/', 'Front\AskFrontController@asks');
        Route::name('asks.tags')
            ->get('asks/tags', 'Front\AskFrontController@askTags');
        Route::name('asks.tag')
            ->get('asks/tag/{tag}', 'Front\AskFrontController@askTags');
        Route::name('askCategory')
            ->get('asks/{category_id}/{slug}', 'Front\AskFrontController@asks');
        Route::name('ask')
            ->get('ask/{id}/{slug}', 'Front\AskFrontController@ask');


        Route::name('createQuestionAsk')
            ->get('ask/create-question', 'Front\AskFrontController@createQuestion');
        Route::name('storeQuestionAsk')
            ->post('ask/create-question', 'Front\AskFrontController@storeQuestion');

        Route::name('createAnswerAsk')
            ->get('ask/{question}/create-answer', 'Front\AskFrontController@createAnswer');
        Route::name('storeAnswerAsk')
            ->post('ask/{question}/create-answer', 'Front\AskFrontController@storeAnswer');
        Route::name('storeReplyAsk')
            ->post('ask/create-reply', 'Front\AskFrontController@storeReply');

        Route::name('editQuestionAsk')
            ->get('{token}/edit', 'Front\AskFrontController@editQuestion');
        Route::name('editQuestionAsk')
            ->post('{token}/edit', 'Front\AskFrontController@updateQuestion');
        Route::name('hideQuestionAsk')
            ->get('{token}/hide', 'Front\AskFrontController@hideQuestion');

        Route::name('getRelatedQuestionsAjax')
            ->post('get-related-questions-ajax', 'Front\AskFrontController@getRelatedQuestionsAjax');

    });
