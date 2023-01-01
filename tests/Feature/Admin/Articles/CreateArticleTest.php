<?php

namespace Tests\Feature\Admin\Articles;

use App\Models\Article;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_create_a_new_article(): void
    {
        $this->authenticate();
        $article = Article::factory()->make()->toArray();
        $this->assertDatabaseEmpty(Article::class);

        $res = $this->get(route('admin.articles.create'));
        $res->assertSee('Create New Article');

        $res = $this->post(route('admin.articles.store'), $article);
        $res->assertRedirect(route('admin.articles.list'));

        $this->assertDatabaseCount(Article::class, 1);
        $this->assertEquals(Str::slug($article['title']), Article::first()->slug);
    }
}
