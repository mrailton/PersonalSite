<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Articles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Articles\UpsertArticleRequest;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class UpdateArticleController extends Controller
{
    public function __invoke(UpsertArticleRequest $request, Article $article): RedirectResponse
    {
        $article->title = strval($request->get('title'));
        $article->published_at = $request->get('published_at') ? Carbon::createFromTimeString(strval($request->get('published_at'))) : null;
        $article->content = strval($request->get('content'));
        $article->save();

        return redirect()->route('admin.articles.list');
    }
}
