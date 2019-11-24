<?php


namespace App\Services;


use App\Image;
use App\Jobs\NormalizeImagePreview;
use App\Project;
use App\Sample;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageManager;
use Throwable;
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
    public function createProject(
        string $name,
        string $description,
        int $manager_id,
        Collection $catalogs,
        Collection $users
    ): Project {

        $project = new Project;

        $project->fill([
            'name' => $name,
            'description' => $description,
            'user_id' => $manager_id,
        ]);
        $project->users()->attach($users);
        $project->catalogs()->attach($catalogs);
        $project->save();

        return $project;
    }

    /**
     * @param string $name
     * @param string $description
     * @param Collection $files
     * @param Project $project
     * @return Sample
     * @throws Throwable
     */
    public function addSampleToProject(string $name, string $description, Collection $files, Project $project): Sample
    {

        return DB::transaction(function () use ($project, $description, $name, $files) {

            [$packages, $files] = $this->filterFilesByType($files);

            $sample = Sample::create([
                'name' => $name,
                'description' => $description,
                'project_id' => $project->getKey(),
            ]);


            $tempDirs = collect();
            foreach ($packages as $package) {
                $tempDir = $this->extractPackage($package);
                $tempDirs->push($tempDir);
                $files = $files->merge(Storage::allFiles($tempDir));
            }

            $files = $files->map(function ($path, $i) use ($sample) {

                $extension = File::extension(storage_path($path));

                $newPath = 'sample-images/' . $sample->getKey() . '/' . $i . '.' . $extension;
                Storage::move($path, 'public/' . $newPath);

                $image = Image::create([
                    'sample_id' => $sample->getKey(),
                    'path' => $newPath,
                ]);

                NormalizeImagePreview::dispatch($image);

                return $image;
            });

            foreach ($tempDirs as $dir) {
                Storage::deleteDirectory($dir);
            }

            $sample->images()->saveMany($files);

            return $sample;
        });

    }


    /**
     * Filters a collection of files, returning an array that differentiates packages
     * from files.
     *
     * @param Collection $files
     * @return Collection Collection of collections with the first item being a
     * collection of packages and the second a collection of common files.
     */
    private function filterFilesByType(Collection $files)
    {
        return $files->reduce(function ($carry, $file) {
            $key = (int) !$this->isPackage($file); // First key stores packages, second key the common files. That's why it's negated.
            $carry[$key]->push($file);
            return $carry;
        }, [collect(), collect()]);
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
        $path = local_path($file);
        return in_array(File::mimeType($path), $packageMimes);
    }

    /**
     * Extracts the provided packages and returns the path to the
     * extracted files parent directory.
     *
     * If a file is not of an allowed MIME type, it will be discarded.
     *
     * @param string $path
     * @return string
     */
    private function extractPackage(string $path): ?string
    {
        $packageTempId = uniqid();
        $tempDir = 'temp/' . $packageTempId . '/'; // The temporary directory where the files will be left.

        try {

            if (Storage::exists($tempDir)) { // We only extract the zip if it wasn't previously extracted (e.g. a repeated request)
                $output['files'] = collect(Storage::allFiles($tempDir));
                return $output;
            }

            $extractionDir = $this->extractPackageInSamePath($path);

            $files = collect(Storage::allFiles($extractionDir));

            // Move all the valid files to the temp directory
            foreach ($this->filterValidFiles($files) as $file) {
                $name = substr($file, strrpos($file, '/')); // Contains the slash (/)
                $newPath = $tempDir . $name;
                Storage::move($file, $newPath);
            }
        } catch (Exception $e) {
            return null;
        } finally {
            Storage::delete($path);
            Storage::deleteDirectory($extractionDir);
        }

        return $tempDir;
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
            $mimeType = File::mimeType(local_path($file));

            return $validMimes->contains($mimeType);
        });
    }

    /**
     * Extracts the package to a directory in the
     * same place as the package.
     *
     * @param string $packagePath
     * @param string $directory
     *
     * @return string The extraction directory.
     * @throws Exception
     */
    private function extractPackageInSamePath(string $packagePath)
    {

        $directory = $packagePath . '_extracted';

        Storage::makeDirectory($directory);

        $packagePath = local_path($packagePath);

        if (File::mimeType($packagePath) !== 'application/zip') {
            throw new Exception('Exporting that file type is not supported.');
        }

        $za = new ZipArchive;
        $za->open($packagePath);
        $za->extractTo(local_path($directory));
        $za->close();

        return $directory;
    }


}
