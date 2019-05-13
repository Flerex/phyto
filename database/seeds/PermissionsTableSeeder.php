<?php

use App\Enums\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Permissions::getValues() as $name) {
            Permission::create(compact('name'));
        }
    }
}
