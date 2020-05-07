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
    'default_role' => App\Domain\Enums\Roles::TAGGER()->getValue(),


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
    'email_verification_time' => 2, // days


    /*
    |--------------------------------------------------------------------------
    | Valid sample mimes
    |--------------------------------------------------------------------------
    |
    | Valid MIMEs for the files that a user can upload. There are two types
    | of valid MIMEs, those of type “file”, which correspond to image files
    | (e.g. PNG, JPG, TIFF…) uploaded directly or “package” files, which are
    | files that can contain other files (e.g. ZIP, TAR…).
    |
    */
    'valid_sample_mimes' => [
        'file' => [
            'image/tiff',
            'image/jpeg',
            'image/png',
        ],
        'package' => [
            'application/x-compressed',
            'application/x-zip-compressed',
            'application/zip',
        ],
    ],

];
