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
     * @param  string  $description
     * @param  Sample  $sample
     * @param  Collection  $members
     * @param  Collection  $compatibility
     * @param  int  $processCount
     * @return Task
     */
    public function create_task(string $description, Sample $sample, Collection $members, Collection $compatibility, int $processCount = 1): Task;

}
