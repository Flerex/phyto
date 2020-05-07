<?php

use App\Domain\Models\Role;
use App\Enums\Permissions;
use App\Enums\Roles;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => Roles::ADMIN()->getValue()]);
        $supervisor = Role::create(['name' => Roles::SUPERVISOR()->getValue()]);
        $manager = Role::create(['name' => Roles::MANAGER()->getValue()]);
        $tagger = Role::create(['name' => Roles::TAGGER()->getValue()]);

        // Boxer permissions

        // Manager permissions
        $manager->givePermissionTo(Permissions::PROJECT_MANAGEMENT()->getValue());

        $manager->givePermissionTo($tagger->getAllPermissions());


        // Supervisor permissions
        $supervisor->givePermissionTo(Permissions::PANEL_ACCESS()->getValue());
        $supervisor->givePermissionTo(Permissions::SPECIES_MANAGEMENT()->getValue());
        $supervisor->givePermissionTo(Permissions::CATALOG_MANAGEMENT()->getValue());
        $supervisor->givePermissionTo(Permissions::MANAGE_ALL_PROJECTS()->getValue());

        $supervisor->givePermissionTo($manager->getAllPermissions());
        $supervisor->givePermissionTo($tagger->getAllPermissions());


        // Permission definition
        $admin->givePermissionTo(Permissions::PANEL_ACCESS()->getValue());
        $admin->givePermissionTo(Permissions::USER_MANAGEMENT()->getValue());

        $admin->givePermissionTo($supervisor->getAllPermissions());
        $admin->givePermissionTo($manager->getAllPermissions());
        $admin->givePermissionTo($tagger->getAllPermissions());



    }
}
