<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', action: LoginController::class);
