<?php

namespace Tests\Feature\Admin\Articles;

use App\Models\Article;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    /** @test */
    public function an_authenticated_user_can_view_a_list_of_articles(): void
    {
        $articles = Article::factory()->count(10)->create();
        $this->authenticate();

        $res = $this->get(route('admin.articles.list'));

        $res->assertSee($articles[0]->title);
        $res->assertSee($articles[3]->title);
        $res->assertSee('Add Article');
    }
}
