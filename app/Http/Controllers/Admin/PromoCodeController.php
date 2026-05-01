<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePromoCodeRequest;
use App\Models\PromoCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromoCodeController extends Controller
{
    /**
     * Display a paginated listing of promo codes.
     */
    public function index(Request $request): View
    {
        $search = $request->input("search");
        $isActive = $request->input("is_active");
        $type = $request->input("type");

        $promoCodes = PromoCode::query()
            ->when(
                $search,
                fn($q) => $q->where(function ($q2) use ($search) {
                    $q2->where("code", "like", "%{$search}%")->orWhere(
                        "label",
                        "like",
                        "%{$search}%",
                    );
                }),
            )
            ->when(
                $isActive !== null && $isActive !== "",
                fn($q) => $q->where("is_active", (bool) $isActive),
            )
            ->when(
                $type !== null && $type !== "",
                fn($q) => $q->where("type", $type),
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            "admin.promo-codes.index",
            compact("promoCodes", "search", "isActive", "type"),
        );
    }

    /**
     * Show the form for creating a new promo code.
     */
    public function create(): View
    {
        return view("admin.promo-codes.create");
    }

    /**
     * Store a newly created promo code in storage.
     */
    public function store(StorePromoCodeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Normalize code to uppercase
        $validated["code"] = strtoupper(trim($validated["code"]));

        // Cast boolean
        $validated["is_active"] = $request->boolean("is_active");

        // Initialize usage count
        $validated["usage_count"] = 0;

        PromoCode::create($validated);

        return redirect()
            ->route("admin.promo-codes.index")
            ->with(
                "success",
                "Kode promo \"{$validated["code"]}\" berhasil ditambahkan.",
            );
    }

    /**
     * Show the form for editing the specified promo code.
     */
    public function edit(PromoCode $promoCode): View
    {
        return view("admin.promo-codes.edit", compact("promoCode"));
    }

    /**
     * Update the specified promo code in storage.
     */
    public function update(
        Request $request,
        PromoCode $promoCode,
    ): RedirectResponse {
        $validated = $request->validate([
            "code" =>
                "required|string|max:50|unique:promo_codes,code," .
                $promoCode->id,
            "label" => "nullable|string|max:255",
            "type" => "required|in:percentage,fixed",
            "value" => "required|numeric|min:0",
            "max_discount" => "nullable|numeric|min:0",
            "min_purchase" => "nullable|numeric|min:0",
            "expired_at" => "nullable|date",
            "usage_limit" => "nullable|integer|min:1",
            "is_active" => "nullable|boolean",
        ]);

        // Normalize code to uppercase
        $validated["code"] = strtoupper(trim($validated["code"]));

        // Cast boolean
        $validated["is_active"] = $request->boolean("is_active");

        // Ensure nullable numerics are stored as null when empty
        $validated["max_discount"] = $validated["max_discount"] ?? null;
        $validated["min_purchase"] = $validated["min_purchase"] ?? 0;
        $validated["usage_limit"] = $validated["usage_limit"] ?? null;
        $validated["expired_at"] = $validated["expired_at"] ?? null;


        $promoCode->update($validated);

        return redirect()
            ->route("admin.promo-codes.index")
            ->with(
                "success",
                "Kode promo \"{$promoCode->code}\" berhasil diperbarui.",
            );
    }

    /**
     * Remove the specified promo code from storage.
     */
    public function destroy(PromoCode $promoCode): RedirectResponse
    {
        $code = $promoCode->code;

        // Prevent deletion if the promo code has already been used
        if ($promoCode->usage_count > 0) {
            return redirect()
                ->route("admin.promo-codes.index")
                ->with(
                    "error",
                    "Kode promo \"{$code}\" sudah pernah digunakan dan tidak dapat dihapus. Nonaktifkan saja.",
                );
        }

        $promoCode->delete();

        return redirect()
            ->route("admin.promo-codes.index")
            ->with("success", "Kode promo \"{$code}\" berhasil dihapus.");
    }

    /**
     * Toggle the active status of a promo code.
     */
    public function toggleStatus(PromoCode $promoCode): RedirectResponse
    {
        $promoCode->update(["is_active" => !$promoCode->is_active]);

        $status = $promoCode->is_active ? "diaktifkan" : "dinonaktifkan";

        return redirect()
            ->route("admin.promo-codes.index")
            ->with(
                "success",
                "Kode promo \"{$promoCode->code}\" berhasil {$status}.",
            );
    }

}
