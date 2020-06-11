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

            $compatibilityAssignments = $this->getAssignmentsFromPreviousTasks($compatibility);

            $assignmentsByProcess = $this->computeAssignments($compatibilityAssignments, $sample->images->pluck('id'),
                $members->pluck('id'), $processCount);


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
        $previousMembers = $compatibilityAssignments->flatten()->unique();

        /*
         * Separate the provided members in $recurringMembers (members that were in previous compatible tasks) and
         * $newMembers (members that are completely new to this task).
         */
        [$recurringMembers, $newMembers] = $members->reduce(function (array $carry, int $u) use ($previousMembers) {
            $carry[$previousMembers->contains($u) ? 0 : 1]->push($u);
            return $carry;
        }, [collect(), collect()]);

        // A list of available members from the $members list that are available for every image.
        $availability = $images->mapWithKeys(fn(int $image) => [
            $image => (object) [
                'recurring' => $recurringMembers->diff($compatibilityAssignments->get($image)),
                'new' => $newMembers->collect(),
            ]
        ]);

        // We keep track of every user's assignments to ensure an equal workload balance.
        $workload = $members->mapWithKeys(fn(int $member) => [$member => 0]);

        return empty_collection($processCount)->map(function () use ($workload, $availability, $images) {
            return $images->map(function (int $image) use ($workload, $availability) {
                $availabilityForImage = $availability->get($image);

                if ($availabilityForImage->recurring->isEmpty() && $availabilityForImage->new->isEmpty()) {
                    throw new NotEnoughMembersForProcessException;
                }

                $assignee = $this->assignMember($availabilityForImage->recurring, $workload)
                    ?? $this->assignMember($availabilityForImage->new, $workload);

                return (object) [
                    'image' => $image,
                    'user' => $assignee,
                ];
            });
        });
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

    /**
     * Retrieves the assignments from a list of tasks.
     *
     * @param  Collection  $tasks
     * @return Collection
     */
    private function getAssignmentsFromPreviousTasks(Collection $tasks): Collection
    {
        return $tasks
            ->map(function (Task $task) {
                return $task->processes->map(function (TaskProcess $process) {
                    return $process->assignments;
                });
            })
            ->flatten()
            ->groupBy(fn(TaskAssignment $assignment) => $assignment->image->getKey())
            ->map(function (Collection $assignments) {
                return $assignments->map(function (TaskAssignment $a) {
                    return $a->user->getKey();
                });
            });
    }


    /**
     * Assigns the member in $availability with the lowest workload in $workload and returns it. Assigning a member
     * automatically modifies the $availability list.
     *
     * If more than one member fulfil the condition, one is randomly returned.
     *
     * @param $availability
     * @param  Collection  $workload
     */
    private function assignMember(Collection $availability, Collection $workload)
    {
        if ($availability->isEmpty()) {
            return null;
        }

        // Get the relative workload that only contains members of $member
        $membersWorkload = $workload->filter(fn(int $assignmentCount, int $member) => $availability->contains($member));

        // Filter the workload to get only members with the minimum work. Of those members, we pick one randomly.
        $assignee = $membersWorkload->filter(fn(int $assignmentCount) => $assignmentCount === $membersWorkload->min())
            ->keys()
            ->random();


        $workload->put($assignee, $workload->get($assignee) + 1);

        $availability->forget($availability->search($assignee));

        return $assignee;
    }

}
