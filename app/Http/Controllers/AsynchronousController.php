<?php

namespace App\Http\Controllers;

use App\Domain;
use Illuminate\Http\Request;

class AsynchronousController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function species()
    {
        return Domain::with('classis.genera.species')->get();
    }
}
