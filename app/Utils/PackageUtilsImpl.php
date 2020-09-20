<?php


namespace App\Utils;


use App\Utils\Exceptions\ExtractorNotImplementedException;
use App\Utils\Extractors\Extractor;
use App\Utils\Extractors\ZipExtractor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class PackageUtilsImpl implements PackageUtils
{

    protected const IMPLEMENTATIONS = [
        'application/zip' => ZipExtractor::class,
    ];

    /**
     * @inheritDoc
     * @throws ExtractorNotImplementedException
     */
    public function extractTo(string $destinationPath, string $packagePath)
    {

        if (!file_exists($packagePath)) {
            throw new InvalidArgumentException;
        }

        Storage::makeDirectory($destinationPath);

        $extractor = $this->getExtractor(File::mimeType($packagePath));

        $extractor->extractTo($destinationPath, $packagePath);
    }

    /**
     * Returns an instance of the corresponding extractor
     * implementation for the given $mime type.
     *
     * @param  string  $mime
     *
     * @return Extractor
     * @throws ExtractorNotImplementedException
     */
    private function getExtractor(string $mime): Extractor
    {
        if (!isset(self::IMPLEMENTATIONS[$mime])) {
            throw new ExtractorNotImplementedException;
        }

        $implementation = self::IMPLEMENTATIONS[$mime];

        return new $implementation;
    }
}
