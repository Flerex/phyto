<?php

namespace App\Console\Commands;

use App\Domain\Models\User;
use App\Enums\Roles;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DevelopmentSeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates dummy data useful in development.';

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
        $user = User::create([
            'name' => 'Phyto',
            'email' => 'phyto@phyto.test',
            'password' => bcrypt('admin'),
        ]);

        $user->email_verified_at = Carbon::now();
        $user->save();

        $user->assignRole(Roles::ADMIN()->getValue());

        $this->info('Development seeding completed successfully.');
    }
}
