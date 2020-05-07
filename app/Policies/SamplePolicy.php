<?php

namespace App\Policies;

use App\Enums\Permissions;
use App\Domain\Models\Project;
use App\Domain\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SamplePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user, Project $project)
    {
        return $user->getKey() === $project->manager->getKey();
    }

    public function viewAny(User $user, Project $project)
    {
        return $user->getKey() === $project->manager->getKey();
    }

    public function before(User $user, $ability)
    {
        if ($user->hasPermissionTo(Permissions::MANAGE_ALL_PROJECTS()->getValue())) {
            return true;
        }
    }
}
