<?php

namespace App\Domain\Services;

use App\Domain\Models\Catalog;
use App\Domain\Models\Domain;
use Illuminate\Support\Collection;

class TaxonomyServiceImpl implements TaxonomyService
{

    public function getTree(): Collection
    {
        return Domain::with('children.children.children')->get();

    }

}
