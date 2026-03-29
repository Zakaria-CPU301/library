<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $auth = Auth::check() ? route('dashboard') : route('login');
    return redirect($auth);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin')->group(function () {});

    Route::prefix('books')->name('books.')->group(function () {
        Route::livewire('/', 'pages::users.books')->name('index');
        Route::livewire('view-book{idBook}', 'pages::users.books.view-more')->name('view-more');
        Route::livewire('create', 'pages::admin.books.create')->name('create');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::livewire('/', 'pages::admin.users')->name('index');

        Route::prefix('register')->name('create.')->group(function () {
            Route::livewire('single', 'pages::admin.users.register')->name('single');
            Route::livewire('import', 'pages::admin.users.register')->name('import');
        });
    });
});

require __DIR__ . '/auth.php';
