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
        if ($request->user()->cannot('edit', Skill::class)) {
            return redirect(route('dashboard'));
        }
        $categories = SkillCategory::all();
        return view('rules.skills', compact('categories'));
    }
}
