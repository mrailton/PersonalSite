<?php

use App\Models\Article;

test('a visitor can view a list of published articles', function () {
    $publishedArticles = Article::factory()->count(4)->create();
    $unpublishedArticles = Article::factory()->unpublished()->count(2)->create();

    guest()->get(route('articles.list'))
        ->assertSee('Blog Articles')
        ->assertSee($publishedArticles[0]->title)
        ->assertSee($publishedArticles[2]->title)
        ->assertDontSee($unpublishedArticles[0]->title)
        ->assertDontSee($unpublishedArticles[1]->title)
        ->assertDontSee('Newer')
        ->assertDontSee('Older');
});

test('pagination options show when there are more than 10 published articles', function () {
    Article::factory()->count(12)->create();

    guest()->get(route('articles.list'))
        ->assertSee('Blog Articles')
        ->assertSee('Newer')
        ->assertSee('Older');
});

test('a visitor can view a published article', function () {
    $article = Article::factory()->create();

    guest()->get(route('articles.show', ['article' => $article]))
        ->assertSee($article->title)
        ->assertSee($article->published_at->format('jS F Y'));
});

test('a guest can not view an article that has not been published yet', function () {
    $unpublishedArticle = Article::factory()->unpublished()->create();

    guest()->get(route('articles.show', ['article' => $unpublishedArticle]))
        ->assertStatus(404)
        ->assertDontSee($unpublishedArticle->title);
});
