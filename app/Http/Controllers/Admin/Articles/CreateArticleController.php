<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Articles;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CreateArticleController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('admin.articles.create');
    }
}
