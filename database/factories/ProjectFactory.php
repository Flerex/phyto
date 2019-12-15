<?php

use App\Project;
use App\User;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'description' => $faker->sentence,
        'user_id' => function () {
            return factory(User::class)->create()->getKey();
        },
    ];
});

$factory->afterCreating(Project::class, function (Project $project, Faker $faker) {
    $project->users()->sync(factory(User::class, rand(0, 10))->create());
});
