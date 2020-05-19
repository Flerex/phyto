<?php

use App\Domain\Models\Classis;
use App\Domain\Models\Domain;
use App\Domain\Models\Genus;
use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Models\Species;
use Carbon\Carbon;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Sample::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(4),
        'description' => $faker->paragraph,
        'taken_on' => Carbon::now(),
        'project_id' => factory(Project::class),
    ];
});

$factory->afterCreatingState(Sample::class, 'withImages', function (Sample $sample, Faker $faker) {
    $images = factory(Image::class, rand(1, 15))->create();
    $sample->images()->sync($images);
});
