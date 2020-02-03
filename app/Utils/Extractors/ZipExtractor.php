<?php


namespace App\Utils\Extractors;


use App\Utils\Exceptions\ExtractionException;
use App\Utils\Extractors\Extractor;
use ZipArchive;

class ZipExtractor implements Extractor
{

    /**
     * @inheritDoc
     */
    public function extractTo(string $destinationPath, string $packagePath)
    {
        $za = new ZipArchive;
        $za->open($packagePath);
        $za->extractTo($destinationPath);
        $za->close();
    }
}
