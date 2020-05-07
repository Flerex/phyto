<?php

use App\Domain\Models\Catalog;
use App\Domain\Models\Domain;
use App\Enums\CatalogStatus;
use Illuminate\Database\Seeder;

class CatalogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Catalog::class, 5)->create();

        foreach(Domain::all() as $domain) {
            $catalog = factory(Catalog::class)->create([
                'name' => $domain->name . ' catalog',
                'status' => CatalogStatus::SEALED,
            ]);
            $catalog->domains()->attach($domain);
        }


    }
}
