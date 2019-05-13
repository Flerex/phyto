<?php

use App\Enums\Permissions;
use App\Enums\Roles;
use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => Roles::ADMIN]);
        $supervisor = Role::create(['name' => Roles::SUPERVISOR]);
        $manager = Role::create(['name' => Roles::MANAGER]);
        $tagger = Role::create(['name' => Roles::TAGGER]);

        // Tagger permissions


        // Manager permissions
        $manager->givePermissionTo(Permissions::PANEL_ACCESS);
        $manager->givePermissionTo(Permissions::PROJECT_MANAGEMENT);

        $manager->givePermissionTo($tagger->getAllPermissions());


        // Supervisor permissions
        $supervisor->givePermissionTo(Permissions::PANEL_ACCESS);
        $supervisor->givePermissionTo(Permissions::SPECIES_MANAGEMENT);
        $supervisor->givePermissionTo(Permissions::CATALOG_MANAGEMENT);
        $supervisor->givePermissionTo(Permissions::MANAGE_ALL_PROJECTS);

        $supervisor->givePermissionTo($manager->getAllPermissions());
        $supervisor->givePermissionTo($tagger->getAllPermissions());


        // Permission definition
        $admin->givePermissionTo(Permissions::PANEL_ACCESS);
        $admin->givePermissionTo(Permissions::USER_MANAGEMENT);

        $admin->givePermissionTo($supervisor->getAllPermissions());
        $admin->givePermissionTo($manager->getAllPermissions());
        $admin->givePermissionTo($tagger->getAllPermissions());



    }
}
