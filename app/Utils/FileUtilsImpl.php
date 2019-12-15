<?php


namespace App\Utils;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileUtilsImpl implements FileUtils
{

    /** @var PackageUtils $packageUtils */
    protected $packageUtils;

    public function __construct(PackageUtils $packageUtils)
    {
        $this->packageUtils = $packageUtils;
    }


    /**
     * @inheritDoc
     */
    public function storeImages(Collection $files, string $finalPath): Collection
    {
        $tempDirs = collect();

        $files = $files
            ->map(
                function (string $file) use ($tempDirs) {
                    if (!$this->isPackage($file)) {
                        return $file;
                    }

                    // Directory images were extracted to
                    $packDir = $this->extractPackageImages($file);

                    $tempDirs->push($packDir);

                    return Storage::allFiles($packDir);
                }
            )
            ->flatten()
            ->map(
                function ($path, $i) use ($finalPath) {
                    $extension = File::extension(storage_path($path));

                    $newPath = $finalPath . '/' . $i . '.' . $extension;
                    Storage::move($path, 'public/' . $newPath);

                    return $newPath;
                }
            );

        foreach ($tempDirs as $dir) {
            Storage::deleteDirectory($dir);
        }

        return $files;
    }

    /**
     * Checks whether a file is considered of type package (e.g. a ZIP file).
     *
     * @param  string  $file
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
     * @param  string  $path
     * @return string
     */
    private function extractPackageImages(string $path): ?string
    {
        $packageTempId = uniqid();
        $tempDir = 'temp/' . $packageTempId . '/'; // The temporary directory where the files will be left.

        try {
            if (Storage::exists(
                $tempDir
            )) { // We only extract the zip if it wasn't previously extracted (e.g. a repeated request)
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

            if (isset($extractionDir)) {
                Storage::deleteDirectory($extractionDir);
            }
        }

        return $tempDir;
    }

    /**
     * Returns a list with the valid file types.
     *
     * @param  Collection  $files
     * @return Collection
     */
    private function filterValidFiles(Collection $files): Collection
    {
        $validMimes = collect(config('phyto.valid_sample_mimes.file'));

        return $files->filter(
            function ($file) use ($validMimes) {
                $mimeType = File::mimeType(local_path($file));

                return $validMimes->contains($mimeType);
            }
        );
    }

    /**
     * Extracts the package to a directory in the
     * same place as the package.
     *
     * @param  string  $packagePath
     * @return string The extraction directory.
     * @throws Exceptions\ExtractionException
     */
    private function extractPackageInSamePath(string $packagePath)
    {
        $destination = $packagePath . '_extracted';

        $package = local_path($packagePath);

        $this->packageUtils->extractTo(local_path($destination), $package);

        return $destination;
    }
}
