<?php

namespace App\Http\Controllers\Rules;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $categories = SkillCategory::whereNotIn('id', [SkillCategory::SYSTEM, SkillCategory::REMOVED])->get();
        $skills = [];
        foreach (Skill::whereNotIn('skill_category_id', [SkillCategory::SYSTEM, SkillCategory::REMOVED])->get() as $skill) {
            if (!$skill->hidden || $request->user()->can('edit skill')) {
                $skills[$skill->skill_category_id][] = $skill;
            }
        }
        foreach ($skills as &$skillList) {
            usort($skillList, [$this, 'compareModelNames']);
        }
        return view('rules.skills', compact('categories', 'skills'));
    }
}
