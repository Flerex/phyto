<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Models\Catalog;
use App\Domain\Services\TaxonomyService;
use App\Exceptions\CatalogStatusException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogRequest;
use App\Domain\Services\CatalogService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CatalogController extends Controller
{

    protected CatalogService $catalogService;

    protected TaxonomyService $taxonomyService;

    /**
     * CatalogController constructor.
     * @param  CatalogService  $catalogService
     * @param  TaxonomyService  $taxonomyService
     */
    public function __construct(CatalogService $catalogService, TaxonomyService $taxonomyService)
    {
        $this->catalogService = $catalogService;
        $this->taxonomyService = $taxonomyService;
    }


    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'sortBy' => ['sometimes', 'string', Rule::in('name', 'id', 'status', 'created_at')],
            'order' => ['sometimes', 'string', Rule::in('asc', 'desc')],
        ]);

        $catalogs = Catalog::orderBy($validated['sortBy'] ?? 'id', $validated['order'] ?? 'desc')
            ->paginate(config('phyto.pagination_size'));

        return view('panel.catalogs.index', compact('catalogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {

        $tree = $this->taxonomyService->getTree();

        return view('panel.catalogs.create', compact('tree'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CatalogRequest  $request
     * @return RedirectResponse
     */
    public function store(CatalogRequest $request)
    {

        $validated = collect($request->validated());

        $name = $validated->pull('name');

        $mode = $validated->pull('mode');

        $catalog = $this->catalogService->createCatalog($name, $validated);

        if ($mode && $mode === 'seal') {
            $this->catalogService->sealCatalog($catalog->getKey());
        }

        return redirect()->route('panel.catalogs.index');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Catalog $catalog
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function edit(Catalog $catalog)
    {
        $this->authorize('edit', $catalog);

        $tree = $this->taxonomyService->getTree();

        $nodes = $catalog->nodes();

        return view('panel.catalogs.edit', compact('catalog', 'tree', 'nodes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CatalogRequest $request
     * @param Catalog $catalog
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(CatalogRequest $request, Catalog $catalog)
    {

        $this->authorize('edit', $catalog);

        $validated = collect($request->validated());

        $name = $validated->pull('name');

        $this->catalogService->overrideCatalog($catalog->getKey(), $name, $validated);

        return redirect()->route('panel.catalogs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Catalog  $catalog
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws CatalogStatusException
     */
    public function destroy(Catalog $catalog)
    {
        $this->authorize('destroy', $catalog);

        $this->catalogService->destroyCatalog($catalog->getKey());

        return back()->with('alert', trans('panel.catalogs.destroyed_alert', ['catalog' => $catalog->name]));

    }

    /**
     * Changes the status of a Catalog to sealed, so it cannot be edited anymore.
     *
     * @param Catalog $catalog
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function seal(Catalog $catalog)
    {
        $this->authorize('seal', $catalog);

        $this->catalogService->sealCatalog($catalog->getKey());

        return back()->with('alert', trans('panel.catalogs.sealed_alert', ['catalog' => $catalog->name]));
    }

    /**
     * Changes the status of a Catalog to obsolete, so it cannot be used anymore.
     *
     * @param  Catalog  $catalog
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws CatalogStatusException
     */
    public function markAsObsolete(Catalog $catalog)
    {
        $this->authorize('markAsObsolete', $catalog);

        $this->catalogService->markCatalogAsObsolete($catalog->getKey());

        return back()->with('alert', trans('panel.catalogs.obsolete_alert', ['catalog' => $catalog->name]));
    }

    /**
     * Changes the status of a Catalog back to sealed.
     *
     * @param  Catalog  $catalog
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws CatalogStatusException
     */
    public function restore(Catalog $catalog)
    {
        $this->authorize('restore', $catalog);

        $this->catalogService->restoreCatalog($catalog->getKey());

        return back()->with('alert', trans('panel.catalogs.restore_alert', ['catalog' => $catalog->name]));
    }

    /**
     * Creates a catalog from a given catalog
     *
     * @param Catalog $catalog
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function create_from(Catalog $catalog)
    {

        $this->authorize('create_from', $catalog);

        $tree = $this->taxonomyService->getTree();

        $nodes = $catalog->nodes();

        return view('panel.catalogs.create_from', compact('catalog', 'tree', 'nodes'));

    }
}
