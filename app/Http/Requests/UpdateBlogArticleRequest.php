<?php

namespace App\Http\Requests;

use App\Models\BlogArticle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAdminAccess();
    }

    public function rules(): array
    {
        $articleId =
            $this->route('blog_article') instanceof BlogArticle
                ? $this->route('blog_article')->id
                : (int) ($this->route('blog_article') ?? $this->route('blogArticle'));

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('blog_articles', 'slug')->ignore($articleId),
            ],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cover_image_url' => ['nullable', 'url', 'max:1000'],
            'status' => ['required', Rule::in([BlogArticle::STATUS_DRAFT, BlogArticle::STATUS_PUBLISHED])],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
