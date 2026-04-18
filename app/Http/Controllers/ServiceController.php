<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Display the services page.
     */
    public function index(): View
    {
        return view('pages.services.index');
    }
}
