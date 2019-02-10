<?php

namespace App\Services;

use App\Utils\Roles;

/**
 * Interface UserService
 * @package App\Services
 */
interface UserService
{
    /**
     * Creates a new user
     *
     * @param string $name
     * @param string $email
     * @param Roles $role
     * @return string The ID of the user
     */
    public function createUser(string $name, string $email, string $role) : int;

    /**
     * Sends an email to the user so they can set a new password.
     * @param int $user_id
     * @return void
     */
    public function resetPassword(int $user_id) : void;
}