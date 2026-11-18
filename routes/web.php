<?php

use App\Http\Controllers\EloquentRelationshipController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

// Login & Register Routes (Public)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Root redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// PROTECTED ROUTES (Requirement 3: Handled securely in the controller)
Route::get('/eloquent-relationships', [EloquentRelationshipController::class, 'index'])->name('eloquent.index');

Route::prefix('eloquent-relationships')->name('eloquent.')->group(function () {
    Route::post('/one-to-one', [EloquentRelationshipController::class, 'storeOneToOne'])->name('one-to-one.store');
    Route::post('/one-to-many', [EloquentRelationshipController::class, 'storeOneToMany'])->name('one-to-many.store')
    ->middleware('throttle:5,1');
    Route::post('/many-to-many', [EloquentRelationshipController::class, 'storeManyToMany'])->name('many-to-many.store');
});