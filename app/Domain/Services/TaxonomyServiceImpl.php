<?php

namespace App\Domain\Services;

use App\Domain\Models\Catalog;
use App\Domain\Models\Domain;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class TaxonomyServiceImpl implements TaxonomyService
{

    public function getTree(): Collection
    {
        return Domain::with('children.children.children')->get();

    }

    public function generateJson(): string
    {
        $path = 'species/tree.json';

        Storage::put($path, Domain::with('children.children.children')->get()->toJson(JSON_PRETTY_PRINT));

        return $path;
    }
}
