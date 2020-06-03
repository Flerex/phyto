<?php

namespace App\Policies;

use App\Domain\Models\TaskAssignment;
use App\Domain\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskAssignmentPolicy
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

    /**
     * Any use of a task assignment.
     * @param  User  $user
     * @param  TaskAssignment  $taskAssignment
     * @return bool
     */
    public function work(User $user, TaskAssignment $taskAssignment)
    {
        return $taskAssignment->user->getKey() === $user->getKey() && !$taskAssignment->finished;
    }
}
