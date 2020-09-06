<?php

namespace App\Domain\Services;

use App\Domain\Models\Catalog;
use Illuminate\Support\Collection;

interface TaxonomyService
{

    /**
     * Returns the taxonomy tree, containing all hierarchical elements (species, domains, etc.) from the application,
     * in a hierarchical order (i.e. nested).
     *
     * @return Collection
     */
    public function getTree() : Collection;

    /**
     * Returns the path to a file that contains the Taxonomy in JSON format.
     * @return string
     */
    public function generateJson(): string;
}
