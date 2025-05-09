<?php

declare(strict_types=1);

namespace App\Http\Controllers\Articles;

use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Spatie\LaravelMarkdown\MarkdownRenderer;

class ShowArticleController
{
    public function __invoke(Request $request, string $slug): View
    {
        try {
            $article = Article::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            abort(404);
        }

        if ((null === $article->published_at || $article->published_at > now()) && ! $request->user()) {
            abort(404);
        }

        return view('articles.show', ['article' => $article, 'html' => $this->convertToHtml($article->content)]);
    }

    private function convertToHtml(string $markdown)
    {
        return app(MarkdownRenderer::class)
            ->highlightTheme('dracula')
            ->toHtml($markdown);
    }
}
