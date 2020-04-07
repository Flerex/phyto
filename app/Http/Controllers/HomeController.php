<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Factory|View
     */
    public function index()
    {

        $projects = collect();
        if (Auth::check()) {
            $user = Auth::user();
            $projects = $user->projects
                ->union($user->managedProjects)
                ->unique('id');
        }

        return view('home', compact('projects'));
    }
}
