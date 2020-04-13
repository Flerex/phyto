<?php

Route::get('/projects/{project}', 'ProjectController@show')->name('projects.show');
Route::get('/projects/{project}/images/{image}', 'ProjectController@tag')->name('projects.images.tag');

/**
 * Asynchronous calls.
 */
Route::post('/projects/images/{image}/bounding-boxes', 'BoundingBoxController@store')
    ->name('async.bounding_boxes.store');
Route::patch('/projects/images/bounding-boxes/{boundingBox}/update', 'BoundingBoxController@update')
    ->name('async.bounding_boxes.update');
