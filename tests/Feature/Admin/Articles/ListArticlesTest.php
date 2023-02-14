<?php

declare(strict_types=1);

use App\Models\Article;

test('an authenticated user can view a list of articles', function () {
    authenticatedUser();
    $articles = Article::factory()->count(10)->create();

    $res = $this->get(route('admin.articles.list'));

    $res->assertSee($articles[0]->title);
    $res->assertSee($articles[3]->title);
    $res->assertSee('Add Article');
});
