<?php

use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}', 'ProjectController@show')->name('projects.show');
Route::get('/projects/{project}/assignments', 'ProjectController@assignments')->name('projects.assignments');
Route::get('/projects/{project}/members', 'ProjectController@members')->name('projects.members');
Route::get('/projects/{project}/images/{image}', 'ProjectController@tag')->name('projects.images.tag');

/**
 * Asynchronous calls.
 */
Route::post('/projects/images/{image}/bounding-boxes', 'BoundingBoxController@store')
    ->name('async.bounding_boxes.store');
Route::patch('/projects/images/bounding-boxes/{boundingBox}/update', 'BoundingBoxController@update')
    ->name('async.bounding_boxes.update');
Route::delete('/projects/images/bounding-boxes/{boundingBox}/delete', 'BoundingBoxController@destroy')
    ->name('async.bounding_boxes.destroy');
