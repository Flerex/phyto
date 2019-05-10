<?php

namespace App\Http\Controllers\Panel;

use App\Catalog;
use App\Http\Requests\CatalogRequest;
use App\Services\CatalogService;
use App\Species;
use App\Utils\CatalogStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CatalogController extends Controller
{

    /** @var CatalogService $catalogService */
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'sortBy' => ['sometimes', 'string', Rule::in('name', 'id', 'status', 'created_at')],
            'order' => ['sometimes', 'string', Rule::in('asc', 'desc')],
        ]);

        $sortBy = isset($validated['sortBy']) ? $validated['sortBy'] : '';
        $order = isset($validated['order']) ? $validated['order'] : '';

        $catalogs = Catalog::orderBy($sortBy ?? 'id', $order ?? 'desc')->paginate(config('phyto.pagination_size'));

        return view('panel.catalogs.index', compact('catalogs', 'sortBy', 'order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hierarchySelectorLang = array_merge([
            'title' => trans('panel.species.hierarchy_selector'),
            'search' => trans('general.search'),
            'cancel' => trans('general.cancel'),
            'name' => trans('labels.name'),
        ], trans('hierarchy_selector'));

        return view('panel.catalogs.create', compact('hierarchySelectorLang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Catalog $catalog
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Catalog $catalog)
    {
        $this->authorize('edit', $catalog);

        $hierarchySelectorLang = array_merge([
            'title' => trans('panel.species.hierarchy_selector'),
            'search' => trans('general.search'),
            'cancel' => trans('general.cancel'),
            'name' => trans('labels.name'),
        ], trans('hierarchy_selector'));

        $nodes = $catalog->nodes();

        return view('panel.catalogs.edit', compact('catalog', 'nodes', 'hierarchySelectorLang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CatalogRequest $request
     * @param Catalog $catalog
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @param Catalog $catalog
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @param Catalog $catalog
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create_from(Catalog $catalog)
    {

        $this->authorize('create_from', $catalog);

        $hierarchySelectorLang = array_merge([
            'title' => trans('panel.species.hierarchy_selector'),
            'search' => trans('general.search'),
            'cancel' => trans('general.cancel'),
            'name' => trans('labels.name'),
        ], trans('hierarchy_selector'));

        $nodes = $catalog->nodes();

        return view('panel.catalogs.create_from', compact('catalog', 'nodes', 'hierarchySelectorLang'));

    }
}
