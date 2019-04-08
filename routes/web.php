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


/*
 * Home
 */
Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');


/*
 * Auth
 */
Auth::routes(['verify' => true, 'register' => false]);
Route::post('/email/activate/{id}', 'Auth\VerificationController@password')->name('verification.activate');
Route::get('/password/change', 'PasswordController@index')->name('password.change');


/*
 * Panel
 */
Route::prefix('panel')->middleware('permission:' . Permissions::PANEL_ACCESS)->group(function () {
    Route::get('/', 'Panel\PanelController@index')->name('panel');


    /*
     * User Management
     */
    Route::prefix('users')->middleware('permission:' . Permissions::USER_MANAGEMENT)->group(function () {
        Route::get('/', 'Panel\UserController@index')->name('panel.users.index');
        Route::get('/create', 'Panel\UserController@create')->name('panel.users.create');
        Route::post('/store', 'Panel\UserController@store')->name('panel.users.store');
        Route::post('{id}/password/reset', 'Panel\UserController@reset')->name('panel.users.password_reset');
    });

    /*
     * Species management
     */
    Route::middleware('permission:' . Permissions::SPECIES_MANAGEMENT)->group(function () {
        Route::resource('species', 'Panel\\SpeciesController')
            ->only(['index'])
            ->names([
                'index' => 'panel.species.index',
            ]);
    });

    /*
     * Catalog management
     */
    Route::middleware('permission:' . Permissions::CATALOG_MANAGEMENT)->group(function () {
        Route::resource('catalogs', 'Panel\\CatalogController')
            ->only(['index', 'create', 'store', 'update', 'edit'])
            ->names([
                'index' => 'panel.catalogs.index',
                'create' => 'panel.catalogs.create',
                'store' => 'panel.catalogs.store',
                'edit' => 'panel.catalogs.edit',
                'update' => 'panel.catalogs.update',
            ]);

        Route::post('{catalog}/seal', 'Panel\\CatalogController@seal')
            ->name('panel.catalogs.seal');

        Route::post('{catalog}/mark-as-obsolete', 'Panel\\CatalogController@markAsObsolete')
            ->name('panel.catalogs.mark_as_obsolete');

        Route::post('{catalog}/restore', 'Panel\\CatalogController@restore')
            ->name('panel.catalogs.restore');
    });
});


/*
 * AJAX Calls
 */

Route::prefix('async')->group(function () {
    Route::get('/species', 'AsynchronousController@species') // TODO: No permissions because taggers might use this API call?
        ->name('async.species');


    Route::middleware('permission:' . Permissions::SPECIES_MANAGEMENT . ',' . Permissions::CATALOG_MANAGEMENT)->group(function () {
        Route::post('/hierarchy/add', 'AsynchronousController@add_to_hierarchy')
            ->name('async.add_to_hierarchy');
        Route::post('/hierarchy/edit', 'AsynchronousController@edit_node')
            ->name('async.edit_node');
        Route::get('/catalogs/{catalog}/edit', 'AsynchronousController@edit_catalog')
            ->name('async.edit_catalog');
    });


});
