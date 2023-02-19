<?php

use App\Models\Article;

test('a visitor can view a published article', function () {
    guest();

    $article = Article::factory()->create();

    $res = $this->get(route('articles.show', ['article' => $article]));

    $res->assertSee($article->title)
        ->assertSee($article->published_at->format('jS F Y'));
});

test('a guest can not view an article that has not been published yet', function () {
    guest();

    $unpublishedArticle = Article::factory()->unpublished()->create();

    $res = $this->get(route('articles.show', ['article' => $unpublishedArticle]));

    $res->assertStatus(404)
        ->assertDontSee($unpublishedArticle->title);
});
