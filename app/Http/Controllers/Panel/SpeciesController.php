<?php

namespace App\Http\Controllers\Panel;

use App\Classis;
use App\Domain;
use App\Species;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpeciesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $hierarchySelectorLang = [
            'title' => trans('panel.species.hierarchy_selector'),
            'search' => trans('general.search'),
        ];

        return view('panel.species.index', compact('hierarchySelectorLang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'domain' => 'exists:domains,id',
            'classis' => 'exists:classis,id',
            'genus' => 'exists:genera,id',
        ]);

        $domains = Domain::with('classis.genera.species')->get();

        $lastStep = $request->has('genus');

        $result = compact('domains', 'lastStep');

        // FIXME: Needs refactor. Export validation logic to own class.


        if ($request->has('domain')) {
            $domain = Domain::firstOrFail($request->get('domain'));
            $classis = $domain->classis->get();
            $result = array_merge($result, $classis);
        }

        if ($request->has('classis')) {
            $classis = Classis::firstOrFail($request->get('genus'));
            $genera = $classis->genera->get();
            $result = array_merge($result, $genera);
        }

        return view('panel.species.create', $result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
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
