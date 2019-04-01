<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default role
    |--------------------------------------------------------------------------
    |
    | The default role used when creating a new user.
    |
    */
    'default_role' => App\Utils\Roles::TAGGER,


    /*
    |--------------------------------------------------------------------------
    | Pagination Size
    |--------------------------------------------------------------------------
    |
    | The amount of elements that will be shown in paginated results.
    |
    */
    'pagination_size' => 50,

    /*
    |--------------------------------------------------------------------------
    | Email Verification Time
    |--------------------------------------------------------------------------
    |
    | Time in days the verification link sent to a user's email can be
    | used before expiring. After this the user will have to use the form
    | to resend a new email.
    |
    */
    'email_verification_time' => 1, // days

];
