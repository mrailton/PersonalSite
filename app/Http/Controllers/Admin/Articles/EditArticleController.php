<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Articles;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EditArticleController extends Controller
{
    public function __invoke(Request $request, Article $article): View
    {
        return view('admin.articles.edit', ['article' => $article]);
    }
}
