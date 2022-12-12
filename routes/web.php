<?php

use App\Http\Controllers\Articles\ListArticlesController;
use App\Http\Controllers\Articles\ShowArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', App\Http\Controllers\IndexController::class)->name('index');

Route::get('/blog', ListArticlesController::class)->name('articles.list');
Route::get('/blog/{article:slug}', ShowArticleController::class)->name('articles.show');
