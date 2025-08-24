<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Division;
use App\Models\SkillCategory;
use App\Models\Status;
use App\Models\User;

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
        $characterIds = Character::whereIn('status_id', [Status::APPROVED, Status::PLAYED])
            ->where('user_id', '!=', User::PLOT_CO_ID)
            ->orderBy('name')
            ->get()->pluck('id');
        $skillCategories = SkillCategory::with('skills')
            ->where('id', '!=', SkillCategory::SYSTEM)
            ->get();
        return view('organisation.skills', [
            'validCharacters' => $characterIds,
            'skillCategories' => $skillCategories,
        ]);
    }
}
