<?php

namespace App\Policies;

use App\Enums\Permissions;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
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

    public function before(User $user, $ability)
    {
        if ($user->can(Permissions::MANAGE_ALL_PROJECTS)) {
            return true;
        }
    }

    /**
     * @param User $user
     * @param Project $project
     * @param Sample $sample
     * @return bool
     */
    public function viewAny(User $user, Project $project, Sample $sample)
    {
        return $user->getKey() === $project->manager->getKey();
    }

}
