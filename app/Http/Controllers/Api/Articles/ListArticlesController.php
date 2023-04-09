<?php

namespace App\Http\Controllers\Api\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ListArticlesController extends Controller
{
    public function __invoke(Request $request)
    {
        return ArticleResource::collection(Article::all());
    }
}
