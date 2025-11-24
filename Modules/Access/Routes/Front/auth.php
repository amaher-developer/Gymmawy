<?php
/**
 * Created by PhpStorm.
 * User: AMiR
 * Date: 5/3/2017
 * Time: 2:40 PM
 */

Route::get('logout', 'Front\AuthFrontController@logout')->name('logout');

Route::get('register', 'Front\AuthFrontController@showRegistrationForm')->name('register');
Route::post('register', 'Front\AuthFrontController@register');
Route::post('social_register', 'Front\AuthFrontController@socialRegister')->name('socialRegister');

Route::get('login', 'Front\AuthFrontController@showLoginForm')->name('login');
Route::get('social_login', 'Front\AuthFrontController@redirectToProvider')->name('socialLogin');
Route::get('provider_callback', 'Front\AuthFrontController@handleProviderCallback');


Route::get('broker', 'Front\AuthFrontController@showBrokerForm')->name('broker');

Route::get('google_login', 'Front\AuthFrontController@redirectToGoogle')->name('loginByGoogle');
Route::get('google_callback', 'Front\AuthFrontController@handleGoogleCallback');

Route::post('login', 'Front\AuthFrontController@login');
Route::post('broker', 'Front\AuthFrontController@broker');
Route::post('broker/edit', 'Front\AuthFrontController@brokerEdit')->name('brokerEdit');

Route::get('profile/show', 'Front\AuthFrontController@showProfile')->name('showProfile')->middleware('auth');
Route::get('profile/edit', 'Front\AuthFrontController@editProfile')->name('editProfile')->middleware('auth');
Route::post('profile/edit', 'Front\AuthFrontController@updateProfile')->middleware('auth');

Route::post('newsletter-subscribe', 'Front\AuthFrontController@newsletterSubscribe')->name('newsletterSubscribe');

Route::get('verification', 'Front\AuthFrontController@showVerificationPage')->name('showVerificationPage');
Route::get('send-phone-activate-code', 'Front\AuthFrontController@sendPhoneVerificationCode')->name('sendPhoneVerificationCode');
Route::any('activate-phone-user', 'Front\AuthFrontController@verifyPhone')->name('verifyPhone');

//Route::get('update_password/{user}', 'Front\AuthFrontController@showUpdatePasswordForm');
//Route::patch('update_password/{user}', 'Front\AuthFrontController@updatePassword');
//
//Route::get('password/reset', 'Front\AuthFrontController@sendResetPassword')->name('sendResetPassword');
//Route::post('password/reset', 'Front\AuthFrontController@resetPassword');


Route::post('password/email', 'Front\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset', 'Front\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/reset', 'Front\ResetPasswordController@reset');
Route::get('password/reset/{token}', 'Front\ResetPasswordController@showResetForm')->name('password.reset');
Route::name('thanksRegister')->get('/thanks-register', 'Front\AuthFrontController@thanksRegister');
Route::name('emailActivate')->get('/user-activation', 'Front\AuthFrontController@emailActivate');



Route::prefix('user')
    ->middleware(['auth'])
    ->group(function () {

        Route::name('showUserFront')
            ->get('show', 'Front\AuthFrontController@showUser');
        Route::name('editUserFront')
            ->get('edit', 'Front\AuthFrontController@editUser');
        Route::name('editUserFront')
            ->post('edit', 'Front\AuthFrontController@updateUser');

        Route::get('update_password', 'Front\AuthFrontController@editUserUpdatePassword')->name('editUserUpdatePassword');
        Route::post('update_password', 'Front\AuthFrontController@updateUserUpdatePassword')->name('updateUserUpdatePassword');


    });

