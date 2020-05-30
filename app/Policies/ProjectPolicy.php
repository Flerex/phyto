<?php

namespace App\Policies;

use App\Domain\Enums\Permissions;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
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


    public function view(User $user, Project $project)
    {
        return $user->getKey() === $project->manager->getKey();
    }

    public function add_user(User $user, Project $project)
    {
        return $user->getKey() === $project->manager->getKey();
    }

    public function before(User $user, $ability)
    {
        if ($user->hasPermissionTo(Permissions::MANAGE_ALL_PROJECTS()->getValue())) {
            return true;
        }
    }

    public function access(User $user, Project $project)
    {

        $users = $project->users()->pluck('id');

        return $users->contains($user->getKey());
    }


}
