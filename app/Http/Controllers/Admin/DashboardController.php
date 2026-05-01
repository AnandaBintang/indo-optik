<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PromoCode;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display catalog and content statistics for the WhatsApp-only storefront.
     */
    public function index()
    {
        $totalProducts     = Product::count();
        $activeProducts    = Product::active()->count();
        $totalCategories   = Category::count();
        $activePromos      = PromoCode::where('is_active', true)->count();
        $totalTestimonials = Testimonial::count();
        $publishedReviews  = Testimonial::where('status', 'published')->count();
        $totalUsers        = User::where('role', User::ROLE_USER)->count();

        $latestProducts = Product::with('category')
            ->latest()
            ->take(6)
            ->get();

        $latestPromos = PromoCode::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'activeProducts',
            'totalCategories',
            'activePromos',
            'totalTestimonials',
            'publishedReviews',
            'totalUsers',
            'latestProducts',
            'latestPromos',
        ));
    }
}
