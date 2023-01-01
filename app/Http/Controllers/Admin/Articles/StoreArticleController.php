<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Articles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Articles\StoreArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;

class StoreArticleController extends Controller
{
    public function __invoke(StoreArticleRequest $request): RedirectResponse
    {
        Article::create([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'published_at' => $request->get('published_at'),
        ]);

        return redirect()->route('admin.articles.list');
    }
}
