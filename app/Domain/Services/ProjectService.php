<?php


namespace App\Domain\Services;

use App\Domain\Models\Catalog;
use App\Exceptions\CatalogStatusException;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Interface ProjectService
 * @package App\Services
 */
interface ProjectService
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
    public function createProject(string $name, string $description, int $manager_id, Collection $catalogs, Collection $users) : Project;


    /**
     * Creates a new Sample entity and adds it to the given Project.
     *
     * @param string $name
     * @param string $description
     * @param Carbon $takenOn
     * @param Collection $files
     * @param Project $project
     * @return Sample
     */
    public function addSampleToProject(string $name, string $description, Carbon $takenOn, Collection $files, Project $project) : Sample;

}
