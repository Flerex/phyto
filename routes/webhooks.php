<?php

use Illuminate\Support\Facades\Route;



Route::post('automated-services/bounding-boxes/{assignment}', 'AutomatedServiceController@receiveBoundingBoxes')
    ->name('automated_services.receive_bounding_boxes');

