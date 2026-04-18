<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Testimonial;
use App\Policies\ProductPolicy;
use App\Policies\TestimonialPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // -------------------------------------------------------------------------
        // Policies
        // -------------------------------------------------------------------------

        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Testimonial::class, TestimonialPolicy::class);
    }
}
