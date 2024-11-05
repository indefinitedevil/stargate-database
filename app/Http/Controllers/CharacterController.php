<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function index()
    {
        return view('characters.index', [
            'characters' => auth()->user()->characters
        ]);
    }

    public function create()
    {
        return view('characters.create');
    }

    public function view($characterId) {
        return view('characters.view', ['character' => Character::find($characterId)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'name' => 'required|string|max:255',
            'background_id' => 'required|exists:backgrounds,id',
        ]);
    }
}
