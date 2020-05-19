<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Models\Catalog;
use App\Domain\Enums\CatalogStatus;
use Faker\Generator as Faker;

$factory->define(Catalog::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3, true),
        'status' => collect(CatalogStatus::toArray())->random(1)->first()
    ];
});

