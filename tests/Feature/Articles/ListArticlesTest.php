<?php

declare(strict_types=1);

namespace Tests\Feature\Articles;

use App\Models\Article;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    /** @test */
    public function it_displays_a_list_of_published_articles(): void
    {
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
    }

    /** @test */
    public function it_displays_pagination_when_more_then_10_posts_are_published(): void
    {
        $publishedArticles = Article::factory()->count(12)->create();

        $res = $this->get(route('articles.list'));

        $res->assertSee('Blog Articles')
            ->assertSee('Newer')
            ->assertSee('Older');
    }
}
