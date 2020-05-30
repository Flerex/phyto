<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Models\Image;
use App\Domain\Models\Sample;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    return [
        'sample_id' => factory(Sample::class),
        'original_path' => $faker->imageUrl(),
    ];
});
