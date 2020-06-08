<?php

namespace App\Domain\Services;

use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Models\Task;
use App\Domain\Models\TaskAssignment;
use App\Domain\Models\TaskProcess;
use App\Domain\Models\User;
use App\Exceptions\NotEnoughMembersForProcessException;
use App\Mail\NewAssignmentsMail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Interface TaskService
 *
 * @package App\Services
 */
class TaskServiceImpl implements TaskService
{
    /**
     * Create a task.
     *
     * @param  Sample  $sample
     * @param  Collection  $members
     * @param  int  $processCount
     * @return Task
     * @throws Throwable
     */
    public function create_task(Sample $sample, Collection $members, int $processCount = 1): Task
    {
        return DB::transaction(function () use ($processCount, $members, $sample) {

            $project = $sample->project;

            $task = Task::create(['project_id' => $project->getKey(), 'sample_id' => $sample->getKey()]);

            if ($members->count() < $processCount) {
                throw new NotEnoughMembersForProcessException;
            }

            $processes = $this->createProcesses($task->getKey(), $processCount);

             $this->createAssignments($processes, $members, $sample->images, $project, $members->count());

            return $task;
        });
    }

    /**
     * Compute the assignments for the given parameters. For every process, the assignments are retrieved as follow:
     *
     * 1. We map the image list and assign to every image a member that is in the same position in both lists
     * ($members and $sample->images lists). Of course, as there can be less users than images, we compute the
     * position modulo the member count so if we run out of users we start again from the top, making sure this
     * way that the members are assigned evenly.
     *
     * 2. As for every process we have to make sure that the same member is not assigned more than one time to
     * the same image, we shift the members array for every process.
     *
     * @param  Collection  $processes
     * @param  Collection  $members
     * @param  Collection  $images
     * @param  int  $projectId
     * @param  int  $membersCount
     * @return Collection
     */
    private function createAssignments(
        Collection $processes,
        Collection $members,
        Collection $images,
        Project $project,
        int $membersCount
    ): Collection {
        $assignments =  $processes->map(
            fn(TaskProcess $process, int $processIndex) => $images->map(
                fn(Image $image, int $imageIndex) => (object) [
                    'process' => $process->getKey(),
                    'image' => $image->getKey(),
                    'user' => $members[($imageIndex + $processIndex) % $membersCount]->getKey(),
                ]
            )
        )->flatten(2);

        foreach ($assignments as $assignment) {
            TaskAssignment::create([
                'task_process_id' => $assignment->process,
                'project_id' => $project->getKey(),
                'user_id' => $assignment->user,
                'image_id' => $assignment->image,
            ]);
        }

        $this->notifyUsers($assignments, $members, $project);

        return $assignments;
    }

    /**
     * Instantiates a given number of processes in the provided task and returns them in a collection.
     *
     * @param  int  $taskId
     * @param  int  $count
     * @return Collection
     */
    private function createProcesses(int $taskId, int $count = 1): Collection
    {
        return collect(range(0, $count - 1))->map(fn($i) => TaskProcess::create(['task_id' => $taskId]));
    }

    /**
     * Sends an email to the users to notify them of the new assignments.
     *
     * @param  Collection  $assignments
     * @param  Collection  $members
     * @param  Project  $project
     */
    private function notifyUsers(Collection $assignments, Collection $members, Project $project): void
    {
        foreach ($assignments->groupBy('user') as $userId => $assignments) {
            $user = $members->first(fn(User $user) => $user->getKey() === $userId);
            $link = route('projects.assignments.index', compact('project'));
            Mail::to($user)->queue(new NewAssignmentsMail($user->name, $project->name, count($assignments), $link));
        }
    }

}
