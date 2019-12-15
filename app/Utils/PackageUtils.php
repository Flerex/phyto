<?php


namespace App\Utils;


use App\Utils\Exceptions\ExtractionException;

interface PackageUtils
{

    /**
     * Extracts the given package into the destination directory.
     *
     * If the destination directory does not exist, it is created.
     *
     * @param string $destinationPath
     * @param string $packagePath
     * @return mixed
     * @throws ExtractionException
     */
    public function extractTo(string $destinationPath, string $packagePath);
}
