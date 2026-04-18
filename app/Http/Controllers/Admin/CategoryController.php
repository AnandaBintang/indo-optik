<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): View
    {
        $search = $request->input("search");

        $categories = Category::withTrashed()
            ->when($search, fn($q) => $q->where("name", "like", "%{$search}%"))
            ->withCount("products")
            ->orderByRaw('deleted_at IS NOT NULL')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view("admin.categories.index", compact("categories", "search"));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view("admin.categories.create");
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "status" => "required|in:active,inactive",
        ]);

        $slug = $this->uniqueSlug($validated["name"]);

        Category::create([
            "name" => $validated["name"],
            "slug" => $slug,
            "description" => $validated["description"] ?? null,
            "status" => $validated["status"],
        ]);

        return redirect()
            ->route("admin.categories.index")
            ->with(
                "success",
                "Kategori \"{$validated["name"]}\" berhasil ditambahkan.",
            );
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(int $id): View
    {
        $category = Category::withTrashed()->findOrFail($id);

        return view("admin.categories.edit", compact("category"));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $category = Category::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "status" => "required|in:active,inactive",
        ]);

        // Regenerate slug only if the name has changed
        $slug =
            $category->name !== $validated["name"]
                ? $this->uniqueSlug($validated["name"], $category->id)
                : $category->slug;

        $category->update([
            "name" => $validated["name"],
            "slug" => $slug,
            "description" => $validated["description"] ?? null,
            "status" => $validated["status"],
        ]);

        return redirect()
            ->route("admin.categories.index")
            ->with(
                "success",
                "Kategori \"{$validated["name"]}\" berhasil diperbarui.",
            );
    }

    /**
     * Soft-delete the specified category.
     */
    public function destroy(int $id): RedirectResponse
    {
        $category = Category::withTrashed()->findOrFail($id);

        if ($category->trashed()) {
            // Already soft-deleted — permanently delete
            $category->forceDelete();

            return redirect()
                ->route("admin.categories.index")
                ->with(
                    "success",
                    "Kategori \"{$category->name}\" berhasil dihapus permanen.",
                );
        }

        // Check if the category still has active products
        $activeProductCount = $category->products()->count();

        if ($activeProductCount > 0) {
            return redirect()
                ->route("admin.categories.index")
                ->with(
                    "error",
                    "Kategori \"{$category->name}\" memiliki {$activeProductCount} produk dan tidak dapat dihapus.",
                );
        }

        $category->delete();

        return redirect()
            ->route("admin.categories.index")
            ->with(
                "success",
                "Kategori \"{$category->name}\" berhasil dihapus.",
            );
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restore(int $id): RedirectResponse
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()
            ->route("admin.categories.index")
            ->with(
                "success",
                "Kategori \"{$category->name}\" berhasil dipulihkan.",
            );
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Generate a unique slug, optionally excluding the given ID (for updates).
     */
    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $count = 1;

        while (
            Category::withTrashed()
                ->where("slug", $slug)
                ->when($excludeId, fn($q) => $q->where("id", "!=", $excludeId))
                ->exists()
        ) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }
}
