<?php

return [
    /*
   |--------------------------------------------------------------------------
   | Enabled services
   |--------------------------------------------------------------------------
   |
   | Define here a list of services that are enabled.
   |
   */

    'enabled' => [
        'dummy',
    ],


    /*
    |--------------------------------------------------------------------------
    | Automated identification services
    |--------------------------------------------------------------------------
    |
    | Here you can set up a list of services that will be used to send
    | images for identification of species.
    |
    */

    'services' => [

        'dummy' => [
            'name' => 'Dummy classifier',
            'endpoint' => 'http://localhost:8080/api/identify-species/',
        ],

    ],
];
