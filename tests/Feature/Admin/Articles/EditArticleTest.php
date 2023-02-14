<?php

declare(strict_types=1);

use App\Models\Article;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

test('an authenticated user can view the edit article page', function () {
    authenticatedUser();
    $article = Article::factory()->create();

    get(route('admin.articles.edit', ['article' => $article]))
        ->assertStatus(200)
        ->assertSee($article->title)
        ->assertSee('Submit');
});

test('an authenticate user can update an article', function () {
    authenticatedUser();
    $article = Article::factory()->create();
    $data = $article->toArray();
    $data['title'] = 'Updated Title';

    put(route('admin.articles.update', ['article' => $article]), $data)
         ->assertRedirectToRoute('admin.articles.list')
         ->assertSessionDoesntHaveErrors();
});
