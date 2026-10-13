<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function manageRoles(Request $request)
    {
        if ($request->user()->cannot('modify roles')) {
            return redirect(route('dashboard'));
        }
        return view('admin.manage-roles', [
            'users' => User::whereNot('id', User::PLOT_CO_ID)
                ->where('users.deleted', false)
                ->orderBy('name')->get(),
            'roles' => Role::all(),
        ]);
    }

    public function storeRoles(Request $request)
    {
        if ($request->user()->cannot('modify roles')) {
            return redirect(route('dashboard'));
        }
        $request->validate([
            'role' => 'required|array',
        ]);
        $roles = $request->input('role');
        foreach ($roles as $user => $roles) {
            $user = User::find($user);
            if ($user) {
                $user->syncRoles($roles);
            }
        }
        return redirect(route('admin.manage-roles'));
    }
}
