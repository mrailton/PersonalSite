<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Articles;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListArticlesController extends Controller
{
    public function __invoke(Request $request): View
    {
        $articles = Article::query()->orderByDesc('id')->get();

        return view('admin.articles.list', ['articles' => $articles]);
    }
}
