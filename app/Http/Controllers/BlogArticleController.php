<?php

namespace App\Http\Controllers;

use App\Models\BlogArticle;
use Illuminate\View\View;

class BlogArticleController extends Controller
{
    public function index(): View
    {
        $articles = BlogArticle::published()
            ->latest('published_at')
            ->paginate(9);

        return view('pages.blog.index', compact('articles'));
    }

    public function show(string $slug): View
    {
        $article = BlogArticle::published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.blog.show', compact('article'));
    }
}
