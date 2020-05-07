<?php

namespace App\Policies;

use App\Domain\Models\BoundingBox;
use App\Domain\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BoundingBoxPolicy
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


    public function update(User $user, BoundingBox $boundingBox)
    {
        $users = $boundingBox->image->sample->project->users->pluck('id');
        return $users->contains($user->getKey());
    }

    public function destroy(User $user, BoundingBox $boundingBox)
    {
        $users = $boundingBox->image->sample->project->users->pluck('id');
        return $users->contains($user->getKey());
    }
}
