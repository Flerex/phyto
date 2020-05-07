<?php


namespace Tests\Unit\Services;


use App\Domain\Models\Catalog;
use App\Domain\Models\Image;
use App\Jobs\NormalizeImagePreview;
use App\Domain\Models\Project;
use App\Services\ProjectService;
use App\Domain\Models\User;
use App\Utils\FileUtils;
use App\Utils\FileUtilsImpl;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ProjectServiceTest extends TestCase
{

    /** @var ProjectService $projectService */
    protected $projectService;

    public function setUp(): void
    {
        parent::setUp();

        // Inject UserService from the service container
        $this->projectService = $this->app->make(ProjectService::class);
    }


    public function tearDown() : void {
        Mockery::close();
    }

    /**
     * @test Test the create a project method.
     */
    public function create_a_project()
    {
        $manager = factory(User::class)->create();

        $catalogs = factory(Catalog::class, 3)->create();

        $users = factory(User::class, 10)->create();

        $name = $this->faker->sentence(5);
        $description = $this->faker->paragraph(2);

        $project = $this->projectService->createProject($name, $description, $manager->getKey(), $catalogs, $users);

        $this->assertEquals(count($catalogs), $project->catalogs()->count());
        $this->assertEquals(count($users), $project->users()->count());
        $this->assertEquals($manager->getKey(), $project->manager->getKey());
        $this->assertEquals($name, $project->name);
        $this->assertEquals($description, $project->description);
    }

    /**
     * @test Test the addition of a sample to an already created project
     */
    public function add_sample_to_project()
    {
        Queue::fake();

        $files = collect(range(1, rand(1, 10)))->map(function () {
            return $this->faker->word . '.' . $this->faker->lexify('???');
        });

        $this->mock(FileUtils::class, function (MockInterface $mock) use ($files) {
            $mock->shouldReceive('storeImages')->withAnyArgs()->andReturn($files);
        });

        $projectService = $this->app->make(ProjectService::class);

        $project = factory(Project::class)->create();

        $name = $this->faker->sentence(5);
        $description = $this->faker->paragraph(2);

        $projectService->addSampleToProject($name, $description, new Carbon, $files, $project);

        $this->assertEquals(1, $project->samples()->count());

        $projectImages = $project->samples()->first()->images;
        $this->assertEquals($files->count(), $projectImages->count());

        foreach ($files as $file) {
            $this->assertTrue($projectImages->contains(function (Image $image) use ($file) {
                return $image->original_path == $file;
            }));
        }

        Queue::assertPushed(NormalizeImagePreview::class, $files->count());
    }
}
