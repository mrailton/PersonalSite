<?php

use App\Models\Article;
use Illuminate\Support\Str;

test('an authenticated user can create a new article', function () {
    authenticatedUser();

    $article = Article::factory()->make()->toArray();
    $this->assertDatabaseEmpty(Article::class);

    $res = $this->get(route('admin.articles.create'));
    $res->assertSee('Create New Article');

    $res = $this->post(route('admin.articles.store'), $article);
    $res->assertRedirect(route('admin.articles.list'));

    $this->assertDatabaseCount(Article::class, 1);
    $this->assertEquals(Str::slug($article['title']), Article::first()->slug);
});
