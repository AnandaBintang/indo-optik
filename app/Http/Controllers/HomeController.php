<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->with('images')
            ->take(4)
            ->get();

        $testimonials = Testimonial::published()
            ->take(3)
            ->get();

        return view('pages.home', compact('featuredProducts', 'testimonials'));
    }
}
