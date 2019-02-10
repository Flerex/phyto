<?php

use App\Utils\Permissions;
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
        Permission::create(['name' => Permissions::PANEL_ACCESS]);
        Permission::create(['name' => Permissions::USER_MANAGEMENT]);
        Permission::create(['name' => Permissions::SPECIES_MANAGEMENT]);
    }
}
