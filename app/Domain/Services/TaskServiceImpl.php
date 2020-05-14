<?php

namespace App\Domain\Services;

use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Models\Task;
use App\Domain\Models\TaskAssignment;
use App\Domain\Models\TaskProcess;
use App\Domain\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     * @param  int  $repetitions
     * @param  int  $processes
     * @return Task
     * @throws Throwable
     */
    public function create_task(Sample $sample, Collection $members, int $repetitions = 1, int $processes = 1): Task
    {
        return DB::transaction(function () use ($processes, $repetitions, $members, $sample) {

            $task = Task::create([
                'project_id' => $sample->project_id,
                'user_id' => optional(Auth::user())->getKey(),
                'sample_id' => $sample->getKey(),
            ]);


            $membersCount = $members->count();


            $processes = collect(range(0, $processes - 1))
                ->map(fn($i) => TaskProcess::create(['task_id' => $task->getKey()]));


            /*
             * Create a collection that maps 1:1 to the Task Assignments. The algorithm works as follows:
             *
             * For every process we iterate through the images of the sample to assign to them the $imageIndex modulo
             * the number of members in the task in case there's more images than members. This will grantee that all
             * users are assigned equally to every image.
             *
             * Now, as multiple users might be assigned to the same image, we iterate $repetitions times for every
             * image and use the index of this loop as an offset in the array position. This means that for two users
             * per image, the current one and the next one will be selected for one picture iteration. This also equally
             * distributes the load.
             *
             * Finally, we have to take into account the loop for the processes, as we must make sure that in two
             * different processes, no user will tag the same image. To do this, we first know that the $processes
             * variable will never have less than count($members) / $repetitions, and then, we add $processIndex *
             * $repetitions to the position index so that the member array is shifted in groups, so that every process
             * will start in a different group of users for one image.
             */
            $assignments = $processes->map(
                fn(TaskProcess $process, int $processIndex) => $sample->images->map(
                    fn(Image $image, int $imageIndex) => collect(range(0, $repetitions - 1))->map(
                        fn($offset) => (object) [
                            'process' => $process->getKey(),
                            'image' => $image->getKey(),
                            'user' => $members[($imageIndex + $offset + $processIndex * $repetitions) % $membersCount]->getKey(),
                        ]
                    )
                )
            )->flatten(2);


            foreach ($assignments as $assignment) {
                TaskAssignment::create([
                    'task_process_id' => $assignment->process,
                    'user_id' => $assignment->user,
                    'image_id' => $assignment->image,
                ]);
            }


            return $task;
        });
    }

}
