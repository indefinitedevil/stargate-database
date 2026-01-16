<?php

namespace App\Http\Controllers\Rules;

use App\Http\Controllers\Controller;
use App\Models\SkillCategory;

class SkillController extends Controller
{
    public function index()
    {
        $categories = SkillCategory::where('id', '!=', SkillCategory::SYSTEM)->get();
        return view('rules.skills', compact('categories'));
    }
}
