<?php

use App\Utils\Permissions;
use App\Utils\Roles;
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


        // Supervisor permissions
        $supervisor->givePermissionTo(Permissions::SPECIES_MANAGEMENT);

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
