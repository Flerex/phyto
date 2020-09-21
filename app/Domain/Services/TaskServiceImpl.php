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
use App\Jobs\SendAutomatedIdentificationRequestJob;
use App\Mail\NewAssignmentsMail;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * @inheritDoc
     */
    public function create_task(
        string $description,
        Sample $sample,
        Collection $members,
        Collection $compatibility,
        int $processCount = 1
    ): Task {
        return DB::transaction(function () use ($description, $compatibility, $processCount, $members, $sample) {
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

            $task = $this->createTask($assignmentsByProcess, $sample, $description);
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
     * @inheritDoc
     */
    private function createTask(Collection $assignmentsByProcess, Sample $sample, string $description): Task
    {

        $task = Task::create([
            'description' => $description,
            'project_id' => $sample->project->getKey(),
            'sample_id' => $sample->getKey()
        ]);

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
     * @inheritDoc
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
     * @inheritDoc
     */
    public function create_automated_task(
        string $description,
        Sample $sample,
        Collection $services
    ): Task {
        return DB::transaction(function () use ($sample, $description, $services) {

            $task = Task::create([
                'description' => $description,
                'project_id' => $sample->project->getKey(),
                'sample_id' => $sample->getKey(),
                'automated' => true,
            ]);

            $assignments = collect();
            foreach ($services as $service) {
                $process = TaskProcess::create(['task_id' => $task->getKey()]);
                foreach ($sample->images as $image) {
                    $assignment = TaskAssignment::create([
                        'task_process_id' => $process->getKey(),
                        'project_id' => $sample->project->getKey(),
                        'service' => $service,
                        'image_id' => $image->getKey(),
                    ]);

                    $assignments->push($assignment);
                }
            }

            // We loop again to make we are not dispatching a job when the transaction rolls back.
            foreach ($assignments as $assignment) {
                SendAutomatedIdentificationRequestJob::dispatch($assignment);
            }

            return $task;
        });
    }


    /**
     * @inheritDoc
     */
    public function get_from_project(Project $project): LengthAwarePaginator
    {
        return $project->tasks()->paginate(config('phyto.pagination_size'));
    }

    /**
     * @inheritDoc
     */
    public function get_processes(Task $task): Collection
    {
        return $task->processes;
    }

    /**
     * @inheritDoc
     */
    public function get_assignments_for_process_with_percentage(TaskProcess $process): Collection
    {
        return $process->assignments
            ->groupBy(fn(TaskAssignment $assignment) => $assignment->user->getKey())
            ->map(function (Collection $group) {
                $images = count($group);
                $finished = $group->filter(fn(TaskAssignment $assignment) => $assignment->finished);
                $percentage = round($finished->count() / $images, 2) * 100;

                return (object) [
                    'user' => $group[0]->user->name,
                    'images' => $images,
                    'percentage' => $percentage,
                ];
            });
    }

    /**
     * @inheritDoc
     */
    public function get_assignments_for_process(TaskProcess $process): LengthAwarePaginator
    {
        return $process->assignments()->paginate(config('phyto.pagination_size'));
    }

    /**
     * @inheritDoc
     */
    public function get_boxes_for_assignment(TaskAssignment $assignment): Collection
    {
        return $assignment->boxes()->get();
    }

    /**
     * @inheritDoc
     */
    public function get_assignment_image(TaskAssignment $assignment): Image
    {
        return $assignment->image;
    }
}
