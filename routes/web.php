<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionPlanController;
use App\Http\Controllers\ProductionOrderController;

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

	Route::get('/ppic/products', [ProductController::class, 'index'])->name('ppic.products');
	Route::post('/ppic/products', [ProductController::class, 'store'])->name('ppic.products.store');
	Route::delete('/ppic/products/{product}', [ProductController::class, 'destroy'])->name('ppic.products.destroy');

	Route::get('/ppic/planning', [ProductionPlanController::class, 'index'])->name('ppic.planning');
	Route::post('/ppic/planning', [ProductionPlanController::class, 'store'])->name('ppic.planning.store');
	Route::post('/ppic/planning/approve/{planning}', [ProductionPlanController::class, 'approve'])->name('ppic.planning.approve');
	Route::post('/ppic/planning/reject/{planning}', [ProductionPlanController::class, 'reject'])->name('ppic.planning.reject');
});

// Production Routes - Only accessible by users with department 'production'
Route::middleware('production')->group(function () {
	Route::get('/production', function () {
		return Inertia::render('Production/Dashboard');
	})->name('production.dashboard');

	Route::get('/production/orders', [ProductionOrderController::class, 'index'])->name('production.orders');
	Route::post('/production/orders/start-production/{productionOrder}', [ProductionOrderController::class, 'startProduction'])->name('production.orders.start');
	Route::post('/production/orders/complete-production/{productionOrder}', [ProductionOrderController::class, 'completeProduction'])->name('production.orders.complete');
});

// Shared authenticated routes (accessible by all authenticated users)
Route::middleware('auth')->group(function () {
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
