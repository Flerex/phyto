<?php


namespace App\Domain\Services\Utils;


use App\Domain\Models\Task;
use App\Domain\Models\TaskAssignment;
use App\Domain\Models\TaskProcess;
use App\Domain\Services\Utils\Dtos\Assignment;
use App\Domain\Services\Utils\Dtos\Availability;
use App\Exceptions\NotEnoughMembersForProcessException;
use Illuminate\Support\Collection;

class AssignmentManagerImpl implements AssignmentManager
{

    /**
     *  The images to assign.
     *
     * @var Collection $workload
     */
    private Collection $images;


    /**
     * The number of assignments (value) for every user (key).
     *
     * This will be used to ensure an equal workload balance.
     *
     * @var Collection $workload
     */
    private Collection $workload;

    /**
     * Members that are assignable for every image.
     *
     * This will be used to keep track of the users that already have been assigned to a given image.
     *
     * @var Collection $availability
     */
    private Collection $availability;


    public function __construct(Collection $compatibility, Collection $images, Collection $members)
    {
        $this->images = $images;
        $this->initializeWorkload($members);
        $this->initializeAvailability($members, $compatibility);
    }


    /**
     * Computes the assignments for a $processes number of processes.
     *
     * This method makes sure no user will be assigned to the same image, ever. Moreover, it tries to equally distribute
     * work load among all members, as long as it doesn't interfere with the previous rule.
     *
     * If the first rule cannot be guarantee, a NotEnoughMembersException is thrown.
     *
     * @param  int  $processes
     * @return Collection
     * @throws NotEnoughMembersForProcessException
     */
    public function computeAssignments(int $processes): Collection
    {
        return empty_collection($processes)->map(function () {
            return $this->images->map(function (int $image) {
                $availability = $this->getAvailabilityOfImage($image);

                $assignee = $this->assignMember($availability);

                return new Assignment($image, $assignee);
            });
        });
    }


    /**
     * Returns the availability object for the given $image.
     *
     * @param  int  $image
     */
    private function getAvailabilityOfImage(int $image): Availability
    {
        return $this->availability->get($image);
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
     * @param  Availability  $availability
     * @throws NotEnoughMembersForProcessException
     */
    private function assignMember(Availability $availability)
    {
        $pool = $availability->getPool();

        if (!$pool) {
            throw new NotEnoughMembersForProcessException;
        }

        $assignee = $this->getFreestMembersForPool($pool)->random();

        $this->addWorkToMember($assignee);

        $availability->makeUserUnavailable($assignee);

        return $assignee;
    }

    /**
     * Returns the freest members available in the $pool.
     *
     * @param  Collection  $pool
     * @return Collection
     */
    private function getFreestMembersForPool(Collection $pool): Collection
    {
        // subset of the workload attribute for the specific $pool.
        $membersWorkload = $this->workload->filter(fn(int $assignmentCount, int $member) => $pool->contains($member));


        $minimumMembersWorkloadValue = $membersWorkload->min();

        // Filter the workload to get only members with the minimum work. Of those members, we pick one randomly.
        return $membersWorkload->filter(fn(int $workload) => $workload === $minimumMembersWorkloadValue)->keys();
    }


    /**
     * Increases by one the amount of work a member has in the $workload.
     *
     * @param  int  $member
     */
    private function addWorkToMember(int $member)
    {
        $this->workload->put($member, $this->workload->get($member) + 1);
    }

    /**
     * Initializes the $availability attribute.
     *
     * @param  Collection  $members
     * @param  Collection  $compatibility
     */
    private function initializeAvailability(Collection $members, Collection $compatibility)
    {
        $compatibilityAssignments = $this->getAssignmentsFromPreviousTasks($compatibility);
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
        $this->availability = $this->images->mapWithKeys(fn(int $image) => [
            $image => new Availability($recurringMembers->diff($compatibilityAssignments->get($image)),
                $newMembers->collect())
        ]);
    }

    /**
     * Initializes the workload attribute.
     *
     * @param  Collection  $members
     */
    private function initializeWorkload(Collection $members)
    {
        $this->workload = $members->mapWithKeys(fn(int $member) => [$member => 0]); // Workload starts at zero
    }
}
