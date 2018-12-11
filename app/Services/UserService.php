<?php

namespace App\Services;

use App\Role;

/**
 * Interface UserService
 * @package App\Services
 */
interface UserService
{
    /**
     * Creates a new user
     *
     * @param String $name
     * @param String $email
     * @param Role $role
     * @return void
     */
    public function createUser(String $name, String $email, Role $role);

    /**
     * Sends an email to the user so they can set a new password.
     * @param int $user_id
     * @return void
     */
    public function resetPassword(int $user_id);
}