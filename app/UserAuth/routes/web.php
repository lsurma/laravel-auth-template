<?php

/*
|--------------------------------------------------------------------------
| User Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes
Route::get('login', 'LoginController@showLoginForm')->name('user-auth.login');
Route::post('login', 'LoginController@login');
Route::post('logout', 'LoginController@logout')->name('user-auth.logout');

// Registration Routes
Route::get('register', 'RegisterController@showRegistrationForm')->name('user-auth.register');
Route::post('register', 'RegisterController@register');

// Password Reset Routes
Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('user-auth.password.request');
Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('user-auth.password.email');
Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('user-auth.password.reset');
Route::post('password/reset', 'ResetPasswordController@reset')->name('user-auth.password.update');

// Password Confirmation Routes
Route::get('password/confirm', 'ConfirmPasswordController@showConfirmForm')->name('user-auth.password.confirm');
Route::post('password/confirm', 'ConfirmPasswordController@confirm');

// Email Verification Routes
Route::get('email/verify', 'EmailVerificationController@show')->name('user-auth.verification.notice');
Route::get('email/verify/{id}/{hash}', 'EmailVerificationController@verify')->name('user-auth.verification.verify');
Route::post('email/resend', 'EmailVerificationController@resend')->name('user-auth.verification.resend');
