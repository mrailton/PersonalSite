<?php

use App\Models\Article;

test('an authenticated user can view a list of articles', function () {
    $articles = Article::factory()->count(10)->create();

    authenticatedUser()->get(route('admin.articles.list'))
        ->assertSee($articles[0]->title)
        ->assertSee($articles[3]->title)
        ->assertSee('Add Article');
});

test('an authenticated user can create a new article', function () {
    $article = Article::factory()->make()->toArray();

    expect(Article::count())->toBe(0);

    authenticatedUser()->get(route('admin.articles.create'))
        ->assertSee('Create New Article');

    authenticatedUser()->post(route('admin.articles.store'), $article)
        ->assertRedirect(route('admin.articles.list'));

    expect(Article::count())->toBe(1)
        ->and(Article::first()->slug)->toEqual(Str::slug($article['title']));
});

test('an authenticate user can update an article', function () {
    $article = Article::factory()->create();
    $data = $article->toArray();
    $data['title'] = 'Updated Title';

    authenticatedUser()->get(route('admin.articles.edit', ['article' => $article]))
        ->assertStatus(200)
        ->assertSee($article->title)
        ->assertSee('Submit');

    authenticatedUser()->put(route('admin.articles.update', ['article' => $article]), $data)
        ->assertRedirectToRoute('admin.articles.list')
        ->assertSessionDoesntHaveErrors();
});
