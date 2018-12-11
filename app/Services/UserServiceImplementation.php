<?php

namespace App\Services;


use App\Mail\ActivateAccount;
use App\Role;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;

class UserServiceImplementation implements UserService
{

    /**
     * Creates a new user
     *
     * @param String $name
     * @param String $email
     * @param Role $role
     * @return mixed
     */
    public function createUser(String $name, String $email, Role $role)
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
        ]);

        event(new Registered($user));

        $user->assignRole($role);

    }

    /**
     * Sends an email to the user so they can set a new password.
     * @param int $user_id
     * @return void
     */
    public function resetPassword(int $user_id)
    {
        User::findOrFail($user_id); // Throw ModelNotFoundException if user does not exist

        // TODO: Add to queue. (Extending Laravel's default password broker)
        Password::broker()->sendResetLink(['id' => $user_id]);
    }
}