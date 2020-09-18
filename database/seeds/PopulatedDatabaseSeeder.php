<?php

use Illuminate\Database\Seeder;

class PopulatedDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(TaxonomySeeder::class);
        $this->call(CatalogTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
    }
}
