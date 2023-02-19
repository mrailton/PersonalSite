<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ArticlesController as AdminArticlesController;
use App\Http\Controllers\Admin\CertificatesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', App\Http\Controllers\IndexController::class)->name('index');

Route::get('/blog', [ArticlesController::class, 'list'])->name('articles.list');
Route::get('/blog/{article:slug}', [ArticlesController::class, 'show'])->name('articles.show');

Route::prefix('/login')->name('login.')->middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('create');
    Route::post('/', [AuthController::class, 'authenticate'])->name('store');
});

Route::middleware('auth:web')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::prefix('/articles')->name('articles.')->group(function () {
            Route::post('/', [AdminArticlesController::class, 'store'])->name('store');
            Route::get('/', [AdminArticlesController::class, 'list'])->name('list');
            Route::get('/create', [AdminArticlesController::class, 'create'])->name('create');
            Route::put('/{article:slug}', [AdminArticlesController::class, 'update'])->name('update');
            Route::get('/{article:slug}/edit', [AdminArticlesController::class, 'edit'])->name('edit');
        });

        Route::prefix('/certificates')->name('certificates.')->group(function () {
            Route::get('/', [CertificatesController::class, 'list'])->name('list');
            Route::get('/create', [CertificatesController::class, 'create'])->name('create');
            Route::post('/', [CertificatesController::class, 'store'])->name('store');
            Route::get('/{certificate:id}', [CertificatesController::class, 'show'])->name('show');
            Route::get('/{certificate:id}/download', [CertificatesController::class, 'downloadFile'])->name('download');
            Route::get('/{certificate:id}/view-certificate', [CertificatesController::class, 'viewFile'])->name('view-certificate');
            Route::get('/{certificate:id}/edit', [CertificatesController::class, 'edit'])->name('edit');
            Route::put('/{certificate:id}', [CertificatesController::class, 'update'])->name('update');
            Route::delete('/{certificate:id}', [CertificatesController::class, 'delete'])->name('delete');
        });
    });
});
