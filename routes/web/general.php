<?php

/*
 * Home
 */
Route::get('/', 'HomeController@index')->name('home');


/*
 * Auth
 */
Auth::routes(['verify' => true, 'register' => false]);
Route::post('/email/activate/{id}', 'Auth\VerificationController@password')->name('verification.activate');
Route::get('/password/change', 'PasswordController@index')->name('password.change');

