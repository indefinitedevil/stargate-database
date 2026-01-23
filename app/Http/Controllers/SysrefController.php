<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Http\Request;

class SysrefController extends Controller
{
    public function skillCheck(Request $request)
    {
        if ($request->user()->cannot('edit', Skill::class)) {
            return redirect(route('dashboard'));
        }
        $categories = SkillCategory::all();
        $skills = [];
        foreach (Skill::all() as $skill) {
            $skills[$skill->category_id][] = $skill;
        }
        foreach ($skills as &$skillList) {
            usort($skillList, [$this, 'compareModelNames']);
        }
        return view('sysref.skill-check', compact('categories', 'skills'));
    }
}
