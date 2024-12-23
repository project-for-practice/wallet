<?php

use App\Http\Controllers\Home\TransactionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::
        namespace('Home')->name('transaction-')->prefix('transaction')->middleware(['auth', 'verified'])->group(function () {
            Route::get('/', [TransactionController::class, 'getTrasaction'])->name('list');

            Route::get('/add', [TransactionController::class, 'createTransaction'])->name('add');
        });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/transaction-list', function () {
//     return Inertia::render('Transaction');
// })->middleware(['auth', 'verified'])->name('transaction-list');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
