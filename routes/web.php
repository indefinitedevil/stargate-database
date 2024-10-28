<?php

use App\Models\Character;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return view('welcome');
    return view('characters.view', ['character' => Character::find(2)]);
});
