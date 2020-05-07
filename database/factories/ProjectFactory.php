<?php

use App\Domain\Models\Catalog;
use App\Enums\CatalogStatus;
use App\Domain\Models\Project;
use App\Domain\Models\User;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'description' => $faker->sentence,
        'user_id' => function () {
            return factory(User::class)->states('manager')->create()->getKey();
        },
    ];
});

$factory->afterCreating(Project::class, function (Project $project, Faker $faker) {
    $project->users()->sync(factory(User::class, rand(0, 10))->create());


    $catalog = Catalog::inRandomOrder()->where('status', CatalogStatus::SEALED()->getValue())->limit(1)->get();
    $users = User::inRandomOrder()->limit(rand(2, 10))->get();

    $users = $users->filter(fn ($u) => $u->getKey() !== $project->manager->getKey());

    if(count($users) <= 1)
        $users = factory(User::class, rand(1, 10))->create();

    $project->users()->attach($users);
    $project->catalogs()->attach($catalog);
});
