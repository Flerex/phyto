<?php


namespace App\Domain\Services;


use App\Domain\Models\Image;
use App\Domain\Models\User;
use App\Jobs\NormalizeImagePreviewJob;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Utils\FileUtils;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProjectServiceImpl implements ProjectService
{

    /** @var FileUtils $fileUtils */
    protected $fileUtils;

    public function __construct(FileUtils $fileUtils)
    {
        $this->fileUtils = $fileUtils;
    }

    /**
     * Create a project.
     *
     * @param string $name
     * @param string $description
     * @param int $manager_id
     * @param Collection $catalogs
     * @param Collection $users
     * @return Project
     */
    public function createProject(string $name, string $description, int $manager_id, Collection $catalogs, Collection $users): Project {

        $project = Project::create([
            'name' => $name,
            'description' => $description,
            'user_id' => $manager_id,
        ]);

        $project->users()->attach($users);
        $project->catalogs()->attach($catalogs);

        return $project;
    }


    /**
     * @param string $name
     * @param string $description
     * @param Carbon $takenOn
     * @param Collection $files
     * @param Project $project
     * @return Sample
     * @throws Throwable
     */
    public function addSampleToProject(string $name, string $description, Carbon $takenOn, Collection $files, Project $project): Sample
    {

        return DB::transaction(function() use ($takenOn, $project, $description, $name, $files) {

            $sample = Sample::create([
                'name' => $name,
                'description' => $description,
                'taken_on' => $takenOn,
                'project_id' => $project->getKey(),
            ]);

            $files = $this->fileUtils->storeImages($files, 'sample-images/' . $sample->getKey());

            foreach($files as $file) {
                $image = Image::create([
                    'sample_id' => $sample->getKey(),
                    'original_path' => $file,
                ]);

                NormalizeImagePreviewJob::dispatch($image)->onQueue('image-processing');
            }

            return $sample;
        });

    }


    /**
     * Retrieves all members of a project.
     * @param  Project  $project
     * @return Collection
     */
    public function get_members(Project $project): Collection
    {
        return $project->users;
    }

    public function set_member_status(Project $project, User $member, bool $status)
    {
        return $project->users()->updateExistingPivot($member, ['active' => $status]);
    }

    public function add_members(Project $project, Collection $users)
    {
        $alreadyAdded = $project->users
            ->push($project->manager)
            ->pluck('id');

        $filteredUsers = $users->diff($alreadyAdded);

        $project->users()->attach($filteredUsers);
    }
}
