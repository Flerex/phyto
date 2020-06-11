<?php

namespace App\Domain\Services;

use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Models\Task;
use App\Domain\Models\TaskAssignment;
use App\Domain\Models\TaskProcess;
use App\Domain\Models\User;
use App\Domain\Services\Utils\AssignmentManager;
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
     * @param  Collection  $compatibility
     * @param  int  $processCount
     * @return Task
     * @throws Throwable|NotEnoughMembersForProcessException
     */
    public function create_task(
        Sample $sample,
        Collection $members,
        Collection $compatibility,
        int $processCount = 1
    ): Task {
        return DB::transaction(function () use ($compatibility, $processCount, $members, $sample) {
            if ($members->count() < $processCount) {
                throw new NotEnoughMembersForProcessException;
            }

            /** @var AssignmentManager $assignmentManager */
            $assignmentManager = app(AssignmentManager::class, [
                'compatibility' => $compatibility,
                'processCount' => $processCount,
                'images' => $sample->images->pluck('id'),
                'members' => $members->pluck('id'),
            ]);
            $assignmentsByProcess = $assignmentManager->computeAssignments($processCount);

            $task = $this->createTask($assignmentsByProcess, $sample);
            $this->notifyUsers($assignmentsByProcess, $members, $sample->project);
            return $task;
        });
    }

    /**
     * Computes the assignments for thee given task and returns a collection of objects representing the assignment
     * for every image, wrapped in a collection for every process.
     *
     * This method makes sure no user will be assigned to the same image, ever. Moreover, it tries to equally distribute
     * work load among all members, as long as it doesn't interfere with the previous rule.
     *
     * If the first rule cannot be guarantee, a NotEnoughMembersException is thrown.
     *
     * @param  Collection  $compatibilityAssignments
     * @param  Collection  $images
     * @param  Collection  $members
     * @param  int  $processCount
     * @throws NotEnoughMembersForProcessException
     */
    private function computeAssignments(
        Collection $compatibilityAssignments,
        Collection $images,
        Collection $members,
        int $processCount
    ) {

    }

    /**
     * Creates the task model with the corresponding processes and assignments according to $assignmentsByProcess.
     *
     * @param  Collection  $assignmentsByProcess
     * @param  Sample  $sample
     * @return Task
     */
    private function createTask(Collection $assignmentsByProcess, Sample $sample): Task
    {

        $task = Task::create(['project_id' => $sample->project->getKey(), 'sample_id' => $sample->getKey()]);

        foreach ($assignmentsByProcess as $processAssignments) {
            $process = TaskProcess::create(['task_id' => $task->getKey()]);

            foreach ($processAssignments as $assignment) {
                TaskAssignment::create([
                    'task_process_id' => $process->getKey(),
                    'project_id' => $sample->project->getKey(),
                    'user_id' => $assignment->user,
                    'image_id' => $assignment->image,
                ]);
            }
        }

        return $task;
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
        $assignments = $assignments->flatten(1); // We get rid of the process level

        foreach ($assignments->groupBy('user') as $userId => $assignments) {
            $user = $members->first(fn(User $user) => $user->getKey() === $userId);
            $link = route('projects.assignments.index', compact('project'));
            Mail::to($user)->queue(new NewAssignmentsMail($user->name, $project->name, count($assignments), $link));
        }
    }



}
