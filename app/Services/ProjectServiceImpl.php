<?php


namespace App\Services;


use App\Catalog;
use App\Exceptions\CatalogStatusException;
use App\Enums\CatalogStatus;
use App\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProjectServiceImpl implements ProjectService
{

    /**
     * Create a project.
     *
     * @param string $name
     * @param string $description
     * @param int $manager_id
     * @param Collection $catalogs
     * @param Collection $users
     * @return Project
     */
    public function createProject(string $name, string $description, int $manager_id, Collection $catalogs, Collection $users): Project
    {
        $project = Project::create([
            'name' => $name,
            'description' => $description,
            'user_id' => $manager_id,
        ]);

        $project->users()->attach($users);
        $project->catalogs()->attach($catalogs);
        $project->save();

        return $project;
    }
}