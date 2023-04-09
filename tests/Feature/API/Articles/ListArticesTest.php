<?php

use App\Models\Article;
use Symfony\Component\HttpFoundation\Response;

test('non-authenticated request will not return articles', function (): void {
    guest()->getJson(route('articles:list'))
        ->assertStatus(Response::HTTP_UNAUTHORIZED);
});

test('an authenticated user can get a list of all articles', function () {
    $articles = Article::factory()->count(10)->create();

    authenticatedUser()->getJson(route('articles:list'))
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(10, 'data')
        ->assertJsonPath('data.0.title', $articles[0]->title);
});
