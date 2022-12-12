<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Article;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function index_page_loads(): void
    {
        $publishedArticle = Article::factory()->create();
        $unpublishedArticle = Article::factory()->unpublished()->create();

        $res = $this->get('/');

        $res->assertSee('Mark Railton');
        $res->assertSee($publishedArticle->title);
        $res->assertDontSee($unpublishedArticle->title);
    }
}
