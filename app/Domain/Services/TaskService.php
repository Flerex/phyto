<?php

namespace App\Domain\Services;

use App\Domain\Models\Sample;
use App\Domain\Models\Task;
use App\Exceptions\NotEnoughMembersForProcessException;
use Illuminate\Support\Collection;

/**
 * Interface TaskService
 *
 * @package App\Services
 */
interface TaskService
{
    /**
     * Create a task.
     *
     * @param  Sample  $sample
     * @param  Collection  $members
     * @param  int  $processCount
     * @return Task
     * @throws NotEnoughMembersForProcessException
     */
    public function create_task(Sample $sample, Collection $members,int $processCount = 1): Task;

}
