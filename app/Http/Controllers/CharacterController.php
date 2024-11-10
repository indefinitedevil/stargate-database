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

    public function edit($characterId) {
        return view('characters.edit', ['character' => Character::find($characterId)]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'integer|exists:characters,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'rank' => 'string|max:64',
            'former_rank' => 'string|max:64',
            'background_id' => 'required|exists:backgrounds,id',
            'status_id' => 'required|exists:statuses,id',
            'history' => 'string',
            'plot_notes' => 'string',
        ]);

        if (!empty($validatedData['id'])) {
            $character = Character::find($validatedData['id']);
        } else {
            $character = new Character();
        }
        $character->fill($validatedData);
        $character->save();
        return redirect(route('characters.view', ['characterId' => $character->id]));
    }
}
