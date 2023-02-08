<?php

declare(strict_types=1);

use App\Models\Article;

test('index page renders', function () {
    $publishedArticle = Article::factory()->create();
    $unpublishedArticle = Article::factory()->unpublished()->create();

    $res = $this->get('/');

    $res->assertSee('Mark Railton');
    $res->assertSee($publishedArticle->title);
    $res->assertDontSee($unpublishedArticle->title);
});
