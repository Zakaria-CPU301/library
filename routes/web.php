<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::livewire('/dashboard', 'pages::admin.dashboard')->name('dashboard');

    Route::prefix('tools')->name('tools.')->group(function () {
        Route::middleware('role:admin')->group(function () {
            Route::livewire('manage', 'pages::admin.tools')->name('admin');
            Route::livewire('create', 'pages::admin.tools.create')->name('create');
            Route::livewire('edit/{toolId}', 'pages::admin.tools.edit')->name('edit');
        });
        Route::middleware('role:user')->group(function () {
            Route::livewire('/', 'pages::user.tools')->name('user');
            Route::livewire('view/{idTool}', 'pages::user.tools.view-more')->name('view');
        });
    });
    // user : domain/borrowing, domain/borrowing/cart
    // admin : domain/borrowing
    Route::prefix('borrowing')->name('borrowing.')->group(function () {
        Route::middleware('role:admin')->name('admin.')->group(function () {
            Route::livewire('/', 'pages::admin.borrowing.manage')->name('index');
        });
        Route::middleware('role:user')->name('user.')->group(function () {
            Route::livewire('history', 'pages::user.borrowing.history')->name('index');
            Route::livewire('cart', 'pages::user.borrowing.request')->name('request');
        });
    });

    Route::prefix('accounts')->name('account.')->middleware('role:admin')->group(function () {
        Route::livewire('/', 'pages::admin.accounts')->name('index');
        Route::prefix('register')->name('create.')->group(function () {
            Route::livewire('single', 'pages::admin.accounts.register')->name('single');
            Route::livewire('import', 'pages::admin.accounts.register')->name('import');
        });
    });

    Route::livewire('demo', 'pages::demo');
});

require __DIR__ . '/auth.php';
