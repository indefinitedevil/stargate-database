<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Division;
use App\Models\SkillCategory;
use App\Models\Status;

class OrganisationController extends Controller
{
    public function chart()
    {
        return view('organisation.chart', [
            'divisions' => Division::with(['characters', 'departments.characters'])->get(),
        ]);
    }

    public function skills()
    {
        $characters = Character::whereIn('status_id', [Status::APPROVED, Status::PLAYED])->orderBy('name')->get()->pluck('id');
        $skillCategories = SkillCategory::with('skills')
            ->where('id', '!=', SkillCategory::SYSTEM)
            ->get();
        return view('organisation.skills', [
            'validCharacters' => $characters,
            'skillCategories' => $skillCategories,
        ]);
    }
}
