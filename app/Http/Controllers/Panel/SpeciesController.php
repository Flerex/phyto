<?php

namespace App\Http\Controllers\Panel;

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
    public function index(Request $request)
    {

        $species = Species::paginate(config('phyto.pagination_size'));

        $mode = $request->get('mode');
        $id = $request->get('model');

        if ($mode && in_array($mode, ['domain', 'genus', 'classis']) && $id) {

            $class = '\\App\\' . ucfirst($mode);
            $species = $class::findOrFail($id)->species()->paginate(config('phyto.pagination_size'));
        }

        $domains = Domain::with('classis.genera.species')->get();

        return view('panel.species.index', compact('species', 'domains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
