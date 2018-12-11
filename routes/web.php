<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Utils\Permissions;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true, 'register' => false]);
Route::post('/email/activate/{id}', 'Auth\VerificationController@password')->name('verification.activate');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/password/change', 'PasswordController@index')->name('password.change');

Route::prefix('panel')->middleware('permission:' . Permissions::PANEL_ACCESS)->group(function () {
    Route::get('/', 'PanelController@index')->name('panel');

    Route::prefix('users')->middleware('permission:' . Permissions::USER_MANAGEMENT)->group(function () {
        Route::get('/', 'PanelUserController@index')->name('panel.users');
        Route::get('/create', 'PanelUserController@create')->name('panel.users.create');
        Route::post('/store', 'PanelUserController@store')->name('panel.users.store');
        Route::post('{id}/password/reset', 'PanelUserController@reset')->name('panel.users.password_reset');
    });
});