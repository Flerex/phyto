<?php


namespace App\Services;


use App\Image;
use App\Project;
use App\Sample;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PharData;
use Exception;
use ZipArchive;

class ProjectServiceImpl implements ProjectService
{

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
        $project->save();

        return $project;
    }

    public function addSampleToProject(string $name, string $description, Collection $files, Project $project): Sample
    {

        return DB::transaction(function () use ($project, $description, $name, $files) {

            ['packages' => $packages, 'files' => $files] = $files->reduce(function ($carry, $file) {
                $key = $this->isPackage($file->path . $file->name) ? 'packages' : 'files';
                $carry[$key]->push($file);
                return $carry;
            }, [
                'packages' => collect(),
                'files' => collect(),
            ]);

            $sample = Sample::create([
                'name' => $name,
                'description' => $description,
                'project_id' => $project->getKey(),
            ]);

            $files = $files->concat($this->extractPackages($packages));

            $files = $files->map(function ($path) use ($sample) {
                return Image::create([
                    'sample_id' => $sample->getKey(),
                    'path' => $path,
                ]);
            });

            $sample->images()->saveMany($files);

            return $sample;
        });

    }

    /**
     * Checks whether a file is considered of type package (e.g. a ZIP file).
     *
     * @param string $file
     * @return bool
     */
    private function isPackage(string $file)
    {
        $packageMimes = config('phyto.valid_sample_mimes.package');
        $path = storage_path('app/' . $file);
        return in_array(File::mimeType($path), $packageMimes);
    }

    /**
     * Extracts the provided packages and returns the paths to their
     * containing files.
     *
     * If a file is not of an allowed MIME type, it will be discarded.
     *
     * @param Collection $packages
     * @return Collection
     */
    private function extractPackages(Collection $packages) : Collection
    {

        $files = collect();

        foreach ($packages as $package) {
            $packagePath = $package->path . $package->name;
            $directory = $packagePath . '_contents';

            try {
                $this->extractPackageTo($packagePath, $directory);

                $files = collect(Storage::allFiles($directory));

                $files = $this->filterValidFiles($files);

                foreach ($files as $file) {
                    $name = substr($file, strrpos($file, '/')); // Contains the slash (/)
                    Storage::move($file, $packagePath . '_valid' . $name);
                }

            } catch (Exception $e) {
                continue;
            } finally {
                // fixme: remove this comment
                // Storage::delete($packagePath);
                Storage::deleteDirectory($directory);
            }
        }
        return $files;
    }

    /**
     * Returns a list with the valid file types.
     *
     * @param Collection $files
     * @return Collection
     */
    private function filterValidFiles(Collection $files): Collection
    {
        $validMimes = collect(config('phyto.valid_sample_mimes.file'));

        return $files->filter(function ($file) use ($validMimes) {
            $mimeType = File::mimeType(storage_path('app/' . $file));

            return $validMimes->contains($mimeType);
        });
    }

    /**
     * Extracts the package to the given directory.
     *
     * @param string $packagePath
     * @param string $directory
     *
     * @throws Exception
     */
    private function extractPackageTo(string $packagePath, string $directory)
    {
        Storage::makeDirectory($directory);

        $packagePath = storage_path('app/' . $packagePath);

        if (File::mimeType($packagePath) !== 'application/zip') {
            throw new Exception('Exporting that file type is not supported.');
        }

        $za = new ZipArchive;
        $za->open($packagePath);
        $za->extractTo(storage_path('app/' . $directory));
        $za->close();
    }

}
