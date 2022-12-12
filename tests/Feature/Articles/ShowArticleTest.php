<?php

declare(strict_types=1);

namespace Tests\Feature\Articles;

use App\Models\Article;
use Tests\TestCase;

class ShowArticleTest extends TestCase
{
    /** @test */
    public function it_displays_a_published_article(): void
    {
        $article = Article::factory()->create();

        $res = $this->get(route('articles.show', ['article' => $article]));

        $res->assertSee($article->title)
            ->assertSee($article->published_at->format('jS F Y'));
    }

    /** @test */
    public function it_doesnt_display_an_unpublished_article_to_a_guest(): void
    {
        $unpublishedArticle = Article::factory()->unpublished()->create();

        $res = $this->get(route('articles.show', ['article' => $unpublishedArticle]));

        $res->assertStatus(404)
            ->assertDontSee($unpublishedArticle->title);
    }
}
