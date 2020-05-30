<?php

use App\Domain\Enums\Permissions;
use Illuminate\Support\Facades\Route;

Route::prefix('panel')->middleware('permission:'.Permissions::PANEL_ACCESS()->getValue())->group(function () {
    Route::get('/', 'Panel\PanelController@index')->name('panel');


    /*
    * User Management
    */
    Route::prefix('users')->middleware('permission:'.Permissions::USER_MANAGEMENT()->getValue())->group(function () {
        Route::get('/', 'Panel\UserController@index')->name('panel.users.index');
        Route::get('/create', 'Panel\UserController@create')->name('panel.users.create');
        Route::post('/store', 'Panel\UserController@store')->name('panel.users.store');
        Route::post('{id}/password/reset', 'Panel\UserController@reset')->name('panel.users.password_reset');
    });

    /*
    * Species management
    */
    Route::middleware('permission:'.Permissions::SPECIES_MANAGEMENT()->getValue())->group(function () {
        Route::resource('species', 'Panel\\SpeciesController')
            ->only(['index'])
            ->names([
                'index' => 'panel.species.index',
            ]);
    });

    /*
    * Catalog management
    */
    Route::middleware('permission:'.Permissions::CATALOG_MANAGEMENT()->getValue())->group(function () {
        Route::resource('catalogs', 'Panel\\CatalogController')
            ->names([
                'index' => 'panel.catalogs.index',
                'create' => 'panel.catalogs.create',
                'store' => 'panel.catalogs.store',
                'edit' => 'panel.catalogs.edit',
                'update' => 'panel.catalogs.update',
                'destroy' => 'panel.catalogs.destroy'
            ]);

        Route::get('{catalog}/create-from', 'Panel\\CatalogController@create_from')
            ->name('panel.catalogs.create_from');

        Route::post('{catalog}/seal', 'Panel\\CatalogController@seal')
            ->name('panel.catalogs.seal');

        Route::post('{catalog}/mark-as-obsolete', 'Panel\\CatalogController@markAsObsolete')
            ->name('panel.catalogs.mark_as_obsolete');

        Route::post('{catalog}/restore', 'Panel\\CatalogController@restore')
            ->name('panel.catalogs.restore');
    });

    /*
    * Project management
    */
    Route::middleware('permission:'.Permissions::PROJECT_MANAGEMENT()->getValue())->group(function () {
        Route::resource('projects', 'Panel\ProjectController')
            ->only(['index', 'create', 'store', 'show'])
            ->names([
                'index' => 'panel.projects.index',
                'create' => 'panel.projects.create',
                'store' => 'panel.projects.store',
                'show' => 'panel.projects.show',
            ]);


        /*
         * Members
         */
        Route::get('projects/{project}/users/add', 'Panel\\MemberController@create')
            ->name('panel.projects.members.create');

        Route::post('projects/{project}/users/add', 'Panel\\MemberController@store')
            ->name('panel.projects.members.store');

        /*
         * Samples
         */
        Route::get('projects/{project}/samples/', 'Panel\\SampleController@index')
            ->name('panel.projects.samples.index');

        Route::get('projects/{project}/samples/create', 'Panel\\SampleController@create')
            ->name('panel.projects.samples.create');

        Route::post('projects/{project}/samples/create', 'Panel\\SampleController@store')
            ->name('panel.projects.samples.store');

        Route::get('projects/{project}/samples/upload', 'Panel\\SampleController@checkChunk');

        Route::post('projects/{project}/samples/upload', 'Panel\\SampleController@upload')
            ->name('panel.projects.samples.upload');


        /*
         * Images
         */
        Route::get('projects/{project}/samples/{sample}/images', 'Panel\\ImageController@index')
            ->name('panel.projects.images.index');

        Route::get('projects/{project}/members', 'Panel\\MemberController@index')
            ->name('panel.projects.members.index');

        Route::post('projects/{project}/members/{member}/change-status', 'Panel\\MemberController@change_status')
            ->name('panel.projects.members.change_status');


        /*
         * Tasks
         */
        Route::get('projects/{project}/tasks', 'Panel\\TaskController@index')
            ->name('panel.projects.tasks.index');

        Route::get('projects/{project}/tasks/create', 'Panel\\TaskController@create')
            ->name('panel.projects.tasks.create');

        Route::post('projects/{project}/tasks/create', 'Panel\\TaskController@store')
            ->name('panel.projects.tasks.store');

        Route::get('projects/{project}/tasks/{task}', 'Panel\\TaskController@show')
            ->name('panel.projects.tasks.show');

        Route::get('projects/{project}/tasks/{task}/processes/{process}', 'Panel\\TaskController@show_process')
            ->name('panel.projects.tasks.show_process');
    });
});
