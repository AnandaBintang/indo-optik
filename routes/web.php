<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// -------------------------------------------------------------------------
// Public Routes
// -------------------------------------------------------------------------

Route::get("/", [HomeController::class, "index"])->name("home");
Route::get("/tentang-kami", [AboutController::class, "index"])->name("about");
Route::get("/katalog", [CatalogController::class, "index"])->name(
    "catalog.index",
);
Route::get("/produk/{slug}", [ProductController::class, "show"])->name(
    "products.show",
);
Route::get("/layanan", [ServiceController::class, "index"])->name(
    "services.index",
);

// -------------------------------------------------------------------------
// Cart Routes (session-based, no auth required)
// -------------------------------------------------------------------------

Route::get("/keranjang", [CartController::class, "index"])->name("cart.index");
Route::post("/keranjang/tambah", [CartController::class, "add"])->name(
    "cart.add",
);
Route::patch("/keranjang/{key}", [CartController::class, "update"])->name(
    "cart.update",
);
Route::delete("/keranjang/{key}", [CartController::class, "remove"])->name(
    "cart.remove",
);
Route::post("/keranjang/checkout", [CartController::class, "checkout"])->name(
    "cart.checkout",
);
Route::post("/keranjang/promo", [CartController::class, "applyPromo"])->name(
    "cart.promo",
);

// -------------------------------------------------------------------------
// Auth Routes (Breeze scaffolded — do not modify)
// -------------------------------------------------------------------------

require __DIR__ . "/auth.php";

// -------------------------------------------------------------------------
// Authenticated User Routes
// -------------------------------------------------------------------------

Route::middleware("auth")->group(function () {
    // Redirect the Breeze default dashboard to admin dashboard or home
    Route::get("/dashboard", function () {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user instanceof \App\Models\User && $user->hasAdminAccess()) {
            return redirect()->route("admin.dashboard");
        }
        return redirect()->route("home");
    })->name("dashboard");

    // Profile (Breeze scaffolded)
    Route::get("/profil", [ProfileController::class, "edit"])->name(
        "profile.edit",
    );
    Route::patch("/profil", [ProfileController::class, "update"])->name(
        "profile.update",
    );
    Route::delete("/profil", [ProfileController::class, "destroy"])->name(
        "profile.destroy",
    );
});

// -------------------------------------------------------------------------
// Admin Routes
// -------------------------------------------------------------------------

Route::prefix("admin")
    ->name("admin.")
    ->middleware(["auth", "ensure.admin"])
    ->group(function () {
        // Dashboard
        Route::get("/", [Admin\DashboardController::class, "index"])->name(
            "dashboard",
        );

        // ---- Products ----
        Route::post("products/{product}/restore", [
            Admin\ProductController::class,
            "restore",
        ])->name("products.restore");
        Route::resource("products", Admin\ProductController::class);

        // ---- Categories ----
        Route::post("categories/{category}/restore", [
            Admin\CategoryController::class,
            "restore",
        ])->name("categories.restore");
        Route::resource("categories", Admin\CategoryController::class);

        // ---- Testimonials ----
        Route::patch("testimonials/{testimonial}/toggle-status", [
            Admin\TestimonialController::class,
            "toggleStatus",
        ])->name("testimonials.toggle-status");
        Route::resource("testimonials", Admin\TestimonialController::class);

        // ---- Promo Codes ----
        Route::patch("promo-codes/{promoCode}/toggle-status", [
            Admin\PromoCodeController::class,
            "toggleStatus",
        ])->name("promo-codes.toggle-status");
        Route::resource("promo-codes", Admin\PromoCodeController::class);

        // ---- Settings ----
        Route::get("settings", [Admin\SettingController::class, "index"])->name(
            "settings.index",
        );
        Route::put("settings", [
            Admin\SettingController::class,
            "update",
        ])->name("settings.update");

        // ---- Users ----
        Route::get("users", [Admin\UserController::class, "index"])->name(
            "users.index",
        );
        Route::get("users/{user}", [Admin\UserController::class, "show"])->name(
            "users.show",
        );
        Route::patch("users/{user}/role", [
            Admin\UserController::class,
            "updateRole",
        ])->name("users.update-role");
        Route::delete("users/{user}", [
            Admin\UserController::class,
            "destroy",
        ])->name("users.destroy");

        // ---- Orders ----
        Route::get("orders", [Admin\OrderController::class, "index"])->name(
            "orders.index",
        );
        Route::get("orders/{order}", [
            Admin\OrderController::class,
            "show",
        ])->name("orders.show");
        Route::patch("orders/{order}/status", [
            Admin\OrderController::class,
            "updateStatus",
        ])->name("orders.update-status");
    });
