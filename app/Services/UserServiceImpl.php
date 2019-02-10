<?php

namespace App\Services;


use App\Mail\ActivateAccount;
use App\Utils\Role;
use App\User;
use App\Utils\Roles;
use http\Exception\InvalidArgumentException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;

class UserServiceImpl implements UserService
{

    /**
     * Creates a new user
     *
     * @param string $name
     * @param string $email
     * @param string $role
     * @return int
     */
    public function createUser(string $name, string $email, string $role): int
    {
        if (!Roles::isValid($role)) {
            throw new \InvalidArgumentException();
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
        ]);

        event(new Registered($user));

        $user->assignRole($role);

        return $user->getKey();

    }

    /**
     * Sends an email to the user so they can set a new password.
     * @param int $user_id
     * @return void
     */
    public function resetPassword(int $user_id): void
    {
        User::findOrFail($user_id); // Throw ModelNotFoundException if user does not exist

        Password::broker()->sendResetLink(['id' => $user_id]);
    }
}