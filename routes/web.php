<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\PlotcoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SysrefController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/{userId}', [ProfileController::class, 'view'])->name('profile.view');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::group(['middleware' => 'can:create character'], function () {
        Route::get('/characters', [CharacterController::class, 'index'])->name('characters.index');
        Route::get('/characters/view/{characterId}', [CharacterController::class, 'view'])->name('characters.view');
        Route::get('/characters/print/{characterId}', [CharacterController::class, 'print'])->name('characters.print');
        Route::get('/characters/edit/{characterId}', [CharacterController::class, 'edit'])->name('characters.edit');
        Route::get('/characters/delete/{characterId}', [CharacterController::class, 'delete'])->name('characters.delete');
        Route::post('/characters/approve/{characterId}', [CharacterController::class, 'approve'])->name('characters.approve');
        Route::post('/characters/deny/{characterId}', [CharacterController::class, 'deny'])->name('characters.deny');
        Route::get('/characters/ready/{characterId}', [CharacterController::class, 'ready'])->name('characters.ready');
        Route::get('/characters/kill/{characterId}', [CharacterController::class, 'kill'])->name('characters.kill');
        Route::get('/characters/retire/{characterId}', [CharacterController::class, 'retire'])->name('characters.retire');
        Route::get('/characters/edit/{characterId}/skills', [CharacterController::class, 'editSkills'])->name('characters.edit-skills');
        Route::get('/characters/edit/{characterId}/skills/{skillId}', [CharacterController::class, 'editSkills'])->name('characters.edit-skill');
        Route::get('/characters/create', [CharacterController::class, 'create'])->name('characters.create');
        Route::get('/characters/edit/{characterId}/remove-skill/{skillId}', [CharacterController::class, 'removeSkill'])->name('characters.remove-skill');
        Route::post('/characters/store', [CharacterController::class, 'store'])->name('characters.store');
        Route::post('/characters/store-skill', [CharacterController::class, 'storeSkill'])->name('characters.store-skill');
    });

    Route::group(['middleware' => 'can:edit all characters'], function () {
        Route::get('/plot-co/characters/', [PlotcoController::class, 'characters'])->name('plotco.characters');
        Route::get('/plot-co/characters/print-all', [PlotcoController::class, 'printAll'])->name('plotco.print-all');
        Route::get('/plot-co/characters/print-some', [PlotcoController::class, 'printSome'])->name('plotco.print-some');
        Route::get('/plot-co/skills/', [PlotcoController::class, 'skills'])->name('plotco.skills');
        Route::get('/plot-co/attendance/', [PlotcoController::class, 'attendance'])->name('plotco.attendance');
    });

    Route::group(['middleware' => 'can:edit skill'], function () {
        Route::get('/sys-ref/skill-check/', [SysrefController::class, 'skillCheck'])->name('sysref.skill-check');
    });

    Route::group(['middleware' => 'can:modify roles'], function () {
        Route::get('/admin/manage-roles/', [AdminController::class, 'manageRoles'])->name('admin.manage-roles');
        Route::post('/admin/store-roles/', [AdminController::class, 'storeRoles'])->name('admin.store-roles');
    });
});

require __DIR__ . '/auth.php';
