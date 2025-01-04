<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SysrefController extends Controller
{
    public function skillCheck(Request $request)
    {
        if ($request->user()->cannot('edit', Skill::class)) {
            return redirect(route('dashboard'));
        }
        return view('sysref.skill-check');
    }
}
