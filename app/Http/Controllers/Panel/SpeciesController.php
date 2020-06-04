<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Services\TaxonomyService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class SpeciesController extends Controller
{

    protected TaxonomyService $taxonomyService;

    /**
     * SpeciesController constructor.
     * @param  TaxonomyService  $taxonomyService
     */
    public function __construct(TaxonomyService $taxonomyService)
    {
        $this->taxonomyService = $taxonomyService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {

        $tree = $this->taxonomyService->getTree();

        return view('panel.species.index', compact('tree'));
    }

}
