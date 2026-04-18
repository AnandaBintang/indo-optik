<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a paginated listing of products with optional search and filters.
     */
    public function index(Request $request): View
    {
        $search     = $request->get('search');
        $categoryId = $request->get('category_id');
        $status     = $request->get('status');

        $products = Product::withTrashed()
            ->with(['category'])
            ->when(
                $search,
                fn ($q) => $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('sku', 'like', "%{$search}%");
                })
            )
            ->when(
                $categoryId,
                fn ($q) => $q->where('category_id', $categoryId)
            )
            ->when(
                $status !== null && $status !== '',
                function ($q) use ($status) {
                    if ($status === 'trashed') {
                        $q->onlyTrashed();
                    } elseif ($status !== 'all') {
                        $q->where('status', $status);
                    }
                }
            )
            ->orderByRaw('deleted_at IS NOT NULL')
            ->orderBy('status', 'asc')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.index', compact(
            'products',
            'categories',
            'search',
            'categoryId',
            'status',
        ));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Generate a unique slug from the product name
        $validated['slug'] = $this->uniqueSlug($validated['name']);

        // Handle primary image upload
        if ($request->filled('image_url')) {
            $validated['image'] = $request->input('image_url');
        } elseif ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        // Cast boolean field
        $validated['is_featured'] = $request->boolean('is_featured');

        $product = Product::create($validated);

        // Handle additional product images
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $index => $file) {
                $path = $file->store('products', 'public');

                $product->images()->create([
                    'image'      => $path,
                    'alt_text'   => $product->name . ' ' . ($index + 1),
                    'is_primary'  => false,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Produk \"{$product->name}\" berhasil ditambahkan.");
    }

    /**
     * Redirect show to edit — admin has no separate show view.
     */
    public function show(int $id): RedirectResponse
    {
        return redirect()->route('admin.products.edit', $id);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(int $id): View
    {
        $product    = Product::withTrashed()->with('images')->findOrFail($id);
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, int $id): RedirectResponse
    {
        $product   = Product::withTrashed()->findOrFail($id);
        $validated = $request->validated();

        // Regenerate slug only if the name has changed
        if ($product->name !== $validated['name']) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], $product->id);
        } else {
            unset($validated['slug']);
        }

        // Handle primary image upload
        if ($request->filled('image_url')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->input('image_url');
        } elseif ($request->hasFile('image')) {
            // Delete the old primary image if one exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        // Cast boolean field
        $validated['is_featured'] = $request->boolean('is_featured');

        $product->update($validated);

        // Handle additional product images
        if ($request->hasFile('additional_images')) {
            $currentMaxOrder = $product->images()->max('sort_order') ?? 0;

            foreach ($request->file('additional_images') as $index => $file) {
                $path = $file->store('products', 'public');

                $product->images()->create([
                    'image'      => $path,
                    'alt_text'   => $product->name . ' ' . ($currentMaxOrder + $index + 1),
                    'is_primary'  => false,
                    'sort_order' => $currentMaxOrder + $index + 1,
                ]);
            }
        }

        // Handle deletion of specific additional images
        if ($request->has('delete_images')) {
            $imageIds = array_filter((array) $request->input('delete_images'));

            foreach ($product->images()->whereIn('id', $imageIds)->get() as $img) {
                if (Storage::disk('public')->exists($img->image)) {
                    Storage::disk('public')->delete($img->image);
                }
                $img->delete();
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Produk \"{$product->name}\" berhasil diperbarui.");
    }

    /**
     * Soft-delete the specified product.
     */
    public function destroy(int $id): RedirectResponse
    {
        $product = Product::withTrashed()->findOrFail($id);

        if ($product->trashed()) {
            // Permanently delete the product and its images
            foreach ($product->images as $img) {
                if (Storage::disk('public')->exists($img->image)) {
                    Storage::disk('public')->delete($img->image);
                }
                $img->delete();
            }

            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $product->forceDelete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', "Produk \"{$product->name}\" berhasil dihapus permanen.");
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Produk \"{$product->name}\" berhasil dihapus.");
    }

    /**
     * Restore a soft-deleted product.
     */
    public function restore(int $id): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Produk \"{$product->name}\" berhasil dipulihkan.");
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Generate a unique slug for a product, optionally excluding an existing ID.
     */
    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base  = Str::slug($name);
        $slug  = $base;
        $count = 1;

        while (
            Product::withTrashed()
                ->where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }
}
