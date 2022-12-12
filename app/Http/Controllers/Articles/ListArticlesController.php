<?php

declare(strict_types=1);

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Contracts\View\View;

class ListArticlesController extends Controller
{
    public function __invoke(): View
    {
        $articles = Article::query()->whereNotNull('published_at')->orderByDesc('published_at')->paginate(10);

        return view('articles.list', ['articles' => $articles]);
    }
}
