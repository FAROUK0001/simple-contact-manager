<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');
Route::resource('contact', ContactController::class)
    ->middleware(['auth', 'verified']);
Route::patch('/contact/{contact}/inline', [ContactController::class, 'inlineUpdate'])
    ->name('contact.inline-update')
    ->middleware(['auth', 'verified']);
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
