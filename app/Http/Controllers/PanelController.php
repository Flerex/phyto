<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class PanelController extends Controller
{
    public function index()
    {
        return view('panel.index');
    }
}
