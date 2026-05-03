<?php

namespace App\Http\Controllers;

use App\Models\Team;

class AboutController extends Controller
{
    public function index()
    {
        $teams = Team::published()
            ->orderBy('id')
            ->get();

        return view('pages.about', compact('teams'));
    }
}
