<?php

use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}', 'Project\\ProjectController@show')
    ->name('projects.show');
Route::get('/projects/{project}/assignments', 'Project\\AssignmentController@index')
    ->name('projects.assignments.index');
Route::get('/projects/{project}/assignments/{assignment}', 'Project\\AssignmentController@show')
    ->name('projects.assignments.show');
Route::get('/projects/{project}/members', 'Project\\ProjectController@members')
    ->name('projects.members');
Route::get('/projects/{project}/images/{image}', 'Project\\ProjectController@view_image')
    ->name('projects.images.show');

/**
 * Bounding Boxes
 */
Route::post('/projects/assignments/{assignment}/bounding-boxes', 'Project\\BoundingBoxController@store')
    ->name('projects.bounding_boxes.store');
Route::patch('/projects/assignments/bounding-boxes/{boundingBox}/update', 'Project\\BoundingBoxController@update')
    ->name('projects.bounding_boxes.update');
Route::delete('/projects/assignments/bounding-boxes/{boundingBox}/delete', 'Project\\BoundingBoxController@destroy')
    ->name('projects.bounding_boxes.destroy');
Route::post('/projects/assignments/bounding-boxes/{boundingBox}/tag', 'Project\\BoundingBoxController@tag')
    ->name('projects.bounding_boxes.tag');
