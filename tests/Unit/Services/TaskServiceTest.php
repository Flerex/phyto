<?php

namespace Tests\Unit\Services;

use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Services\TaskService;
use App\Notifications\ActivateAccountNotification;
use App\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use App\Domain\Models\Role;
use App\Domain\Services\UserService;
use App\Domain\Models\User;
use App\Domain\Enums\Roles;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    protected TaskService $taskService;

    /**
     * Initial configuration for this testing class.
     */
    public function setUp(): void
    {
        parent::setUp();

        // Inject TaskService from the service container
        $this->taskService = $this->app->make(TaskService::class);
    }

    /**
     * @test
     */
    public function create_task_creates_processes_and_assignments()
    {

        // Create the project
        $project = factory(Project::class)->create();

        // We make sure we have enough members to work with.
        $members = factory(User::class, 10)->create();
        $project->users()->attach($members);

        // Create the sample with some images
        $sample = factory(Sample::class)->create([
            'project_id' => $project->getKey(),
        ]);

        // Add images to the sample
        factory(Image::class, 10)->create([
            'sample_id' => $sample->getKey(),
        ]);


        // Get random members for assignment
        $assignees = $members->random(3);
        $task = $this->taskService->create_task($sample, $assignees);

        $this->assertEquals(count($assignees), $task->processes[0]->assignments()->pluck('user_id')->unique()->count());
    }

}
