<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function manageRoles(Request $request)
    {
        if ($request->user()->cannot('modify roles')) {
            return redirect(route('dashboard'));
        }
        return view('admin.manage-roles');
    }
}
