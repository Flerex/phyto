<?php

Route::get('/projects/{project}', 'ProjectController@show')->name('projects.show');
Route::get('/projects/{project}/images/{image}', 'ProjectController@tag')->name('projects.images.tag');
Route::post('/projects/{project}/images/{image}/bounding-boxes', 'ProjectController@create_bounding_box')
    ->name('projects.images.bounding_boxes.create');
