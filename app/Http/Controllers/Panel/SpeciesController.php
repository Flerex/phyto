<?php

namespace App\Http\Controllers\Panel;

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

        $hierarchySelectorLang = array_merge([
            'title' => trans('panel.species.hierarchy_selector'),
            'search' => trans('general.search'),
            'cancel' => trans('general.cancel'),
            'name' => trans('labels.name'),
        ], trans('hierarchy_selector'));

        return view('panel.species.index', compact('hierarchySelectorLang'));
    }
    
}
