<?php

namespace App\Domain\Services;

use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Models\Task;
use App\Domain\Models\TaskAssignment;
use App\Domain\Models\TaskProcess;
use App\Exceptions\NotEnoughMembersForProcessException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
    public function create_task(
        string $description,
        Sample $sample,
        Collection $members,
        Collection $compatibility,
        int $processCount = 1
    ): Task;

    /**
     * Create an automated task.
     *
     * @param  string  $description
     * @param  Sample  $sample
     * @param  Collection  $services
     * @return Task
     */
    public function create_automated_task(string $description, Sample $sample, Collection $services): Task;

    /**
     * Retrieves all tasks for a given project.
     *
     * @param  Project  $project
     * @return LengthAwarePaginator
     */
    public function get_from_project(Project $project): LengthAwarePaginator;

    /**
     * Retrieves all processes for a task.
     *
     * @param  Task  $task
     * @return mixed
     */
    public function get_processes(Task $task): Collection;

    /**
     * Returns the assignments for a given process with its completion status.
     *
     * @param  TaskProcess  $process
     * @return Collection
     */
    public function get_assignments_for_process_with_percentage(TaskProcess $process): Collection;

    /**
     * Returns the assignments for a given process.
     *
     * @param  TaskProcess  $process
     * @return Collection
     */
    public function get_assignments_for_process(TaskProcess $process): Collection;

    /**
     * Retrieves bounding boxes for a given assignment.
     *
     * @param  TaskAssignment  $assignment
     * @return Collection
     */
    public function get_boxes_for_assignment(TaskAssignment $assignment): Collection;


    /**
     * Get the image of a given assignment.
     *
     * @param  TaskAssignment  $assignment
     * @return Image
     */
    public function get_assignment_image(TaskAssignment $assignment): Image;


}
