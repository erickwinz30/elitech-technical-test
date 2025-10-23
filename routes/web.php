<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Guest routes (not authenticated)
Route::middleware('guest')->group(function () {
	Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
	Route::post('/login', [AuthController::class, 'login']);
});

// PPIC Routes - Only accessible by users with department 'ppic'
Route::middleware('ppic')->group(function () {
	Route::get('/ppic', function () {
		return Inertia::render('PPIC/Dashboard');
	})->name('ppic.dashboard');
});

// Production Routes - Only accessible by users with department 'production'
Route::middleware('production')->group(function () {
	Route::get('/production', function () {
		return Inertia::render('Production/Dashboard');
	})->name('production.dashboard');
});

// Shared authenticated routes (accessible by all authenticated users)
Route::middleware('auth')->group(function () {
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
