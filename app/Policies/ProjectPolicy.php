<?php

namespace App\Policies;

use App\Project;
use App\Sample;
use App\User;
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


    public function show(User $user, Project $project)
    {
        return $user->getKey() === $project->manager->getKey();
    }

    public function add_user(User $user, Project $project)
    {
        return $user->getKey() === $project->manager->getKey();
    }


}
