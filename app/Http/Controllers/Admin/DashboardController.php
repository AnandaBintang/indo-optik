<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with summary statistics and recent orders.
     */
    public function index()
    {
        $totalProducts     = Product::count();
        $totalOrders       = Order::count();
        $totalTestimonials = Testimonial::count();
        $totalUsers        = User::where('role', User::ROLE_USER)->count();

        $recentOrders = Order::with(['items', 'user'])
            ->latest()
            ->take(10)
            ->get();

        // Revenue stats
        $totalRevenue = Order::where('status', Order::STATUS_COMPLETED)->sum('total');

        $pendingOrders    = Order::where('status', Order::STATUS_PENDING)->count();
        $processingOrders = Order::where('status', Order::STATUS_PROCESSING)->count();
        $completedOrders  = Order::where('status', Order::STATUS_COMPLETED)->count();
        $cancelledOrders  = Order::where('status', Order::STATUS_CANCELLED)->count();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalTestimonials',
            'totalUsers',
            'recentOrders',
            'totalRevenue',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'cancelledOrders',
        ));
    }
}
