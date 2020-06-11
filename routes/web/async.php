<?php

use App\Domain\Enums\Permissions;

Route::prefix('async')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/species', 'AsynchronousController@species')
            ->name('async.species');
    });


    Route::middleware('permission:'.Permissions::SPECIES_MANAGEMENT()->getValue().','.Permissions::CATALOG_MANAGEMENT()->getValue())
        ->group(function () {
            Route::post('/hierarchy/add', 'AsynchronousController@add_to_hierarchy')
                ->name('async.add_to_hierarchy');
            Route::post('/hierarchy/edit', 'AsynchronousController@edit_node')
                ->name('async.edit_node');
        });

    Route::middleware('permission:'.Permissions::PANEL_ACCESS()->getValue())
        ->group(function () {
            Route::get('/users/search', 'AsynchronousController@search_users')
                ->name('async.search_users');
        });

    Route::middleware('permission:'.Permissions::PROJECT_MANAGEMENT()->getValue())
        ->group(function () {
            Route::get('/project/{project}/samples/search', 'AsynchronousController@search_samples')
                ->name('async.search_samples');
            Route::get('/project/{project}/members/search', 'AsynchronousController@search_members')
                ->name('async.search_members');
            Route::get('/project/{project}/tasks/search', 'AsynchronousController@search_tasks')
                ->name('async.search_tasks');
        });

});
