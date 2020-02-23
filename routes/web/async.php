<?php

use App\Enums\Permissions;

Route::prefix('async')->group(function () {
    Route::get('/species',
        'AsynchronousController@species')// TODO: No permissions because taggers might use this API call?
    ->name('async.species');


    Route::middleware('permission:' . Permissions::SPECIES_MANAGEMENT . ',' . Permissions::CATALOG_MANAGEMENT)
        ->group(function () {
            Route::post('/hierarchy/add', 'AsynchronousController@add_to_hierarchy')
                ->name('async.add_to_hierarchy');
            Route::post('/hierarchy/edit', 'AsynchronousController@edit_node')
                ->name('async.edit_node');
            Route::get('/catalogs/{catalog}/edit', 'AsynchronousController@edit_catalog')
                ->name('async.edit_catalog');
        });

    Route::middleware('permission:' . Permissions::PANEL_ACCESS)
        ->group(function () {
            Route::get('/users/search', 'AsynchronousController@search_users')
                ->name('async.search_users');
        });

});
