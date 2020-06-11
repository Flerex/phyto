<?php


namespace App\Domain\Services\Utils;


use App\Exceptions\NotEnoughMembersForProcessException;
use Illuminate\Support\Collection;

interface AssignmentManager
{
    /**
     * Computes the assignments for $processes number of processes, according to the values provided to the
     * implementation in their constructor.
     *
     * @param  int  $processes
     * @return Collection
     * @throws NotEnoughMembersForProcessException
     */
    public function computeAssignments(int $processes): Collection;
}
