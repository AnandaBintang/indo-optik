<?php

use App\Models\BlogArticle;
use App\Models\User;

function makeBlogAdmin(): User
{
    return User::factory()->create([
        "role" => User::ROLE_ADMIN,
    ]);
}

test("admin can create a blog article", function () {
    $admin = makeBlogAdmin();

    $response = $this->actingAs($admin)->post(route("admin.blog-articles.store"), [
        "title" => "Cara Memilih Frame Kacamata",
        "excerpt" => "Panduan singkat memilih frame.",
        "content" => "Konten artikel lengkap tentang frame.",
        "status" => "published",
        "published_at" => now()->format("Y-m-d\TH:i"),
        "meta_title" => "Cara Memilih Frame",
        "meta_description" => "Panduan memilih frame kacamata.",
    ]);

    $response->assertRedirect(route("admin.blog-articles.index"));

    $article = BlogArticle::query()->where("title", "Cara Memilih Frame Kacamata")->firstOrFail();

    expect($article->slug)->toBe("cara-memilih-frame-kacamata")
        ->and($article->status)->toBe("published");
});

test("public blog only shows published articles", function () {
    BlogArticle::factory()->create([
        "title" => "Artikel Tayang",
        "slug" => "artikel-tayang",
        "status" => "published",
        "published_at" => now()->subDay(),
    ]);

    BlogArticle::factory()->create([
        "title" => "Artikel Draft",
        "slug" => "artikel-draft",
        "status" => "draft",
        "published_at" => now()->subDay(),
    ]);

    $this->get(route("blog.index"))
        ->assertOk()
        ->assertSee("Artikel Tayang")
        ->assertDontSee("Artikel Draft");

    $this->get(route("blog.show", "artikel-tayang"))
        ->assertOk()
        ->assertSee("Artikel Tayang");

    $this->get(route("blog.show", "artikel-draft"))
        ->assertNotFound();
});

test("public blog shows an article published at the current Jakarta local time", function () {
    $localPublishedAt = now("Asia/Jakarta")->format("Y-m-d\TH:i");

    $admin = makeBlogAdmin();

    $this->actingAs($admin)->post(route("admin.blog-articles.store"), [
        "title" => "Artikel Waktu Lokal",
        "excerpt" => "Artikel langsung tayang.",
        "content" => "Konten artikel dengan waktu lokal.",
        "status" => "published",
        "published_at" => $localPublishedAt,
    ])->assertRedirect(route("admin.blog-articles.index"));

    $this->get(route("blog.index"))
        ->assertOk()
        ->assertSee("Artikel Waktu Lokal");
});
