<?php

namespace App\Console\Commands;

use App\Domain\Enums\Roles;
use App\Domain\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $validRoles = Roles::values();
        $role = '';

        while (!in_array($role, $validRoles)) {
            $role = $this->ask('Role (' . implode(', ', $validRoles) . ')');
        }

        $name = $this->ask('Name');
        $email = $this->ask('Email');

        $password = bcrypt($this->secret('Password'));

        $user = User::create(compact('name', 'email', 'password'));

        $user->email_verified_at = Carbon::now();
        $user->save();

        $user->assignRole($role);

        $this->info('User “' . $name . "” successfully created.");

        return 0;
    }
}
