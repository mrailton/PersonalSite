<?php

declare(strict_types=1);

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;

class ShowArticleController extends Controller
{
    public function __invoke(Request $request, Article $article): View
    {
        if (is_null($article->published_at) || $article->published_at > now()) {
            abort(404);
        }

        $html = (new CommonMarkConverter())->convert($article->content);

        return view('articles.show', ['article' => $article, 'html' => $html]);
    }
}
