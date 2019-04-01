<?php

namespace App\Services;

use App\Utils\Roles;
use Spatie\Permission\Models\Role;

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
     * @param Role $role
     * @return string The ID of the user
     */
    public function createUser(string $name, string $email, Role $role) : int;

    /**
     * Sends an email to the user so they can set a new password.
     * @param int $user_id
     * @return void
     */
    public function resetPassword(int $user_id) : void;
}