<?php

namespace App\Domain\Services\Utils\Dtos;

use Illuminate\Support\Collection;

/**
 * DTO Class that represents the Availability of users.
 */
class Availability
{

    /**
     * Users that may have been previously assigned in compatible tasks
     *
     * @var Collection $recurring
     */
    private Collection $recurring;

    /**
     * New users to the current task in context.
     *
     * @var Collection $recurring
     */
    private Collection $new;

    /**
     * Availability constructor.
     * @param  Collection  $recurring
     * @param  Collection  $new
     */
    public function __construct(Collection $recurring, Collection $new)
    {
        $this->recurring = $recurring;
        $this->new = $new;
    }

    /**
     * Returns whether there's available users in this Availability object.
     */
    public function isEmpty(): bool
    {
        return $this->recurring->isEmpty() && $this->new->isEmpty();
    }

    /**
     * Returns a pool of users by priority. If one pool is empty, another one with less priority is returned instead. If
     * there're no nonempty pools, null is returned.
     *
     * The priority is as follows: $recurring -> $new
     *
     * @return Collection?
     */
    public function getPool(): ?Collection
    {
        if (!$this->recurring->isEmpty()) {
            return $this->recurring;
        }

        if (!$this->new->isEmpty()) {
            return $this->new;
        }

        return null;
    }

    /**
     * Removes the user from the pools.
     *
     * @param  int  $user
     */
    public function makeUserUnavailable(int $user)
    {
        $key = $this->recurring->search($user);

        if($key !== false) {
            $this->recurring->forget($key);
        }

        $key = $this->new->search($user);

        if($key !== false) {
            $this->new->forget($key);
        }

    }


}
