<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Articles\UpsertArticleRequest;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ArticlesController extends Controller
{
    public function list(Request $request): View
    {
        $articles = Article::query()->orderByDesc('id')->get();

        return view('admin.articles.list', ['articles' => $articles]);
    }

    public function create(Request $request): View
    {
        return view('admin.articles.create');
    }

    public function store(UpsertArticleRequest $request): RedirectResponse
    {
        Article::create([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'published_at' => $request->get('published_at'),
        ]);

        return redirect()->route('admin.articles.list');
    }

    public function edit(Request $request, Article $article): View
    {
        return view('admin.articles.edit', ['article' => $article]);
    }

    public function update(UpsertArticleRequest $request, Article $article): RedirectResponse
    {
        $article->title = strval($request->get('title'));
        $article->published_at = $request->get('published_at') ? Carbon::createFromTimeString(strval($request->get('published_at'))) : null;
        $article->content = strval($request->get('content'));
        $article->save();

        return redirect()->route('admin.articles.list');
    }
}
