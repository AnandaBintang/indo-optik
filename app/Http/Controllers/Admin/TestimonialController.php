<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestimonialRequest;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    /**
     * Display a paginated listing of testimonials.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $rating = $request->get('rating');

        $testimonials = Testimonial::with('user')
            ->when(
                $search,
                fn ($q) => $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('message', 'like', "%{$search}%")
                       ->orWhere('role', 'like', "%{$search}%");
                })
            )
            ->when(
                $status !== null && $status !== '',
                fn ($q) => $q->where('status', $status)
            )
            ->when(
                $rating !== null && $rating !== '',
                fn ($q) => $q->where('rating', (int) $rating)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.testimonials.index', compact('testimonials', 'search', 'status', 'rating'));
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created testimonial in storage.
     */
    public function store(StoreTestimonialRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('testimonials', 'public');
        }

        // Admin-created testimonials are considered verified by default
        $validated['is_verified'] = true;

        Testimonial::create($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', "Testimoni dari \"{$validated['name']}\" berhasil ditambahkan.");
    }

    /**
     * Redirect show to edit — admin has no separate show view.
     */
    public function show(int $id): RedirectResponse
    {
        return redirect()->route('admin.testimonials.edit', $id);
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit(int $id): View
    {
        $testimonial = Testimonial::with('user')->findOrFail($id);

        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified testimonial in storage.
     */
    public function update(StoreTestimonialRequest $request, int $id): RedirectResponse
    {
        $testimonial = Testimonial::findOrFail($id);
        $validated   = $request->validated();

        // Handle photo upload — delete the old one if a new file is provided
        if ($request->hasFile('photo')) {
            if ($testimonial->photo && Storage::disk('public')->exists($testimonial->photo)) {
                Storage::disk('public')->delete($testimonial->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('testimonials', 'public');
        }

        $testimonial->update($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', "Testimoni dari \"{$testimonial->name}\" berhasil diperbarui.");
    }

    /**
     * Remove the specified testimonial from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $testimonial = Testimonial::findOrFail($id);

        // Clean up the photo from disk
        if ($testimonial->photo && Storage::disk('public')->exists($testimonial->photo)) {
            Storage::disk('public')->delete($testimonial->photo);
        }

        $name = $testimonial->name;
        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', "Testimoni dari \"{$name}\" berhasil dihapus.");
    }

    /**
     * Toggle the published/unpublished status of a testimonial.
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $testimonial = Testimonial::findOrFail($id);

        $newStatus = $testimonial->status === 'published' ? 'unpublished' : 'published';

        $testimonial->update(['status' => $newStatus]);

        $label = $newStatus === 'published' ? 'dipublikasikan' : 'disembunyikan';

        return back()->with('success', "Testimoni dari \"{$testimonial->name}\" berhasil {$label}.");
    }
}
