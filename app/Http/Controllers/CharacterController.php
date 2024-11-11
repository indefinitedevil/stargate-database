<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterSkill;
use App\Models\Status;
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
        $character = Character::find($characterId);
        if (in_array($character->status_id, [Status::DEAD, Status::RETIRED])) {
            return redirect(route('characters.view', ['characterId' => $character->id]));
        }
        return view('characters.edit', ['character' => $character]);
    }

    public function editSkills($characterId, $skillId = null) {
        $character = Character::find($characterId);
        if (in_array($character->status_id, [Status::DEAD, Status::RETIRED])) {
            return redirect(route('characters.view', ['characterId' => $character->id]));
        }
        return view('characters.edit-skills', [
            'character' => $character,
            'editSkill' => $skillId ? CharacterSkill::find($skillId) : null,
        ]);
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
            if (in_array($character->status_id, [Status::DEAD, Status::RETIRED])) {
                return redirect(route('characters.view', ['characterId' => $character->id]));
            }
        } else {
            $character = new Character();
        }
        $character->fill($validatedData);
        $character->save();
        return redirect(route('characters.view', ['characterId' => $character->id]));
    }

    public function storeSkill(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'integer|exists:character_skills,id',
            'character_id' => 'integer|exists:characters,id',
            'skill_id' => 'required|exists:skills,id',
        ]);

        if (!empty($validatedData['id'])) {
            $characterSkill = CharacterSkill::find($validatedData['id']);
            if (in_array($characterSkill->character->status_id, [Status::DEAD, Status::RETIRED])) {
                return redirect(route('characters.view', ['characterId' => $characterSkill->character->id]));
            }
        } else {
            $characterSkill = new CharacterSkill();
        }
        $characterSkill->fill($validatedData);
        $characterSkill->save();
        return redirect(route('characters.edit-skills', ['characterId' => $characterSkill->character->id]));
    }
}
