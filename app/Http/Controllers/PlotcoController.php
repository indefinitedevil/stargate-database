<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Skill;
use App\Models\Status;
use Illuminate\Http\Request;

class PlotcoController extends Controller
{
    public function characters(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'));
        }
        return view('plotco.characters', [
            'newCharacters' => Character::all()->where('status_id', Status::READY)->sortBy('name'),
            'activeCharacters' => Character::all()->whereIn('status_id', [Status::APPROVED, Status::PLAYED])->sortBy('name'),
            'inactiveCharacters' => Character::all()->whereIn('status_id', [Status::DEAD, Status::RETIRED])->sortBy('name'),
        ]);
    }

    public function skills(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'));
        }
        return view('plotco.skills', [
            'skills' => Skill::all()->sortBy(['skill_category_id', 'name']),
        ]);
    }
}
