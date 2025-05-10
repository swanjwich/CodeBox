<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SnippetsController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index');
});

Route::middleware('auth')->group( function() {
    Route::inertia('/new', 'NewSnippet')->name('new');
    Route::post('/store', [SnippetsController::class, 'store'])->name('store');
    Route::get('/all', [SnippetsController::class, 'index'])->name('all');
    Route::get('/snippets/{id}', [SnippetsController::class, 'show'])->name('snippets.show');
    Route::get('/favorites', [SnippetsController::class, 'favorites'])->name('favorites');
    Route::patch('/snippets/{id}/favorite', [SnippetsController::class, 'toggleFavorite'])->name('snippets.favorite');
    Route::delete('/snippets/{id}/trash', [SnippetsController::class, 'delete'])->name('snippets.delete');
    Route::get('/trash', [SnippetsController::class, 'trash'])->name('trash');
    Route::patch('/snippets/{id}/restore', [SnippetsController::class, 'restore'])->name('snippets.restore');
    Route::delete('/snippets/{id}/destroy', [SnippetsController::class, 'destroy'])->name('snippets.destroy');
    Route::patch('/snippets/{snippet}', [SnippetsController::class, 'update'])->name('snippets.update');
    Route::post('/snippets/{id}/add-to-category',[SnippetsController::class, 'addToCategory'])->name('snippets.add-to-category');

    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/category', action: [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/{category}/snippets', [CategoryController::class, 'show'])->name('category.snippets');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::middleware('guest')->group( function() {
    Route::inertia('/register', 'Auth/Register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::inertia('/login', 'Auth/Login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


