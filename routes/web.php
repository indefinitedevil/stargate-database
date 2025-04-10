<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\DowntimeController;
use App\Http\Controllers\EventController;
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
Route::get('/changelog', function () {
    return view('changelog');
})->name('changelog');

Route::get('/events/', [EventController::class, 'index'])->name('events.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/{userId}', [ProfileController::class, 'view'])->name('profile.view');
    Route::get('/profile/{userId}/{userName}', [ProfileController::class, 'view'])->name('profile.view-pretty');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::group(['middleware' => 'can:create character'], function () {
        Route::get('/characters', [CharacterController::class, 'index'])->name('characters.index');
        Route::get('/characters/view/{characterId}', [CharacterController::class, 'view'])->name('characters.view');
        Route::get('/characters/view/{characterId}/{characterName}', [CharacterController::class, 'view'])->name('characters.view-pretty');
        Route::get('/characters/logs/{characterId}', [CharacterController::class, 'logs'])->name('characters.logs');
        Route::get('/characters/logs/{characterId}/{characterName}', [CharacterController::class, 'logs'])->name('characters.logs-pretty');
        Route::get('/characters/print/{characterId}', [CharacterController::class, 'print'])->name('characters.print');
        Route::get('/characters/print-skills/{characterId}', [CharacterController::class, 'printSkills'])->name('characters.print-skills');
        Route::get('/characters/edit/{characterId}', [CharacterController::class, 'edit'])->name('characters.edit');
        Route::get('/characters/delete/{characterId}', [CharacterController::class, 'delete'])->name('characters.delete');
        Route::get('/characters/ready/{characterId}', [CharacterController::class, 'ready'])->name('characters.ready');
        Route::get('/characters/reset/{characterId}', [CharacterController::class, 'reset'])->name('characters.reset');
        Route::get('/characters/primary/{characterId}', [CharacterController::class, 'primary'])->name('characters.primary');
        Route::get('/characters/kill/{characterId}', [CharacterController::class, 'kill'])->name('characters.kill');
        Route::get('/characters/retire/{characterId}', [CharacterController::class, 'retire'])->name('characters.retire');
        Route::get('/characters/edit/{characterId}/skills', [CharacterController::class, 'editSkills'])->name('characters.edit-skills');
        Route::get('/characters/edit/{characterId}/skills/{skillId}', [CharacterController::class, 'editSkills'])->name('characters.edit-skill');
        Route::get('/characters/create', [CharacterController::class, 'create'])->name('characters.create');
        Route::get('/characters/edit/{characterId}/remove-skill/{skillId}', [CharacterController::class, 'removeSkill'])->name('characters.remove-skill');
        Route::post('/characters/store', [CharacterController::class, 'store'])->name('characters.store');
        Route::post('/characters/store-skill', [CharacterController::class, 'storeSkill'])->name('characters.store-skill');

        Route::get('/downtimes', [DowntimeController::class, 'index'])->name('downtimes.index');
        Route::get('/downtimes/submit/{downtimeId}/character/{characterId}', [DowntimeController::class, 'submit'])->name('downtimes.submit');
        Route::get('/downtimes/view/{downtimeId}/character/{characterId}', [DowntimeController::class, 'viewSubmission'])->name('downtimes.view');
        Route::post('/downtimes/store-submission', [DowntimeController::class, 'storeSubmission'])->name('downtimes.store-submission');
    });

    Route::group(['middleware' => 'can:view skill breakdown'], function () {
        Route::get('/plot-co/skills/', [PlotcoController::class, 'skills'])->name('plotco.skills');
    });

    Route::group(['middleware' => 'can:view attendance'], function () {
        Route::get('/events/attendance/', [PlotcoController::class, 'attendance'])->name('events.all-attendance');
    });

    Route::group(['middleware' => 'can:record attendance'], function () {
        Route::get('/events/attendance/{eventId}', [EventController::class, 'attendance'])->name('events.attendance');
        Route::post('/events/store-attendance/', [EventController::class, 'storeAttendance'])->name('events.store-attendance');
    });

    Route::group(['middleware' => 'can:edit all characters'], function () {
        Route::get('/plot-co/characters/', [PlotcoController::class, 'characters'])->name('plotco.characters');
        Route::get('/plot-co/characters/print-all', [PlotcoController::class, 'printAll'])->name('plotco.print-all');
        Route::get('/plot-co/characters/print-some', [PlotcoController::class, 'printSome'])->name('plotco.print-some');
        Route::post('/characters/approve/{characterId}', [CharacterController::class, 'approve'])->name('characters.approve');
        Route::post('/characters/deny/{characterId}', [CharacterController::class, 'deny'])->name('characters.deny');
        Route::get('/characters/played/{characterId}', [CharacterController::class, 'played'])->name('characters.played');
        Route::get('/characters/inactive/{characterId}', [CharacterController::class, 'inactive'])->name('characters.inactive');
        Route::get('/characters/resuscitate/{characterId}', [CharacterController::class, 'resuscitate'])->name('characters.resuscitate');
    });

    Route::group(['middleware' => 'can:edit downtimes'], function () {
        Route::get('/plot-co/downtimes', [PlotcoController::class, 'downtimes'])->name('plotco.downtimes');
        Route::get('/plot-co/downtimes/create', [DowntimeController::class, 'create'])->name('plotco.downtimes.create');
        Route::get('/plot-co/downtimes/edit/{downtimeId}', [DowntimeController::class, 'edit'])->name('plotco.downtimes.edit');
        Route::get('/plot-co/downtimes/preprocess/{downtimeId}', [PlotcoController::class, 'preprocessDowntime'])->name('plotco.downtimes.preprocess');
        Route::get('/plot-co/downtimes/process/{downtimeId}', [PlotcoController::class, 'processDowntime'])->name('plotco.downtimes.process');
        Route::post('/plot-co/downtimes/store', [DowntimeController::class, 'store'])->name('plotco.downtimes.store');
    });

    Route::group(['middleware' => 'can:edit skill'], function () {
        Route::get('/sys-ref/skill-check/', [SysrefController::class, 'skillCheck'])->name('sysref.skill-check');
    });

    Route::group(['middleware' => 'can:modify roles'], function () {
        Route::get('/admin/manage-roles/', [AdminController::class, 'manageRoles'])->name('admin.manage-roles');
        Route::post('/admin/store-roles/', [AdminController::class, 'storeRoles'])->name('admin.store-roles');
    });

    Route::group(['middleware' => 'can:edit events'], function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::get('/events/edit/{eventId}', [EventController::class, 'edit'])->name('events.edit');
        Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
    });
});

require __DIR__ . '/auth.php';
