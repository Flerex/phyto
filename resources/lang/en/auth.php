<?php

use App\Enums\Roles;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'logout' => 'Logout',
    'login' => 'Login',

    'name' => 'Name',
    'email' => 'Email',
    'password' => 'Password',
    'password_confirmation' => 'Password confirmation',
    'remember_me' => 'Remember me',

    // Forgot password
    'forgot_password' => 'Forgot your password?',
    'send_password_reset' => 'Send password reset email',

    // Reset password
    'reset_password' => 'Reset password',

    // Roles
    'role' => 'Role',
    'roles' => [
        Roles::ADMIN()->getValue() => 'Administrator',
        Roles::SUPERVISOR()->getValue() => 'Supervisor',
        Roles::MANAGER()->getValue() => 'Manager',
        Roles::TAGGER()->getValue() => 'Boxer',
    ],

    'flash' => [
        'account_activated' => 'The account has been successfully activated',
    ]

];
