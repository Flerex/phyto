<?php

namespace App\Utils;

use Illuminate\Support\Collection;

interface FileUtils
{

    /**
     * Stores the images corresponding to a file in $files
     * and stores them in $path. Returns a collection with
     * the new routes.
     *
     * A file can be either an image (in which case it'd
     * be only moved to the destination $path with a new
     * name) or a compressed file. If it's a compressed
     * file, this method will find for images inside.
     *
     * @param Collection $files
     * @param string $path
     * @return Collection
     */
    public function storeImages(Collection $files, string $path) : Collection;
}
