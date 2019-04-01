<?php

namespace App\Http\Controllers\Panel;

use App\Catalog;
use App\Http\Requests\CreateCatalogRequest;
use App\Species;
use App\Utils\CatalogStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catalogs = Catalog::orderBy('id', 'desc')->paginate(config('phyto.pagination_size'));
        return view('panel.catalogs.index', compact('catalogs'));
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCatalogRequest $request)
    {

        $validated = collect($request->validated());

        $catalog = Catalog::create([
            'name' => $validated->pull('name'),
            'status' => CatalogStatus::EDITING,
        ]);


        foreach ($validated as $nodeType => $list) {
            $catalog->$nodeType()->attach($list);
        }

        return redirect()->route('panel.catalogs.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Catalog $catalog)
    {
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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
