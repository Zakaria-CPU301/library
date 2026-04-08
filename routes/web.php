<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $auth = Auth::check() ? route('dashboard.index') : route('login');
    return redirect($auth);
})->name('index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::livewire('/', 'pages::admin.dashboard')->name('index');
    });

    Route::prefix('books')->name('books.')->group(function () {
        Route::middleware('role:admin')->group(function () {
            Route::livewire('/manage', 'pages::admin.books')->name('admin');
            Route::livewire('create', 'pages::admin.books.create')->name('create');
        });
        Route::middleware('role:user')->group(function () {
            Route::livewire('/', 'pages::users.books')->name('user');
            Route::livewire('view/{idBook}', 'pages::users.books.view-more')->name('view');
        });
    });

    Route::prefix('borrowing')->name('borrowing.')->group(function () {
        Route::middleware('role:admin')->group(function () {
            Route::livewire('manage', 'pages::admin.books.peminjaman')->name('admin');
        });
        Route::middleware('role:user')->group(function () {
            Route::livewire('/', 'pages::users.books.peminjaman')->name('user');
        });
    });

    Route::prefix('users')->name('users.')->middleware('role:admin')->group(function () {
        Route::livewire('/', 'pages::admin.users')->name('index');
        Route::prefix('register')->name('create.')->group(function () {
            Route::livewire('single', 'pages::admin.users.register')->name('single');
            Route::livewire('import', 'pages::admin.users.register')->name('import');
        });
    });
});

require __DIR__ . '/auth.php';
