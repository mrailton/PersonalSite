<?php

use App\Models\Article;

test('a visitor can view a list of published articles', function () {
    guest();

    $publishedArticles = Article::factory()->count(4)->create();
    $unpublishedArticles = Article::factory()->unpublished()->count(2)->create();

    $res = $this->get(route('articles.list'));

    $res->assertSee('Blog Articles')
        ->assertSee($publishedArticles[0]->title)
        ->assertSee($publishedArticles[2]->title)
        ->assertDontSee($unpublishedArticles[0]->title)
        ->assertDontSee($unpublishedArticles[1]->title)
        ->assertDontSee('Newer')
        ->assertDontSee('Older');
});

test('pagination options show when there are more than 10 published articles', function () {
    guest();

    Article::factory()->count(12)->create();

    $res = $this->get(route('articles.list'));

    $res->assertSee('Blog Articles')
        ->assertSee('Newer')
        ->assertSee('Older');
});
