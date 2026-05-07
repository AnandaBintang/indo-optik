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
        $category = $request->get('category', 'all');
        $search = $request->get('q');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $stock = $request->get('stock');
        $discount = $request->boolean('discount');
        $featured = $request->boolean('featured');
        $sort = $request->get('sort', 'latest');

        $productsQuery = Product::active()
            ->with(['category', 'images'])
            ->when(
                $category !== 'all',
                fn ($q) => $q->whereHas(
                    'category',
                    fn ($q2) => $q2->where('slug', $category)
                )
            )
            ->when(
                $search,
                fn ($q) => $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($q3) => $q3->where('name', 'like', "%{$search}%"));
                })
            )
            ->when(
                $minPrice !== null && $minPrice !== '',
                function ($q) use ($minPrice) {
                    $min = (int) $minPrice;
                    $q->where(function ($q2) use ($min) {
                        $q2->whereNotNull('discount_price')
                            ->where('discount_price', '>=', $min);
                    })->orWhere(function ($q2) use ($min) {
                        $q2->whereNull('discount_price')
                            ->where('price', '>=', $min);
                    });
                }
            )
            ->when(
                $maxPrice !== null && $maxPrice !== '',
                function ($q) use ($maxPrice) {
                    $max = (int) $maxPrice;
                    $q->where(function ($q2) use ($max) {
                        $q2->whereNotNull('discount_price')
                            ->where('discount_price', '<=', $max);
                    })->orWhere(function ($q2) use ($max) {
                        $q2->whereNull('discount_price')
                            ->where('price', '<=', $max);
                    });
                }
            )
            ->when(
                $stock === 'in',
                fn ($q) => $q->where('stock', '>', 0)
            )
            ->when(
                $stock === 'out',
                fn ($q) => $q->where('stock', '<=', 0)
            )
            ->when(
                $discount,
                fn ($q) => $q->whereNotNull('discount_price')
                    ->whereColumn('discount_price', '<', 'price')
            )
            ->when(
                $featured,
                fn ($q) => $q->where('is_featured', true)
            );

        switch ($sort) {
            case 'price_asc':
                $productsQuery->orderByRaw('COALESCE(discount_price, price) asc');
                break;
            case 'price_desc':
                $productsQuery->orderByRaw('COALESCE(discount_price, price) desc');
                break;
            case 'name_asc':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $productsQuery->orderBy('name', 'desc');
                break;
            case 'discount_desc':
                $productsQuery->orderByRaw('(price - COALESCE(discount_price, price)) desc');
                break;
            default:
                $productsQuery->latest();
                break;
        }

        $products = $productsQuery
            ->paginate(12)
            ->withQueryString();

        $categories = Category::active()->orderBy('name')->get();

        return view('pages.catalog.index', compact(
            'products',
            'categories',
            'category',
            'search',
            'minPrice',
            'maxPrice',
            'stock',
            'discount',
            'featured',
            'sort',
        ));
    }
}
