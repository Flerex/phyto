<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        // $this->call(UsersTableSeeder::class);


        // FIXME

        $domains = array_map(function ($el) {
            return ['name' => $el];
        }, ['Archaea', 'Bacteria', 'Eukarya']);

        \App\Domain::insert($domains);


        $classis = array_map(function ($el) {
            return [
                'name' => $el,
                'domain_id' => 3,
            ];
        }, ['Mammalia', 'Aves', 'Reptilia']);

        \App\Classis::insert($classis);

        $genus = array_map(function ($el) {
            return [
                'name' => $el,
                'classis_id' => 1,
            ];
        }, ['Canis', 'Vulpes', 'Lycalopex', 'Dusicyon']);


        $genus = array_merge($genus, array_map(function ($el) {
            return [
                'name' => $el,
                'classis_id' => 3,
            ];
        }, ['Ninjemys', 'Meiolania', 'Niolamia', 'Warkalania']));

        \App\Genus::insert($genus);

        $species = array_map(function ($el) {
            return [
                'name' => $el,
                'genus_id' => 1,
            ];
        }, ['Canis Lupus', 'Canis aureus', 'Canis simensis']);


        $species = array_merge($species, array_map(function ($el) {
            return [
                'name' => $el,
                'genus_id' => 2,
            ];
        }, ['V. lagopus', 'V. bengalensis', 'V. chama', 'V. zerda']));

        \App\Species::insert($species);

    }
}
