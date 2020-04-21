<?php

namespace App\Providers;

use App\Classis;
use App\Domain;
use App\Genus;
use App\Species;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class EloquentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'domain' => Domain::class,
            'genus' => Genus::class,
            'classis' => Classis::class,
            'species' => Species::class,
        ]);
    }
}
