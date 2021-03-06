<?php

use App\Domain\Models\Classis;
use App\Domain\Models\Domain;
use App\Domain\Models\Genus;
use App\Domain\Models\Species;
use Faker\Generator as Faker;

$factory->define(Domain::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(Classis::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'domain_id' => factory(Domain::class)->create()->id
    ];
});

$factory->define(Genus::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'classis_id' => factory(Classis::class)->create()->id
    ];
});

$factory->define(Species::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'genus_id' => factory(Genus::class)->create()->id
    ];
});
