<?php

use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\TransactionController;
use App\Http\Controllers\Home\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::
        namespace('Home')->name('transaction-')->prefix('transaction')->middleware(['auth:sanctum', 'verified', RoleMiddleware::class . ':admin'])->group(function () {
            Route::get('/', [TransactionController::class, 'getTrasaction'])->name('list');
            Route::get('/create', [TransactionController::class, 'addTransaction'])->name('create');
            Route::get('/add', [TransactionController::class, 'createTransaction'])->name('add');
            Route::get('/filter', [TransactionController::class, 'index'])->name('filter');
            Route::get('/search', [TransactionController::class, 'index'])->name('search');
            Route::put('/update/{id}/status', [TransactionController::class, 'updateStatus']);
            Route::get('/user/{userId}/amount', [TransactionController::class, 'getUserAmount']);


        });

Route::
        namespace('Home')->name('users-')->prefix('users')->middleware(['auth:sanctum', 'verified', RoleMiddleware::class . ':admin'])->group(function () {
            Route::get('/list', [UserController::class, 'index'])->name('list');
            Route::get('/filter', [UserController::class, 'filterUsers'])->name('filter');
            Route::put('/update/{id}/status', [UserController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/add', [UserController::class, 'store'])->name('store');
            Route::delete('/{id}', [UserController::class, 'destroy']);
            Route::get('/create', function () {
                return Inertia::render('Users/AddUser');
            })->name('create');
        });
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth:sanctum', 'verified', RoleMiddleware::class . ':admin'])->name('dashboard');

// Route::get('/transaction-list', function () {
//     return Inertia::render('Transaction');
// })->middleware(['auth', 'verified'])->name('transaction-list');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
