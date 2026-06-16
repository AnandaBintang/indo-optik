<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogArticleRequest;
use App\Http\Requests\UpdateBlogArticleRequest;
use App\Models\BlogArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogArticleController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $articles = BlogArticle::query()
            ->when($search, fn ($q) => $q->where(function ($q2) use ($search) {
                $q2->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            }))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest('published_at')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.blog-articles.index', compact('articles', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.blog-articles.create');
    }

    public function store(StoreBlogArticleRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? $validated['title']);
        $validated['cover_image'] = $this->resolveCoverImage($request);

        BlogArticle::create($validated);

        return redirect()
            ->route('admin.blog-articles.index')
            ->with('success', 'Artikel blog berhasil ditambahkan.');
    }

    public function show(BlogArticle $blogArticle): RedirectResponse
    {
        return redirect()->route('admin.blog-articles.edit', $blogArticle);
    }

    public function edit(BlogArticle $blogArticle): View
    {
        return view('admin.blog-articles.edit', compact('blogArticle'));
    }

    public function update(UpdateBlogArticleRequest $request, BlogArticle $blogArticle): RedirectResponse
    {
        $validated = $request->validated();
        $slugSource = $validated['slug'] ?? $validated['title'];
        $validated['slug'] = $this->uniqueSlug($slugSource, $blogArticle->id);

        $coverImage = $this->resolveCoverImage($request);
        if ($coverImage !== null) {
            if ($blogArticle->cover_image && Storage::disk('public')->exists($blogArticle->cover_image)) {
                Storage::disk('public')->delete($blogArticle->cover_image);
            }
            $validated['cover_image'] = $coverImage;
        }

        $blogArticle->update($validated);

        return redirect()
            ->route('admin.blog-articles.index')
            ->with('success', 'Artikel blog berhasil diperbarui.');
    }

    public function destroy(BlogArticle $blogArticle): RedirectResponse
    {
        if ($blogArticle->cover_image && Storage::disk('public')->exists($blogArticle->cover_image)) {
            Storage::disk('public')->delete($blogArticle->cover_image);
        }

        $blogArticle->delete();

        return redirect()
            ->route('admin.blog-articles.index')
            ->with('success', 'Artikel blog berhasil dihapus.');
    }

    private function resolveCoverImage(Request $request): ?string
    {
        if ($request->filled('cover_image_url')) {
            return $request->input('cover_image_url');
        }

        if ($request->hasFile('cover_image')) {
            return $request->file('cover_image')->store('blog', 'public');
        }

        return null;
    }

    private function uniqueSlug(string $value, ?int $excludeId = null): string
    {
        $base = Str::slug($value);
        $slug = $base !== '' ? $base : 'artikel';
        $count = 1;

        while (
            BlogArticle::query()
                ->where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = ($base !== '' ? $base : 'artikel') . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
