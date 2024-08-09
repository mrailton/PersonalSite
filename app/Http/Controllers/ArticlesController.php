<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use League\CommonMark\CommonMarkConverter;

class ArticlesController extends Controller
{
    public function list(Request $request): View
    {
        $articles = Article::query()->whereNotNull('published_at')->orderByDesc('published_at')->paginate(10);

        return view('articles.list', ['articles' => $articles]);
    }

    public function show(Request $request, Article $article): View
    {
        if (is_null($article->published_at) || $article->published_at > now()) {
            abort(404);
        }

        $html = (new CommonMarkConverter())->convert($article->content);

        return view('articles.show', ['article' => $article, 'html' => $html]);
    }
}
