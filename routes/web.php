<?php

declare(strict_types=1);

use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');

Route::get('/blog', [ArticlesController::class, 'list'])->name('articles.list');
Route::get('/blog/{article:slug}', [ArticlesController::class, 'show'])->name('articles.show');
