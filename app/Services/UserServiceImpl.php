<?php

namespace App\Services;


use App\Enums\Roles;
use App\User;
use http\Exception\InvalidArgumentException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;

class UserServiceImpl implements UserService
{

    /**
     * Creates a new user
     *
     * @param string $name
     * @param string $email
     * @param Role $role
     * @return int
     */
    public function createUser(string $name, string $email, Role $role): int
    {
        if (!Roles::hasValue($role->name)) {
            throw new \InvalidArgumentException();
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
        ]);

        $user->assignRole($role);

        event(new Registered($user));
        
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
