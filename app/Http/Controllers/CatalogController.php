<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display the product catalog with optional category filtering.
     */
    public function index(Request $request)
    {
        $category = $request->get('category', 'semua');

        $products = Product::active()
            ->when(
                $category !== 'semua',
                fn ($q) => $q->whereHas(
                    'category',
                    fn ($q2) => $q2->where('slug', $category)
                )
            )
            ->with(['category', 'images'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::active()->orderBy('name')->get();

        return view('pages.catalog.index', compact('products', 'category', 'categories'));
    }
}
