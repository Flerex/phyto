<?php

namespace App\Domain\Services;

use App\Domain\Models\Sample;
use App\Domain\Models\Task;
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
     * @param  int  $repetitions
     * @param  int  $processes
     * @return Task
     */
    public function create_task(Sample $sample, Collection $members, int $repetitions = 1, int $processes = 1): Task;

}
