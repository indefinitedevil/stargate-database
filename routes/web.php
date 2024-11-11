<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/characters', [CharacterController::class, 'index'])->name('characters.index');
    Route::get('/characters/view/{characterId}', [CharacterController::class, 'view'])->name('characters.view');
    Route::get('/characters/edit/{characterId}', [CharacterController::class, 'edit'])->name('characters.edit');
    Route::get('/characters/edit/{characterId}/skills', [CharacterController::class, 'editSkills'])->name('characters.edit-skills');
    Route::get('/characters/edit/{characterId}/skills/{skillId}', [CharacterController::class, 'editSkills'])->name('characters.edit-skill');
    Route::get('/characters/create', [CharacterController::class, 'create'])->name('characters.create');
    Route::post('/characters/store', [CharacterController::class, 'store'])->name('characters.store');
    Route::post('/characters/store-skill', [CharacterController::class, 'storeSkill'])->name('characters.store-skill');
});

require __DIR__.'/auth.php';
