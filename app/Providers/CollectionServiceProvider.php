<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class CollectionServiceProvider extends ServiceProvider
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
        $this->bootExtendMethod();
    }

    /**
     * Registers a new Collection::extend method that allows to
     * increase the length of a Collection by equally distributing
     * its current elements.
     */
    private function bootExtendMethod()
    {
        Collection::macro('extend', function (int $length) {
            $collection = $this->collect();
            while (($currLength = $collection->count()) < $length) {
                $newItems = $collection->slice(0, $length - $currLength);
                $collection = $collection->concat($newItems);
            }

            return $collection;
        });
    }

}
