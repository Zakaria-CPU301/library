<?php

use App\Http\Controllers\ManageBookController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $auth = Auth::check() ? route('dashboard') : route('login');
    return redirect($auth);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin')->group(function () {});
});
Route::prefix('books')->name('books.')->group(function () {
    Route::get('/add-book', [ManageBookController::class, 'create'])->name('create');
    Route::post('/store-book', [ManageBookController::class, 'store'])->name('store');
});

require __DIR__ . '/auth.php';
