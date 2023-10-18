<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ArticlesController as AdminArticlesController;
use App\Http\Controllers\Admin\CertificatesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotesController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', App\Http\Controllers\IndexController::class)->name('index');

Route::get('/blog', [ArticlesController::class, 'list'])->name('articles.list');
Route::get('/blog/{article:slug}', [ArticlesController::class, 'show'])->name('articles.show');
