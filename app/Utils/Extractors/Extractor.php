<?php


namespace App\Utils\Extractors;


interface Extractor
{

    /**
     * Extracts the package into the given $destinationPath.
     * @param string $destinationPath
     * @param string $packagePath
     */
    public function extractTo(string $destinationPath, string $packagePath);
}
