<?php


namespace App\Domain\Services;

use App\Domain\Models\Catalog;
use App\Domain\Models\User;
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


    /**
     * Retrieves all members of a project.
     *
     * @param  Project  $project
     * @return mixed
     */
    public function get_members(Project $project): Collection;

    /**
     * Sets the status of a user in a project to $status.
     * @param  Project  $project
     * @param  User  $member
     * @param  bool  $status
     * @return mixed
     */
    public function set_member_status(Project $project, User $member, bool $status);

    /**
     * Adds the provided $users to the $project so that they become members.
     *
     * @param  Project  $project
     * @param  Collection  $users
     * @return mixed
     */
    public function add_members(Project $project, Collection $users);

}
